<?php

namespace App\ModelFilters;

use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Database\Eloquent\Builder;

trait CourseFilter
{
    public function nameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::contains($builder, 'name', $values);
    }

    public function descriptionContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::contains($builder, 'description', $values);
    }

    public function courseTypeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'courseType', 'name', $values);
    }

    public function beginExactly(Builder $builder, $value)
    {
        //^\[([^\[\]]+)\]\[([^\[\]]+)\]$ regex para pegar [asdsad][sadasdads]
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        return ModelFilterHelpers::simpleOperation($builder, 'begin', '=', $values);
    }

    public function beginBigOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        return ModelFilterHelpers::simpleOperation($builder, 'begin', '>=', $values);
    }

    public function beginLowOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        return ModelFilterHelpers::simpleOperation($builder, 'begin', '<=', $values);
    }

    public function endExactly(Builder $builder, $value)
    {
        //^\[([^\[\]]+)\]\[([^\[\]]+)\]$ regex para pegar [asdsad][sadasdads]
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        return ModelFilterHelpers::simpleOperation($builder, 'end', '=', $values);
    }

    public function endBigOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        return ModelFilterHelpers::simpleOperation($builder, 'end', '>=', $values);
    }

    public function endLowOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        return ModelFilterHelpers::simpleOperation($builder, 'end', '<=', $values);
    }
}
