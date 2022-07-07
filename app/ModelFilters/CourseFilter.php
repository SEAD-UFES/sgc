<?php

namespace App\ModelFilters;

use App\Helpers\ModelFilterHelper;
use Illuminate\Database\Eloquent\Builder;

trait CourseFilter
{
    public function nameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'name', $values);
    }

    public function descriptionContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'description', $values);
    }

    public function courseTypeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'courseType', 'name', $values);
    }

    public function beginExactly(Builder $builder, $value)
    {
        //^\[([^\[\]]+)\]\[([^\[\]]+)\]$ regex para pegar [asdsad][sadasdads]
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
