<?php

namespace App\ModelFilters;

use App\Helpers\ModelFilterHelper;
use Illuminate\Database\Eloquent\Builder;

trait ApprovedFilter
{
    public function nameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'approveds.name', $values);
    }

    public function emailContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'email', $values);
    }

    public function areacodeContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'area_code', $values);
    }

    public function phoneContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'phone', $values);
    }

    public function mobileContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'mobile', $values);
    }

    public function announcementContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'announcement', $values);
    }

    public function approvedStateNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'approvedState', 'name', $values);
    }

    public function roleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'role', 'name', $values);
    }

    public function courseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'course', 'name', $values);
    }

    public function poleNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'pole', 'name', $values);
    }
}
