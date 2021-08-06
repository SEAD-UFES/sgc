<?php

namespace App\CustomClasses;

use Illuminate\Http\Request;

class ModelFilterHelpers
{
    public static function buildFilters(Request $request, array $accepted_filters)
    {
        $filters = [];
        foreach ($accepted_filters as $accepted_filter) {
            if ($request->has($accepted_filter)) {
                $filters[$accepted_filter] = $request->get($accepted_filter);
            }
        }
        return $filters;
    }

    public static function inputToArray($value)
    {
        if (is_string($value)) $values = [$value];
        elseif (is_array($value)) $values = $value;
        return $values;
    }

    public static function contains($query_builder,  $column, $values)
    {
        foreach ($values as $value) {
            $query_builder = $query_builder->where($column, 'like', '%' . $value . '%');
        }
        return $query_builder;
    }

    public static function relation_contains($query_builder,  $relation, $column, $values)
    {
        foreach ($values as $value) {
            $query_builder = $query_builder->wherehas($relation, function ($query) use ($column, $value) {
                $query->where($column, 'like', '%' . $value . '%');
            });
        }
        return $query_builder;
    }
}
