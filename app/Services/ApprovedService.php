<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Models\Approved;
use App\Models\ApprovedState;
use App\Models\Employee;
use App\Services\Dto\StoreApprovedDto;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ApprovedService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Approved::class);

        $query = Approved::with(['approvedState', 'course', 'pole', 'role']);
        $query = $query->AcceptRequest(Approved::$accepted_filters)->filter();
        $query = $query->sortable(['updated_at' => 'desc']);

        $approveds = $query->paginate(10);
        $approveds->withQueryString();

        return $approveds;
    }

    /**
     * Undocumented function
     *
     * @param StoreApprovedDto $storeApprovedDto
     *
     * @return Approved
     */
    public function create(StoreApprovedDto $storeApprovedDto): Approved
    {
        $approved = new Approved([
            'name' => TextHelper::titleCase($storeApprovedDto->name),
            'email' => mb_strtolower($storeApprovedDto->email),
            'area_code' => $storeApprovedDto->areaCode,
            'phone' => $storeApprovedDto->phone,
            'mobile' => $storeApprovedDto->mobile,
            'announcement' => mb_strtoupper($storeApprovedDto->announcement),
            'role_id' => $storeApprovedDto->roleId,
            'course_id' => $storeApprovedDto->courseId,
            'pole_id' => $storeApprovedDto->poleId,
            'approved_state_id' => ApprovedState::where('name', 'Não contatado')->first()?->getAttribute('id'),
        ]);

        DB::transaction(static function () use ($approved) {
            $approved->save();
        });

        return $approved;
    }

    /**
     * Undocumented function
     *
     * @param Approved $approved
     *
     * @return Approved
     */
    public function read(Approved $approved): Approved
    {
        ModelRead::dispatch($approved);

        return $approved;
    }

    /**
     * Undocumented function
     *
     * @param Approved $approved
     *
     * @return void
     */
    public function delete(Approved $approved): void
    {
        $approved->delete();
    }

    /**
     * Undocumented function
     *
     * @param array<string> $attributes
     * @param Approved $approved
     *
     * @return void
     */
    public function changeState(array $attributes, Approved $approved): void
    {
        $new_state_id = $attributes['states'];
        $approved->approved_state_id = (int) $new_state_id;

        $approved->save();
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
         * @var array<int, array<string, string>> $approveds
         */
        $approveds = $attributes['approveds'];

        DB::transaction(function () use ($approveds) {
            foreach ($approveds as $approved) {
                if (Arr::exists($approved, 'check')) {
                    $storeApprovedDto = new StoreApprovedDto(
                        name: Arr::get($approved, 'name'),
                        email: Arr::get($approved, 'email'),
                        areaCode: Arr::get($approved, 'area_code'),
                        phone: Arr::get($approved, 'phone'),
                        mobile: Arr::get($approved, 'mobile'),
                        announcement: Arr::get($approved, 'announcement'),
                        roleId: Arr::get($approved, 'role_id'),
                        courseId: Arr::get($approved, 'course_id'),
                        poleId: Arr::get($approved, 'pole_id'),
                    );

                    $this->create($storeApprovedDto);
                }
            }
        });
    }

    public function designateApproved(Approved $approved): Employee
    {
        $alreadyRegistered = Employee::where('email', $approved->email)->exists();
        if ($alreadyRegistered) {
            throw new Exception('Já existe um funcionário cadastrado com esse e-mail.');
        }

        return new Employee([
            'name' => TextHelper::titleCase($approved->name),
            'area_code' => $approved->area_code,
            'phone' => $approved->phone,
            'mobile' => $approved->mobile,
            'email' => mb_strtolower($approved->email),
        ]);
    }
}
