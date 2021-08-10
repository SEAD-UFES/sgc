<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;

trait userFilter
{
    public function email_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'email', $values);
        return $builder;
    }

    public function usertype_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'userType', 'name', $values);
        return $builder;
    }

    public function active_exactly(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);

        foreach ($values as $key => $value) {
            if (in_array(strtolower($value), ['sim', '1', 'true'])) $values[$key] = 1;
            else if (in_array(strtolower($value), ['não', 'nao', '0', 'false'])) $values[$key] = 0;
            else $values[$key] = null;
        }

        $builder = ModelFilterHelpers::simple_operation($builder, 'active', '=', $values);
        return $builder;
    }

    public function employee_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'employee', 'name', $values);
        return $builder;
    }
}
