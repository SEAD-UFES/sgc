<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Models\Pole;
use App\Services\Dto\StorePoleDto;
use App\Services\Dto\UpdatePoleDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PoleService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Pole::class);

        $poles_query = new Pole();
        $poles_query = $poles_query->AcceptRequest(Pole::$accepted_filters)->filter();
        $poles_query = $poles_query->sortable(['name' => 'asc']);

        $poles = $poles_query->paginate(10);
        $poles->withQueryString();

        return $poles;
    }

    /**
     * Undocumented function
     *
     * @param StorePoleDto $storePoleDto
     *
     * @return Pole
     */
    public function create(StorePoleDto $storePoleDto): Pole
    {
        return Pole::create([
            'name' => TextHelper::titleCase($storePoleDto->name),
            'description' => TextHelper::titleCase($storePoleDto->description),
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Pole $pole
     *
     * @return Pole
     */
    public function read(Pole $pole): Pole
    {
        ModelRead::dispatch($pole);

        return $pole;
    }

    /**
     * Undocumented function
     *
     * @param UpdatePoleDto $updatePoleDto
     * @param Pole $pole
     *
     * @return Pole
     */
    public function update(UpdatePoleDto $updatePoleDto, Pole $pole): Pole
    {
        $pole->update([
            'name' => TextHelper::titleCase($updatePoleDto->name),
            'description' => TextHelper::titleCase($updatePoleDto->description),
        ]);

        return $pole;
    }

    /**
     * Undocumented function
     *
     * @param Pole $pole
     *
     * @return void
     */
    public function delete(Pole $pole)
    {
        $pole->delete();
    }
}
