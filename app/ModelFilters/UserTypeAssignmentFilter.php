<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;

trait UserTypeAssignmentFilter
{
    public function userEmailContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'user', 'email', $values);
        return $builder;
    }

    public function usertypeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'userType', 'name', $values);
        return $builder;
    }

    public function courseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'course', 'name', $values);
        return $builder;
    }

    public function beginExactly(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        $builder = ModelFilterHelpers::simpleOperation($builder, 'begin', '=', $values);
        return $builder;
    }

    public function beginBigOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        $builder = ModelFilterHelpers::simpleOperation($builder, 'begin', '>=', $values);
        return $builder;
    }

    public function beginLowOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        $builder = ModelFilterHelpers::simpleOperation($builder, 'begin', '<=', $values);
        return $builder;
    }

    public function endExactly(Builder $builder, $value)
    {
        //^\[([^\[\]]+)\]\[([^\[\]]+)\]$ regex para pegar [asdsad][sadasdads]
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        $builder = ModelFilterHelpers::simpleOperation($builder, 'end', '=', $values);
        return $builder;
    }

    public function endBigOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        $builder = ModelFilterHelpers::simpleOperation($builder, 'end', '>=', $values);
        return $builder;
    }

    public function endLowOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        $builder = ModelFilterHelpers::simpleOperation($builder, 'end', '<=', $values);
        return $builder;
    }
}
