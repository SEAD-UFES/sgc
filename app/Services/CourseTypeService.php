<?php

namespace App\Services;

use App\Models\CourseType;
use App\CustomClasses\SgcLogger;
use Illuminate\Pagination\LengthAwarePaginator;

class CourseTypeService
{
    public function list(): LengthAwarePaginator
    {
        SgcLogger::writeLog(target: 'CourseType', action: 'index');

        $query = new CourseType();
        $query = $query->AcceptRequest(CourseType::$accepted_filters)->filter();
        $query = $query->sortable(['name' => 'asc']);
        $courseTypes = $query->paginate(10);
        $courseTypes->withQueryString();

        return $courseTypes;
    }
}
