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
use App\Interfaces\BondDocumentRepositoryInterface;
use App\Models\Bond;
use App\Models\BondDocument;
use App\Models\Document;
use App\Models\DocumentType;
use App\Models\EmployeeDocument;
use App\Models\User;
use App\Services\Dto\ReviewBondDto;
use App\Services\Dto\StoreBondDto;
use App\Services\Dto\UpdateBondDto;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class BondService
{
    public function __construct(private BondDocumentRepositoryInterface $documentRepository)
    {
    }

    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Bond::class);

        $query = Bond::with(['employee', 'course', 'role', 'pole']);
        $query = $query->AcceptRequest(Bond::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $bonds = $query->paginate(10);
        $bonds->withQueryString();

        return $bonds;
    }

    /**
     * Undocumented function
     *
     * @param StoreBondDto $storeBondDto
     *
     * @return mixed
     */
    public function create(StoreBondDto $storeBondDto): mixed
    {
        return DB::transaction(static function () use ($storeBondDto) {
            $bond = new Bond([
                'employee_id' => $storeBondDto->employeeId,
                'role_id' => $storeBondDto->roleId,
                'course_id' => $storeBondDto->courseId,
                'pole_id' => $storeBondDto->poleId,
                'begin' => $storeBondDto->begin,
                'end' => $storeBondDto->end,
                'terminated_at' => null,
                'volunteer' => $storeBondDto->volunteer,
                'impediment' => true,
                'impediment_description' => '[SGC: Vínculo ainda não revisado]',
                'uaba_checked_at' => null,
            ]);

            $bond->save();

            $bond->qualification()->create([
                'knowledge_area' => $storeBondDto->knowledgeArea,
                'course_name' => TextHelper::titleCase($storeBondDto->courseName),
                'institution_name' => TextHelper::titleCase($storeBondDto->institutionName),
            ]);

            self::carryEmployeeDocuments($bond);

            BondCreated::dispatch($bond);
        });
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
     * @param UpdateBondDto $updateBondDto
     * @param Bond $bond
     *
     * @return Bond
     */
    public function update(UpdateBondDto $updateBondDto, Bond $bond): Bond
    {
        DB::transaction(static function () use ($updateBondDto, $bond) {
            $bond->update([
                'employee_id' => $updateBondDto->employeeId,
                'role_id' => $updateBondDto->roleId,
                'course_id' => $updateBondDto->courseId,
                'pole_id' => $updateBondDto->poleId,
                'begin' => $updateBondDto->begin,
                'end' => $updateBondDto->end,
                'volunteer' => $updateBondDto->volunteer,
            ]);

            $bond->qualification()->updateOrCreate([
                'knowledge_area' => $updateBondDto->knowledgeArea,
                'course_name' => TextHelper::titleCase($updateBondDto->courseName),
                'institution_name' => TextHelper::titleCase($updateBondDto->institutionName),
            ]);
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
            foreach ($bond->bondDocuments as $document) {
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

        $bondHaveRights = $bond->hasRightsDocuments();

        if (! $bondHaveRights) {
            $impediment = true;
            $impediment_description = "{$impediment_description}\n[SGC: O Sistema não encontrou documento de Termos e Licença.]";
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
     * @return void
     */
    private static function carryEmployeeDocuments(Bond $bond): void
    {
        $employeeDocuments = EmployeeDocument::where('employee_id', $bond->employee_id)->get();
        foreach ($employeeDocuments as $employeeDocument) {
            $bondDocument = new BondDocument([
                'bond_id' => $bond->id,
            ]);
            $bondDocument->save();

            /**
             * @var Document $employeeBaseDocument
             */
            $employeeBaseDocument = $employeeDocument->document;

            /**
             * @var DocumentType $employeeBaseDocumentType
             */
            $employeeBaseDocumentType = $employeeBaseDocument->documentType;

            $newDocument = new Document([
                'original_name' => $employeeBaseDocument->original_name,
                'file_data' => $employeeBaseDocument->file_data,
                'document_type_id' => $employeeBaseDocumentType->id,
                'documentable_type' => BondDocument::class,
                'documentable_id' => $bondDocument->id,
            ]);
            $newDocument->save();
        }
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
}
