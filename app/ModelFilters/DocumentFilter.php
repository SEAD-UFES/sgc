<?php

namespace App\ModelFilters;

use App\Helpers\ModelFilterHelper;
use App\Models\BondDocument;
use App\Models\EmployeeDocument;
use Illuminate\Database\Eloquent\Builder;

trait DocumentFilter
{
    public function employeeCpfContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'documentable', EmployeeDocument::class, 'employee', 'cpf', $values);
    }

    public function employeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'documentable', EmployeeDocument::class, 'employee', 'name', $values);
    }

    public function originalnameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'file_name', $values);
    }

    public function documentTypeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'documentType', 'name', $values);
    }

    public function bondEmployeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'documentable', BondDocument::class, 'bond.employee', 'name', $values);
    }

    public function bondRoleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'documentable', BondDocument::class, 'bond.role', 'name', $values);
    }

    public function bondPoleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'documentable', BondDocument::class, 'bond.pole', 'name', $values);
    }

    public function bondCourseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'documentable', BondDocument::class, 'bond.course', 'name', $values);
    }
}
