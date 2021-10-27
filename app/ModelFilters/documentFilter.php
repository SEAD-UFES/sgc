<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;
use App\Models\EmployeeDocument;
use App\Models\BondDocument;

trait documentFilter
{
    public function employee_cpf_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morph_relation_contains($builder, 'documentable', EmployeeDocument::class, 'employee', 'cpf', $values);
        return $builder;
    }

    public function employee_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morph_relation_contains($builder, 'documentable', EmployeeDocument::class, 'employee', 'name', $values);
        return $builder;
    }

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
        $builder = ModelFilterHelpers::morph_relation_contains($builder, 'documentable', BondDocument::class, 'bond.employee', 'name', $values);
        return $builder;
    }

    public function bond_role_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morph_relation_contains($builder, 'documentable', BondDocument::class, 'bond.role', 'name', $values);
        return $builder;
    }

    public function bond_pole_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morph_relation_contains($builder, 'documentable', BondDocument::class, 'bond.pole', 'name', $values);
        return $builder;
    }

    public function bond_course_name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morph_relation_contains($builder, 'documentable', BondDocument::class, 'bond.course', 'name', $values);
        return $builder;
    }
}
