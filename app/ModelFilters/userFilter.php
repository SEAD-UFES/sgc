<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;

trait UserFilter
{
    public function emailContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'email', $values);
        return $builder;
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
            } else if (in_array(strtolower($value), ['não', 'nao', '0', 'false'])) {
                $values[$key] = 0;
            } else {
                $values[$key] = null;
            }
        }

        $builder = ModelFilterHelpers::simpleOperation($builder, 'active', '=', $values);
        return $builder;
    }

    public function employeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'employee', 'name', $values);
        return $builder;
    }

    public function employeeId(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationSimpleOperation($builder, 'employee', 'id', '=', $values);
        return $builder;
    }
}
