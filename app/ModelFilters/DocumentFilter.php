<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;
use App\Models\EmployeeDocument;
use App\Models\BondDocument;

trait DocumentFilter
{
    public function employeeCpfContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morphRelationContains($builder, 'documentable', EmployeeDocument::class, 'employee', 'cpf', $values);
        return $builder;
    }

    public function employeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morphRelationContains($builder, 'documentable', EmployeeDocument::class, 'employee', 'name', $values);
        return $builder;
    }

    public function originalnameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'original_name', $values);
        return $builder;
    }

    public function documentTypeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relationContains($builder, 'documentType', 'name', $values);
        return $builder;
    }

    public function bondEmployeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morphRelationContains($builder, 'documentable', BondDocument::class, 'bond.employee', 'name', $values);
        return $builder;
    }

    public function bondRoleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morphRelationContains($builder, 'documentable', BondDocument::class, 'bond.role', 'name', $values);
        return $builder;
    }

    public function bondPoleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morphRelationContains($builder, 'documentable', BondDocument::class, 'bond.pole', 'name', $values);
        return $builder;
    }

    public function bondCourseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::morphRelationContains($builder, 'documentable', BondDocument::class, 'bond.course', 'name', $values);
        return $builder;
    }
}
