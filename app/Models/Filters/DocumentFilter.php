<?php

namespace App\Models\Filters;

use App\Helpers\ModelFilterHelper;
use App\Models\Bond;
use Illuminate\Database\Eloquent\Builder;

trait DocumentFilter
{
    /* public function employeeCpfContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'related', EmployeeDocument::class, 'employee', 'cpf', $values);
    }

    public function employeeNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'related', Bond::class, 'employee', 'name', $values);
    } */

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
        return ModelFilterHelper::morphRelationContains($builder, 'related', Bond::class, 'employee', 'name', $values);
    }

    public function bondRoleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'related', Bond::class, 'role', 'name', $values);
    }

    public function bondPoleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'related', Bond::class, 'poles', 'name', $values);
    }

    public function bondCourseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::morphRelationContains($builder, 'related', Bond::class, 'courses', 'name', $values);
    }
}
