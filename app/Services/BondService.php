<?php

namespace App\Services;

use App\Events\BondCreated;
use App\Events\BondImpeded;
use App\Events\BondReviewRequested;
use App\Events\BondUpdated;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Events\RightsDocumentArchived;
use App\Helpers\TextHelper;
use App\Interfaces\DocumentRepositoryInterface;
use App\Models\Bond;
use App\Models\DocumentType;
use App\Models\Impediment;
use App\Models\User;
use App\Services\Dto\BondDto;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class BondService
{
    private const RIGHTS_IMPEDIMENT_DESCRIPTION = '[SGC: Documento "Termo de cessão de direitos" ainda não importado]';

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

        $lastOpenImpediments = Impediment::select(['impediments.bond_id', DB::raw('MAX(impediments.created_at) as created_at')])
            ->where('impediments.closed_at', '=', null)
            ->groupBy('impediments.bond_id');

        $query = Bond::with(['employee', 'courses', 'role', 'poles'])
            ->select(['bonds.*', 'lastOpenImpediments.bond_id', 'lastOpenImpediments.created_at as last_open_impediment_date'])
            ->leftJoinSub($lastOpenImpediments, 'lastOpenImpediments', function ($join) {
                $join->on('bonds.id', '=', 'lastOpenImpediments.bond_id');
            })
            ->join('employees', 'bonds.employee_id', '=', 'employees.id')
            ->join('roles', 'bonds.role_id', '=', 'roles.id')
            ->leftJoin('bond_course', 'bonds.id', '=', 'bond_course.bond_id')
            ->leftJoin('courses', 'bond_course.course_id', '=', 'courses.id')
            ->leftJoin('bond_pole', 'bonds.id', '=', 'bond_pole.bond_id')
            ->leftJoin('poles', 'bond_pole.pole_id', '=', 'poles.id')
            ->addSelect(['employees.cpf as employee_cpf', 'employees.name as employee_name', 'roles.name as role_name', 'courses.name as course_name', 'poles.name as pole_name'])

            ->AcceptRequest(Bond::$acceptedFilters)->filter()
            ->sortable()
            ->orderByDesc('bonds.updated_at');

        return $query->paginate(10)
            ->withQueryString();
    }

    /**
     * Undocumented function
     *
     * @param BondDto $bondDto
     *
     * @return ?Bond
     */
    public function create(BondDto $bondDto): ?Bond
    {
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

            if (isset($bondDto->courseId)) {
                $bond->courses()->sync([$bondDto->courseId]);
            }

            if (isset($bondDto->courseClassId)) {
                $bond->courseClasses()->sync([$bondDto->courseClassId]);
            }


            if (isset($bondDto->poleId)) {
                $bond->poles()->sync([$bondDto->poleId]);
            }

            $bond->impediments()->create([
                'description' => '[SGC: Vínculo ainda não revisado]',
                'reviewer_id' => User::where('login', 'sgc_system')->first()?->id,
            ]);

            $bond->impediments()->create([
                'description' => self::RIGHTS_IMPEDIMENT_DESCRIPTION,
                'reviewer_id' => User::where('login', 'sgc_system')->first()?->id,
            ]);

            $bond->qualification()->create([
                'knowledge_area' => $bondDto->qualificationKnowledgeArea,
                'course_name' => TextHelper::titleCase($bondDto->qualificationCourse),
                'institution_name' => TextHelper::titleCase($bondDto->qualificationInstitution),
            ]);

            BondCreated::dispatch($bond);
            BondImpeded::dispatch($bond);

            return $bond;
        });

        return null;
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
     * @param BondDto $bondDto
     * @param Bond $bond
     *
     * @return Bond
     */
    public function update(BondDto $bondDto, Bond $bond): Bond
    {
        DB::transaction(static function () use ($bondDto, $bond) {
            $bond->update([
                'employee_id' => $bondDto->employeeId,
                'role_id' => $bondDto->roleId,
                'begin' => $bondDto->begin,
                'terminated_at' => $bondDto->terminatedAt,
                'hiring_process' => $bondDto->hiringProcess,
                'volunteer' => $bondDto->volunteer,
            ]);

            if (isset($bondDto->courseId)) {
                $bond->courses()->sync([$bondDto->courseId]);
            } else {
                $bond->courses()->detach();
            }

            if (isset($bondDto->courseClassId)) {
                $bond->courseClasses()->sync([$bondDto->courseClassId]);
            } else {
                $bond->courseClasses()->detach();
            }

            if (isset($bondDto->poleId)) {
                $bond->poles()->sync([$bondDto->poleId]);
            } else {
                $bond->poles()->detach();
            }

            $bond->impediments()->create([
                'description' => '[SGC: Vínculo atualizado e ainda não revisado]',
                'reviewer_id' => User::where('login', 'sgc_system')->first()?->id,
            ]);

            if ($bondDto->qualificationKnowledgeArea !== null) {
                $bond->qualification()->updateOrCreate(
                    ['bond_id' => $bond->id],
                    [
                        'knowledge_area' => $bondDto->qualificationKnowledgeArea,
                        'course_name' => TextHelper::titleCase($bondDto->qualificationCourse),
                        'institution_name' => TextHelper::titleCase($bondDto->qualificationInstitution),
                    ]
                );

                BondUpdated::dispatch($bond);
                BondImpeded::dispatch($bond);

                self::bondCheckRights($bond);
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
            $bond->documents()->delete();

            $bond->courses()->detach();
            $bond->poles()->detach();

            $bond->qualification()->delete();

            $bond->impediments()->delete();

            $bond->delete();
        });
    }

    // BondLiberated::dispatch($bond);
    // RightsDocumentArchived::dispatch($bond);

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

    public static function bondCheckRights(Bond $bond): void
    {
        $bondHaveRights = self::bondHaveRights($bond);

        $bondAlreadyImpededForRights = $bond->impediments->contains('description', self::RIGHTS_IMPEDIMENT_DESCRIPTION);

        if ($bondHaveRights && $bondAlreadyImpededForRights) {
            $bond->impediments()->where('description', self::RIGHTS_IMPEDIMENT_DESCRIPTION)->update([
                'closed_by_id' => User::where('login', 'sgc_system')->first()?->id,
                'closed_at' => Carbon::now(),
            ]);
            RightsDocumentArchived::dispatch($bond);
        }

        if (! $bondHaveRights && ! $bondAlreadyImpededForRights) {
            $bond->impediments()->create([
                'description' => self::RIGHTS_IMPEDIMENT_DESCRIPTION,
                'reviewer_id' => User::where('login', 'sgc_system')->first()?->id,
            ]);
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
