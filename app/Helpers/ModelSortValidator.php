<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

class ModelSortValidator
{
    /**
     * Undocumented function
     *
     * @param ?string $direction
     *
     * @return string
     */
    public static function validateDirection(?string $direction): string
    {
        /**
         * var array<int, string> $directions
         */
        $directions = ['asc', 'desc'];

        if (! is_null($direction) && in_array($direction, $directions)) {
            return $direction;
        }

        return 'desc';
    }

    /**
     * Undocumented function
     *
     * @param string $class
     * @param ?string $sort
     *
     * @return string
     */
    public static function validateSort(string $class, ?string $sort): string
    {
        /**
         * var array<int, string> $sortable
         */
        $sortable = $class::$sortable;

        if (! is_null($sort) && in_array($sort, $sortable)) {
            return $sort;
        }

        /**
         * @var Model $auxModel
         */
        $auxModel = new $class();

        return $auxModel->getTable() . '.' . $auxModel->getKeyName();
    }
}
