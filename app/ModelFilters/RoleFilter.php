<?php

namespace App\ModelFilters;

use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Database\Eloquent\Builder;

trait RoleFilter
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

    public function grantvalueExactly(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::simpleOperation($builder, 'grant_value', '=', $values);
    }

    public function grantvalueBigOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        return ModelFilterHelpers::simpleOperation($builder, 'grant_value', '>=', $values);
    }

    public function grantvalueLowOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $values = ModelFilterHelpers::convertDateFormat($values);
        return ModelFilterHelpers::simpleOperation($builder, 'grant_value', '<=', $values);
    }

    public function grantTypeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'grantType', 'name', $values);
    }
}
