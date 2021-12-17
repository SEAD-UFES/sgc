<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;

trait CourseFilter
{
    public function nameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'name', $values);
        return $builder;
    }

    public function descriptionContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'description', $values);
        return $builder;
    }

    public function courseTypeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'courseType', 'name', $values);
        return $builder;
    }

    public function beginExactly(Builder $builder, $value)
    {
        //^\[([^\[\]]+)\]\[([^\[\]]+)\]$ regex para pegar [asdsad][sadasdads]
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
