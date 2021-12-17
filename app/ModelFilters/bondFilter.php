<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;

trait BondFilter
{
    public function employeeCpfContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'employee', 'cpf', $values);
        return $builder;
    }

    public function employeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'employee', 'name', $values);
        return $builder;
    }

    public function roleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'role', 'name', $values);
        return $builder;
    }

    public function courseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'course', 'name', $values);
        return $builder;
    }

    public function poleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'pole', 'name', $values);
        return $builder;
    }

    public function volunteerExactly(Builder $builder, $value)
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

        $builder = ModelFilterHelpers::simpleOperation($builder, 'volunteer', '=', $values);
        return $builder;
    }

    public function impedimentExactly(Builder $builder, $value)
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

        $builder = ModelFilterHelpers::simpleOperation($builder, 'impediment', '=', $values);
        return $builder;
    }
}
