<?php

namespace App\Services;

use App\Models\Pole;
use App\CustomClasses\SgcLogger;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PoleService
{
    public function list(): LengthAwarePaginator
    {
        SgcLogger::writeLog(target: 'Pole', action: 'index');

        $poles_query = new Pole();
        $poles_query = $poles_query->AcceptRequest(Pole::$accepted_filters)->filter();
        $poles_query = $poles_query->sortable(['name' => 'asc']);
        $poles = $poles_query->paginate(10);
        $poles->withQueryString();

        return $poles;
    }

    public function create(array $attributes): Pole
    {
        $pole = new Pole;
        $pole->name = $attributes['name'];
        $pole->description = $attributes['description'];

        SgcLogger::writeLog(target: $pole);

        $pole->save();

        return $pole;
    }

    public function update(array $attributes, Pole $pole): Pole
    {

        $pole->name = $attributes['name'];
        $pole->description = $attributes['description'];

        SgcLogger::writeLog(target: $pole);

        $pole->save();

        return $pole;
    }

    public function delete(Pole $pole)
    {
        SgcLogger::writeLog(target: $pole);

        $pole->delete();
    }
}
