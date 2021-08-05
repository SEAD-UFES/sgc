<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;

trait bondDocumentFilter
{
    public function originalname_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'original_name', $values);
        return $builder;
    }

    public function documentType_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'documentType', 'name', $values);
        return $builder;
    }

    public function bond_employee_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'bond.employee', 'name', $values);
        return $builder;
    }

    public function bond_role_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'bond.role', 'name', $values);
        return $builder;
    }

    public function bond_course_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'bond.course', 'name', $values);
        return $builder;
    }
}
