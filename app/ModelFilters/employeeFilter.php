<?php

namespace App\ModelFilters;

use Illuminate\Database\Eloquent\Builder;
use App\CustomClasses\ModelFilterHelpers;
use App\Models\ApprovedState;

trait employeeFilter
{
    public function cpf_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'cpf', $values);
        return $builder;
    }

    public function name_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'name', $values);
        return $builder;
    }

    public function job_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'job', $values);
        return $builder;
    }

    public function addresscity_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::contains($builder, 'address_city', $values);
        return $builder;
    }

    public function user_email_contains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        $builder = ModelFilterHelpers::relation_contains($builder, 'user', 'email', $values);
        return $builder;
    }
}
