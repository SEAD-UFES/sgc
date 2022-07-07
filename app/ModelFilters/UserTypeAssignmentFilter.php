<?php

namespace App\ModelFilters;

use App\Helpers\ModelFilterHelper;
use Illuminate\Database\Eloquent\Builder;

trait UserTypeAssignmentFilter
{
    public function userEmailContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'user', 'email', $values);
    }

    public function usertypeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'userType', 'name', $values);
    }

    public function courseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'course', 'name', $values);
    }

    public function beginExactly(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        $values = ModelFilterHelper::convertDateFormat($values);
        return ModelFilterHelper::simpleOperation($builder, 'begin', '=', $values);
    }

    public function beginBigOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        $values = ModelFilterHelper::convertDateFormat($values);
        return ModelFilterHelper::simpleOperation($builder, 'begin', '>=', $values);
    }

    public function beginLowOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        $values = ModelFilterHelper::convertDateFormat($values);
        return ModelFilterHelper::simpleOperation($builder, 'begin', '<=', $values);
    }

    public function endExactly(Builder $builder, $value)
    {
        //^\[([^\[\]]+)\]\[([^\[\]]+)\]$ regex para pegar [asdsad][sadasdads]
        $values = ModelFilterHelper::inputToArray($value);
        $values = ModelFilterHelper::convertDateFormat($values);
        return ModelFilterHelper::simpleOperation($builder, 'end', '=', $values);
    }

    public function endBigOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        $values = ModelFilterHelper::convertDateFormat($values);
        return ModelFilterHelper::simpleOperation($builder, 'end', '>=', $values);
    }

    public function endLowOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        $values = ModelFilterHelper::convertDateFormat($values);
        return ModelFilterHelper::simpleOperation($builder, 'end', '<=', $values);
    }
}
