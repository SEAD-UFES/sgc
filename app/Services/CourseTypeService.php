<?php

namespace App\Services;

use App\Events\ModelListed;
use App\Models\CourseType;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseTypeService
{
    public function list(): LengthAwarePaginator
    {
        ModelListed::dispatch(CourseType::class);

        $query = new CourseType();
        $query = $query->AcceptRequest(CourseType::$accepted_filters)->filter();
        $query = $query->sortable(['name' => 'asc']);

        $courseTypes = $query->paginate(10);
        $courseTypes->withQueryString();

        return $courseTypes;
    }
}
