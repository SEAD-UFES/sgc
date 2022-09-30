<?php

namespace App\Helpers;

class DocumentRepositoryHelper
{
    /**
     * Undocumented function
     *
     * @param string $documentClass
     * @param string $sort
     *
     * @return string
     */
    public static function validateSort(string $documentClass, string $sort = ''): string
    {
        /**
         * var array<int, string> $sortable
         */
        $sortable = $documentClass::$sortable;

        if (! in_array($sort, $sortable)) {
            $sort = 'documents.id';
        }

        return $sort;
    }

    /**
     * Undocumented function
     *
     * @param string $direction
     *
     * @return string
     */
    public static function validateDirection(string $direction = ''): string
    {
        /**
         * var array<int, string> $directions
         */
        $directions = ['asc', 'desc'];

        if (! in_array($direction, $directions)) {
            $direction = 'desc';
        }

        return $direction;
    }
}
