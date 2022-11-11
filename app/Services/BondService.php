<?php

namespace App\Services;

use App\Events\BondCreated;
use App\Events\BondImpeded;
use App\Events\BondLiberated;
use App\Events\BondReviewRequested;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Events\RightsDocumentArchived;
use App\Helpers\TextHelper;
use App\Interfaces\DocumentRepositoryInterface;
use App\Models\Bond;
use App\Models\DocumentType;
use App\Models\User;
use App\Services\Dto\ReviewBondDto;
use App\Services\Dto\BondDto;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class BondService
{
    public function __construct(private DocumentRepositoryInterface $documentRepository)
    {
    }

    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator<Bond>
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Bond::class);

        $query = Bond::with(['employee', 'courses', 'role', 'poles']);
        $query = $query->AcceptRequest(Bond::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $bonds = $query->paginate(10);
        $bonds->withQueryString();

        return $bonds;
    }

    /**
     * Undocumented function
     *
     * @param BondDto $bondDto
     *
     * @return mixed
     */
    public function create(BondDto $bondDto): mixed
    {
        $bond = null;

        DB::transaction(static function () use ($bondDto, &$bond) {
            $bond = new Bond([
                'employee_id' => $bondDto->employeeId,
                'role_id' => $bondDto->roleId,
                'begin' => $bondDto->begin,
                'terminated_at' => $bondDto->terminatedAt,
                'hiring_process' => $bondDto->hiringProcess,
                'volunteer' => $bondDto->volunteer,
            ]);
            $bond->save();

            if ($bondDto->courseId) {
                $bond->courses()->attach($bondDto->courseId);
            }

            if ($bondDto->poleId) {
                $bond->poles()->attach($bondDto->poleId);
            }

            $bond->impediments()->create([
                'description' => '[SGC: Vínculo ainda não revisado]',
                'reviewer_id' => User::where('login', 'sgc_system')->first()?->id,
                'reviewed_at' => Carbon::now(),
            ]);

            $bond->impediments()->create([
                'description' => '[SGC: Documento "Termo de cessão de direitos" ainda não arquivado]',
                'reviewer_id' => User::where('login', 'sgc_system')->first()?->id,
                'reviewed_at' => Carbon::now(),
            ]);

            $bond->qualification()->create([
                'knowledge_area' => $bondDto->qualificationKnowledgeArea,
                'course_name' => TextHelper::titleCase($bondDto->qualificationCourse),
                'institution_name' => TextHelper::titleCase($bondDto->qualificationInstitution),
            ]);

            BondCreated::dispatch($bond);
        });
        
        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     *
     * @return Bond
     */
    public function read(Bond $bond): Bond
    {
        ModelRead::dispatch($bond);

        $bond->setAttribute('documents', $this->documentRepository->getByBondId($bond->id));

        $activity = $this->getActivity($bond);

        foreach ($activity as $property => $value) {
            $bond->setAttribute($property, $value);
        }

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param BondDto $updateBondDto
     * @param Bond $bond
     *
     * @return Bond
     */
    public function update(BondDto $updateBondDto, Bond $bond): Bond
    {
        DB::transaction(static function () use ($updateBondDto, $bond) {
            $bond->update([
                'employee_id' => $updateBondDto->employeeId,
                'role_id' => $updateBondDto->roleId,
                'course_id' => $updateBondDto->courseId,
                'pole_id' => $updateBondDto->poleId,
                'begin' => $updateBondDto->begin,
                'terminated_at' => $updateBondDto->terminatedAt,
                'hiring_process' => $updateBondDto->hiringProcess,
                'volunteer' => $updateBondDto->volunteer,
            ]);

            if ($updateBondDto->qualificationKnowledgeArea !== null) {
                $bond->qualification()->updateOrCreate([
                    'knowledge_area' => $updateBondDto->qualificationKnowledgeArea,
                    'course_name' => TextHelper::titleCase($updateBondDto->qualificationCourse),
                    'institution_name' => TextHelper::titleCase($updateBondDto->qualificationInstitution),
                ]);
            }
        });

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     *
     * @return void
     */
    public function delete(Bond $bond)
    {
        DB::transaction(static function () use ($bond) {
            foreach ($bond->documents as $document) {
                $document->delete();
            }

            $bond->qualification()->delete();

            $bond->delete();
        });
    }

    /**
     * Undocumented function
     *
     * @param ReviewBondDto $reviewBondDto
     * @param Bond $bond
     *
     * @return Bond
     */
    public function review(ReviewBondDto $reviewBondDto, Bond $bond): Bond
    {
        //get impediment; check if bond have 'rights'; if not, impediment = true.
        $impediment = $reviewBondDto->impediment;
        $impediment_description = $reviewBondDto->impedimentDescription;

        $bondHaveRights = self::bondHaveRights($bond);

        if (!$bondHaveRights) {
            $impediment = true;
            $impediment_description = "{$impediment_description}\n[SGC: O Sistema não encontrou documento de Termo de cessão de direitos.]";
        }

        $bond->update([
            'impediment' => $impediment,
            'impediment_description' => $impediment_description,
            'uaba_checked_at' => now(),
        ]);

        if ($bond->impediment === true) {
            BondImpeded::dispatch($bond);
        } else {
            BondLiberated::dispatch($bond);
            RightsDocumentArchived::dispatch($bond);
        }

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param array<string, string> $attributes
     * @param Bond $bond
     *
     * @return Bond
     */
    public function requestReview(array $attributes, Bond $bond): Bond
    {
        BondReviewRequested::dispatch($bond);

        return $bond;
    }

    /**
     * Undocumented function
     *
     * @param Bond $bond
     *
     * @return array<string, User|Carbon|null>
     */
    private function getActivity(Bond $bond): array
    {
        $activityCreated = Activity::select('activity_log.causer_id', 'activity_log.created_at')
            ->where('activity_log.subject_id', $bond->id)
            ->where('activity_log.subject_type', Bond::class)
            ->where('activity_log.event', 'created')
            ->where('activity_log.causer_type', User::class)
            ->orderBy('activity_log.id', 'desc')
            ->first();

        $createdBy = User::find($activityCreated?->causer_id);
        $createdOn = $activityCreated?->created_at;

        $activityUpdated = Activity::select('activity_log.causer_id', 'activity_log.created_at')
            ->where('activity_log.subject_id', $bond->id)
            ->where('activity_log.subject_type', Bond::class)
            ->where('activity_log.event', 'updated')
            ->where('activity_log.causer_type', User::class)
            ->orderBy('activity_log.id', 'desc')
            ->first();

        $updatedBy = User::find($activityUpdated?->causer_id);
        $updatedOn = $activityUpdated?->created_at;

        return [
            'createdBy' => $createdBy,
            'createdOn' => $createdOn,
            'updatedBy' => $updatedBy,
            'updatedOn' => $updatedOn,
        ];
    }

    /**
     * @param Bond $bond
     *
     * @return bool
     */
    public static function bondHaveRights(Bond $bond): bool
    {
        $typeId = DocumentType::where('name', 'Termo de cessão de direitos')->first()?->id;

        if ($bond->documents->where('document_type_id', $typeId)->count() > 0) {
            return true;
        }
        
        return false;
    }
}
