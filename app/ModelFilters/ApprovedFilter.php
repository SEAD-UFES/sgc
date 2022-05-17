<?php

namespace App\ModelFilters;

use App\CustomClasses\ModelFilterHelpers;
use Illuminate\Database\Eloquent\Builder;

trait ApprovedFilter
{
    public function nameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::contains($builder, 'approveds.name', $values);
    }

    public function emailContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::contains($builder, 'email', $values);
    }

    public function areacodeContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::contains($builder, 'area_code', $values);
    }

    public function phoneContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::contains($builder, 'phone', $values);
    }

    public function mobileContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::contains($builder, 'mobile', $values);
    }

    public function announcementContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::contains($builder, 'announcement', $values);
    }

    public function approvedStateNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'approvedState', 'name', $values);
    }

    public function roleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'role', 'name', $values);
    }

    public function courseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'course', 'name', $values);
    }

    public function poleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelpers::inputToArray($value);
        return ModelFilterHelpers::relationContains($builder, 'pole', 'name', $values);
    }
}
