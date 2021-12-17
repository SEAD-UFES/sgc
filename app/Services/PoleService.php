<?php

namespace App\Services;

use App\Models\Pole;
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
        (new Pole)->logListed();
        
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
     * @param array $attributes
     * @return Pole
     */
    public function create(array $attributes): Pole
    {
        $pole = Pole::create($attributes);

        return $pole;
    }

    /**
     * Undocumented function
     *
     * @param Pole $pole
     * @return Pole
     */
    public function read(Pole $pole): Pole
    {
        $pole->logFetched($pole);

        return $pole;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param Pole $pole
     * @return Pole
     */
    public function update(array $attributes, Pole $pole): Pole
    {
        $pole->update($attributes);

        return $pole;
    }

    /**
     * Undocumented function
     *
     * @param Pole $pole
     * @return void
     */
    public function delete(Pole $pole)
    {
        $pole->delete();
    }
}
