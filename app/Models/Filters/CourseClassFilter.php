<?php

namespace App\Models\Filters;

use App\Helpers\ModelFilterHelper;
use Illuminate\Database\Eloquent\Builder;

trait CourseClassFilter
{
    public function nameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'name', $values);
    }

    public function courseNameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::relationContains($builder, 'course', 'name', $values);
    }

    public function codeContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'code', $values);
    }

    public function cppContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'cpp', $values);
    }
}
