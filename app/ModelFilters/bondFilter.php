<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;

trait bondFilter
{
    public function employee_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'employee', 'name', $values);
        return $builder;
    }

    public function role_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'role', 'name', $values);
        return $builder;
    }

    public function course_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'course', 'name', $values);
        return $builder;
    }

    public function pole_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'pole', 'name', $values);
        return $builder;
    }

    public function volunteer_exactly(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);

        foreach ($values as $key => $value) {
            if (in_array(strtolower($value), ['sim', '1', 'true'])) $values[$key] = 1;
            else if (in_array(strtolower($value), ['não', 'nao', '0', 'false'])) $values[$key] = 0;
            else $values[$key] = null;
        }

        $builder = ModelFilterHelpers::simple_operation($builder, 'volunteer', '=', $values);
        return $builder;
    }

    public function impediment_exactly(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);

        foreach ($values as $key => $value) {
            if (in_array(strtolower($value), ['sim', '1', 'true'])) $values[$key] = 1;
            else if (in_array(strtolower($value), ['não', 'nao', '0', 'false'])) $values[$key] = 0;
            else $values[$key] = null;
        }

        $builder = ModelFilterHelpers::simple_operation($builder, 'impediment', '=', $values);
        return $builder;
    }
}
