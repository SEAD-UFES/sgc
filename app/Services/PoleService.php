<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Events\ModelRead;
use App\Helpers\TextHelper;
use App\Models\Pole;
use App\Services\Dto\PoleDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PoleService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator<Pole>
     */
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(Pole::class);

        $query = Pole::select('id', 'name', 'description');
        $query = $query->AcceptRequest(Pole::$acceptedFilters)->filter();
        $query = $query->sortable(['name' => 'asc']);

        $poles = $query->paginate(10);
        $poles->withQueryString();

        return $poles;
    }

    /**
     * Undocumented function
     *
     * @param PoleDto $storePoleDto
     *
     * @return Pole
     */
    public function create(PoleDto $storePoleDto): Pole
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
     * @param PoleDto $updatePoleDto
     * @param Pole $pole
     *
     * @return Pole
     */
    public function update(PoleDto $updatePoleDto, Pole $pole): Pole
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
