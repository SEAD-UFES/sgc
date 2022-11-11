<?php

namespace App\ModelFilters;

use App\Helpers\ModelFilterHelper;
use Illuminate\Database\Eloquent\Builder;

trait UserFilter
{
    public function emailContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'login', $values);
    }

    // public function usertypeNameContains(Builder $builder, $value)
    // {
    //     $values = ModelFilterHelper::inputToArray($value);
    //     $builder = ModelFilterHelper::relationContains($builder, 'userType', 'name', $values);
    //     return $builder;
    // }

    public function activeExactly(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);

        foreach ($values as $key => $value) {
            if (in_array(strtolower($value), ['sim', '1', 'true'])) {
                $values[$key] = 1;
            } elseif (in_array(strtolower($value), ['não', 'nao', '0', 'false'])) {
                $values[$key] = 0;
            } else {
                $values[$key] = null;
            }
        }

        return ModelFilterHelper::simpleOperation($builder, 'active', '=', $values);
    }

    public function employeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'employee', 'name', $values);
    }

    public function employeeId(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationSimpleOperation($builder, 'employee', 'id', '=', $values);
    }
}
