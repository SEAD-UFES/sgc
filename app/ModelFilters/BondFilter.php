<?php

namespace App\ModelFilters;

use App\Helpers\ModelFilterHelper;
use Illuminate\Database\Eloquent\Builder;

trait BondFilter
{
    public function employeeCpfContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'employee', 'cpf', $values);
    }

    public function employeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'employee', 'name', $values);
    }

    public function roleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'role', 'name', $values);
    }

    public function courseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'course', 'name', $values);
    }

    public function poleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'pole', 'name', $values);
    }

    public function volunteerExactly(Builder $builder, $value)
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

        return ModelFilterHelper::simpleOperation($builder, 'volunteer', '=', $values);
    }

    public function impedimentExactly(Builder $builder, $value)
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

        return ModelFilterHelper::simpleOperation($builder, 'impediment', '=', $values);
    }
}
