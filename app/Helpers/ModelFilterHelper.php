<?php

namespace App\Helpers;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ModelFilterHelper
{
    /**
     * @param Request $request
     * @param array<int, string> $acceptedFilters
     *
     * @return array<string, mixed>
     */
    public static function buildFilters(Request $request, array $acceptedFilters)
    {
        $filters = [];
        foreach ($acceptedFilters as $acceptedFilter) {
            if ($request->has($acceptedFilter)) {
                $filters[$acceptedFilter] = $request->get($acceptedFilter);
            }
        }

        return $filters;
    }

    /**
     * Undocumented function
     *
     * @param array<int, string>|string $value
     *
     * @return array<int, string>
     */
    public static function inputToArray(array|string $value): array
    {
        if (is_string($value)) {
            $values = [$value];
        } elseif (is_array($value)) {
            $values = $value;
        } else {
            throw new \InvalidArgumentException('Invalid type for inputToArray($value)');
        }

        return $values;
    }

    /**
     * @param string $date
     * @param string $format
     *
     * @return bool
     */
    public static function validateDate(string $date, string $format = 'Y-m-d'): bool
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }

    /**
     * @param array<int, string> $values
     *
     * @return array<int, string>
     */
    public static function convertDateFormat($values)
    {
        foreach ($values as $key => $value) {
            if (ModelFilterHelper::validateDate($value, 'd/m/Y')) {
                /** @var DateTime $dt */
                $dt = DateTime::createFromFormat('d/m/Y', $value);
                $values[$key] = $dt->format('Y-m-d');
            }
        }

        return $values;
    }

    /**
     * Undocumented function
     *
     * @param Builder<Model> $queryBuilder
     * @param string $column
     * @param string $operation
     * @param array<int, string> $values
     *
     * @return Builder<Model>
     */
    public static function simpleOperation(Builder $queryBuilder, string $column, string $operation, array $values): Builder
    {
        foreach ($values as $value) {
            $queryBuilder = $queryBuilder->where($column, $operation, $value);
        }

        return $queryBuilder;
    }

    /**
     * Undocumented function
     *
     * @param Builder<Model> $queryBuilder
     * @param string $column
     * @param array<int, string> $values
     *
     * @return Builder<Model>
     */
    public static function contains(Builder $queryBuilder, string $column, array $values): Builder
    {
        foreach ($values as $value) {
            $queryBuilder = $queryBuilder->where($column, 'like', '%' . $value . '%');
        }

        return $queryBuilder;
    }

    /**
     * Undocumented function
     *
     * @param Builder<Model> $queryBuilder
     * @param string $relation
     * @param string $column
     * @param array<int, string> $values
     *
     * @return Builder<Model>
     */
    public static function relationContains(Builder $queryBuilder, string $relation, string $column, array $values): Builder
    {
        foreach ($values as $value) {
            $queryBuilder = $queryBuilder->wherehas($relation, static function ($query) use ($column, $value) {
                $query->where($column, 'like', '%' . $value . '%');
            });
        }

        return $queryBuilder;
    }

    /**
     * Undocumented function
     *
     * @param Builder<Model> $queryBuilder
     * @param string $relation
     * @param string $morphClass
     * @param string $childRelation
     * @param string $column
     * @param array<int, string> $values
     *
     * @return Builder<Model>
     */
    public static function morphRelationContains(Builder $queryBuilder, string $relation, string $morphClass, string $childRelation, string $column, array $values): Builder
    {
        foreach ($values as $value) {
            $queryBuilder = $queryBuilder
                ->whereHasMorph($relation, $morphClass, static function ($query) use ($childRelation, $column, $value) {
                    $query->whereHas($childRelation, static function ($query) use ($column, $value) {
                        $query->where($column, 'like', '%' . $value . '%');
                    });
                });
        }

        return $queryBuilder;
    }

    /**
     * Undocumented function
     *
     * @param Builder<Model> $queryBuilder
     * @param string $relation
     * @param string $column
     * @param string $operation
     * @param array<int, string> $values
     *
     * @return Builder<Model>
     */
    public static function relationSimpleOperation(Builder $queryBuilder, string $relation, string $column, string $operation, array $values): Builder
    {
        foreach ($values as $value) {
            $queryBuilder = $queryBuilder->wherehas($relation, static function ($query) use ($column, $operation, $value) {
                $query->where($column, $operation, $value);
            });
        }

        return $queryBuilder;
    }

    /**
     * Undocumented function
     *
     * @param Builder<Model> $queryBuilder
     * @param string $enumClass
     * @param string $column
     * @param array<int, string> $values
     *
     * @return Builder<Model>
     */
    public static function enumLabelsContains(Builder $queryBuilder, string $enumClass, string $column, array $values): Builder
    {
        $arrayedEnum = [];

        foreach ($enumClass::cases() as $enum) {
            $arrayedEnum[$enum->name] = $enum->label();
        }

        $matchingEnumNames = [];

        foreach ($arrayedEnum as $name => $label) {
            $foundOnLabel = true;
            foreach ($values as $value) {
                if (stripos(TextHelper::removeAccents($label), TextHelper::removeAccents($value)) === false) {
                    $foundOnLabel = false;
                }
            }

            if ($foundOnLabel) {
                $matchingEnumNames[] = $name;
            }
        }

        $matchingEnumNames = array_unique($matchingEnumNames);

        return $queryBuilder->whereIn($column, $matchingEnumNames);
    }
}
