<?php

namespace App\ModelFilters;

use App\Helpers\ModelFilterHelper;
use Illuminate\Database\Eloquent\Builder;

trait PoleFilter
{
    public function nameContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'name', $values);
    }

    public function descriptionContains(Builder $builder, $value)
    {
        $values = ModelFilterHelper::inputToArray($value);
        return ModelFilterHelper::contains($builder, 'description', $values);
    }
}
