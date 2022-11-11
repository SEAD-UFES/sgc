<?php

namespace App\Services;

use App\Enums\CallStates;
use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Models\Applicant;
use App\Models\Employee;
use App\Services\Dto\ApplicantDto;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ApplicantService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Applicant::class);

        $query = Applicant::with(['course', 'pole', 'role']);
        $query = $query->AcceptRequest(Applicant::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $applicants = $query->paginate(10);
        $applicants->withQueryString();

        return $applicants;
    }

    /**
     * Undocumented function
     *
     * @param ApplicantDto $ApplicantDto
     *
     * @return Applicant
     */
    public function create(ApplicantDto $ApplicantDto): Applicant
    {
        $applicant = new Applicant([
            'name' => TextHelper::titleCase($ApplicantDto->name),
            'email' => mb_strtolower($ApplicantDto->email),
            'area_code' => $ApplicantDto->areaCode,
            'landline' => $ApplicantDto->landline,
            'mobile' => $ApplicantDto->mobile,
            'hiring_process' => mb_strtoupper($ApplicantDto->hiringProcess),
            'role_id' => $ApplicantDto->roleId,
            'course_id' => $ApplicantDto->courseId,
            'pole_id' => $ApplicantDto->poleId,
            'call_state' => CallStates::NC,
        ]);

        DB::transaction(static function () use ($applicant) {
            $applicant->save();
        });

        return $applicant;
    }

    /**
     * Undocumented function
     *
     * @param Applicant $applicant
     *
     * @return Applicant
     */
    public function read(Applicant $applicant): Applicant
    {
        ModelRead::dispatch($applicant);

        return $applicant;
    }

    /**
     * Undocumented function
     *
     * @param Applicant $applicant
     *
     * @return void
     */
    public function delete(Applicant $applicant): void
    {
        $applicant->delete();
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     * @param Applicant $applicant
     *
     * @return void
     */
    public function changeState(array $attributes, Applicant $applicant): void
    {
        $applicant->call_state = CallStates::tryFrom($attributes['states'])->name;

        $applicant->save();
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     *
     * @return void
     */
    public function batchStore(array $attributes): void
    {
        /**
         * @var array<int, array<string, string>> $applicants
         */
        $applicants = $attributes['applicants'];

        DB::transaction(function () use ($applicants) {
            foreach ($applicants as $applicant) {
                if (Arr::exists($applicant, 'check')) {
                    $ApplicantDto = new ApplicantDto(
                        name: Arr::get($applicant, 'name'),
                        email: Arr::get($applicant, 'email'),
                        areaCode: Arr::get($applicant, 'area_code'),
                        landline: Arr::get($applicant, 'landline'),
                        mobile: Arr::get($applicant, 'mobile'),
                        hiringProcess: Arr::get($applicant, 'hiring_process'),
                        roleId: Arr::get($applicant, 'role_id'),
                        courseId: Arr::get($applicant, 'course_id'),
                        poleId: Arr::get($applicant, 'pole_id'),
                    );

                    $this->create($ApplicantDto);
                }
            }
        });
    }

    public function designateApplicant(Applicant $applicant): Employee
    {
        $alreadyRegistered = Employee::where('email', $applicant->email)->exists();
        if ($alreadyRegistered) {
            throw new Exception('Já existe um funcionário cadastrado com esse e-mail.');
        }

        return new Employee([
            'name' => TextHelper::titleCase($applicant->name),
            'area_code' => $applicant->area_code,
            'landline' => $applicant->landline,
            'mobile' => $applicant->mobile,
            'email' => mb_strtolower($applicant->email),
        ]);
    }
}
