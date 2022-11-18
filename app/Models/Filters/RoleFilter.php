<?php

namespace App\Models\Filters;

use App\Helpers\ModelFilterHelper;
use Illuminate\Database\Eloquent\Builder;

trait RoleFilter
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

    public function grantvalueExactly(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::simpleOperation($builder, 'grant_value', '=', $values);
    }

    public function grantvalueBigOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        $values = ModelFilterHelper::convertDateFormat($values);
        return ModelFilterHelper::simpleOperation($builder, 'grant_value', '>=', $values);
    }

    public function grantvalueLowOrEqu(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        $values = ModelFilterHelper::convertDateFormat($values);
        return ModelFilterHelper::simpleOperation($builder, 'grant_value', '<=', $values);
    }

    public function grantTypeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'grantType', 'name', $values);
    }
}
