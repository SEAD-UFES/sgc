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
     * @return LengthAwarePaginator<Applicant>
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Applicant::class);

        $query = Applicant::with(['course', 'pole', 'role'])

            // AcceptRequest: mehdi-fathi/eloquent-filter
            ->AcceptRequest(Applicant::$acceptedFilters)->filter()
            ->sortable()
            ->orderByDesc('applicants.updated_at');

        return $query->paginate(10)

            // AbstractPaginator->withQueryString():
            // append all of the current request's query string values to the pagination links
            // [https://laravel.com/docs/9.x/pagination]
            ->withQueryString();
    }

    /**
     * Undocumented function
     *
     * @param ApplicantDto $applicantDto
     *
     * @return Applicant
     */
    public function create(ApplicantDto $applicantDto): Applicant
    {
        $applicant = new Applicant([
            'name' => TextHelper::titleCase($applicantDto->name),
            'email' => mb_strtolower($applicantDto->email),
            'area_code' => $applicantDto->areaCode,
            'landline' => $applicantDto->landline,
            'mobile' => $applicantDto->mobile,
            'hiring_process' => mb_strtoupper($applicantDto->hiringProcess),
            'role_id' => $applicantDto->roleId,
            'course_id' => $applicantDto->courseId,
            'pole_id' => $applicantDto->poleId,
            'call_state' => CallStates::NC,
        ]);

        $applicant->save();

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
        $applicant->call_state = CallStates::from((string) $attributes['states']);

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
                    $applicantDto = new ApplicantDto(
                        name: (string) Arr::get($applicant, 'name'),
                        email: (string) Arr::get($applicant, 'email'),
                        areaCode: (string) Arr::get($applicant, 'area_code'),
                        landline: (string) Arr::get($applicant, 'landline'),
                        mobile: (string) Arr::get($applicant, 'mobile'),
                        hiringProcess: (string) Arr::get($applicant, 'hiring_process'),
                        roleId: (int) Arr::get($applicant, 'role_id'),
                        courseId: Arr::get($applicant, 'course_id') !== null ? (int) Arr::get($applicant, 'course_id') : null,
                        poleId: Arr::get($applicant, 'pole_id') !== null ? (int) Arr::get($applicant, 'pole_id') : null,
                    );

                    $this->create($applicantDto);
                }
            }
        });
    }

    public function designateApplicant(Applicant $applicant): Employee
    {
        // TODO: Refactor this method
        if (Employee::where('email', $applicant->email)->exists()) {
            throw new Exception('Já existe um colaborador cadastrado com esse e-mail.');
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
