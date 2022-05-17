<?php

namespace App\ModelFilters;

use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Database\Eloquent\Builder;

trait UserFilter
{
    public function emailContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::contains($builder, 'email', $values);
    }

    // public function usertypeNameContains(Builder $builder, $value)
    // {
    //     $values = ModelFilterHelpers::inputToArray($value);
    //     $builder = ModelFilterHelpers::relationContains($builder, 'userType', 'name', $values);
    //     return $builder;
    // }

    public function activeExactly(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);

        foreach ($values as $key => $value) {
            if (in_array(strtolower($value), ['sim', '1', 'true'])) {
                $values[$key] = 1;
            } elseif (in_array(strtolower($value), ['não', 'nao', '0', 'false'])) {
                $values[$key] = 0;
            } else {
                $values[$key] = null;
            }
        }

        return ModelFilterHelpers::simpleOperation($builder, 'active', '=', $values);
    }

    public function employeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'employee', 'name', $values);
    }

    public function employeeId(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationSimpleOperation($builder, 'employee', 'id', '=', $values);
    }
}
