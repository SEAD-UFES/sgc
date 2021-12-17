<?php

namespace App\CustomClasses;

use Illuminate\Http\Request;
use DateTime;

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
        if (is_string($value)) {
            $values = [$value];
        } elseif (is_array($value)) {
            $values = $value;
        }
        return $values;
    }


    public static function validateDate($date, $form = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($form, $date);
        return $d && $d->format($form) === $date;
    }

    public static function convertDateFormat($values)
    {
        foreach ($values as $key => $value) {
            if (ModelFilterHelpers::validateDate($value, 'd/m/Y')) {
                $dt = DateTime::createFromFormat('d/m/Y', $value);
                $values[$key] = $dt->format('Y-m-d');
            }
        }
        return $values;
    }

    public static function simpleOperation($query_builder, $column, $operation, $values)
    {
        foreach ($values as $value) {
            $query_builder = $query_builder->where($column, $operation, $value);
        }
        return $query_builder;
    }

    public static function contains($query_builder, $column, $values)
    {
        foreach ($values as $value) {
            $query_builder = $query_builder->where($column, 'like', '%' . $value . '%');
        }
        return $query_builder;
    }

    public static function relationContains($query_builder, $relation, $column, $values)
    {
        foreach ($values as $value) {
            $query_builder = $query_builder->wherehas($relation, function ($query) use ($column, $value) {
                $query->where($column, 'like', '%' . $value . '%');
            });
        }
        return $query_builder;
    }

    public static function morphRelationContains($query_builder, $relation, $morphClass, $childRelation, $column, $values)
    {
        foreach ($values as $value) {
            $query_builder = $query_builder
                ->whereHasMorph($relation, $morphClass, function ($query) use ($childRelation, $column, $value) {
                    $query->whereHas($childRelation, function ($query) use ($column, $value) {
                        $query->where($column, 'like', '%' . $value . '%');
                    });
                });
        }
        return $query_builder;
    }

    public static function relationSimpleOperation($query_builder, $relation, $column, $operation, $values)
    {
        foreach ($values as $value) {
            $query_builder = $query_builder->wherehas($relation, function ($query) use ($column, $operation, $value) {
                $query->where($column, $operation, $value);
            });
        }
        return $query_builder;
    }
}
