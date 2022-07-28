<?php

namespace App\Services;

use App\Helpers\TextHelper;
use App\Models\Pole;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class PoleService
{
    /**
     * Undocumented function
     *
     * @return LengthAwarePaginator
     */
    public function list(): LengthAwarePaginator
    {
        (new Pole())->logListed();

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
     *
     * @return Pole
     */
    public function create(array $attributes): Pole
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            return TextHelper::titleCase($value);
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        return Pole::create($attributes);
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
        $pole->logFetched();

        return $pole;
    }

    /**
     * Undocumented function
     *
     * @param array $attributes
     * @param Pole $pole
     *
     * @return Pole
     */
    public function update(array $attributes, Pole $pole): Pole
    {
        $attributes = Arr::map($attributes, static function ($value, $key) {
            return TextHelper::titleCase($value);
        });

        $attributes = Arr::map($attributes, static function ($value, $key) {
            return $value === '' ? null : $value;
        });

        $pole->update($attributes);

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
