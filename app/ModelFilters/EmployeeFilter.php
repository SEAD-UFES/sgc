<?php

namespace App\ModelFilters;

use App\Helpers\ModelFilterHelper;
use Illuminate\Database\Eloquent\Builder;

trait EmployeeFilter
{
    public function cpfContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'cpf', $values);
    }

    public function nameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'name', $values);
    }

    public function jobContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'job', $values);
    }

    public function addresscityContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'address_city', $values);
    }

    public function userEmailContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'user', 'email', $values);
    }
}
