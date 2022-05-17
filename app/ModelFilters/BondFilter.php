<?php

namespace App\ModelFilters;

use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Database\Eloquent\Builder;

trait BondFilter
{
    public function employeeCpfContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'employee', 'cpf', $values);
    }

    public function employeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'employee', 'name', $values);
    }

    public function roleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'role', 'name', $values);
    }

    public function courseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'course', 'name', $values);
    }

    public function poleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'pole', 'name', $values);
    }

    public function volunteerExactly(Builder $builder, $value)
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

        return ModelFilterHelpers::simpleOperation($builder, 'volunteer', '=', $values);
    }

    public function impedimentExactly(Builder $builder, $value)
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

        return ModelFilterHelpers::simpleOperation($builder, 'impediment', '=', $values);
    }
}
