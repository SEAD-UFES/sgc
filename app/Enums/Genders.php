<?php

namespace App\Enums;

enum Genders: string
{
    case F = 'Feminino';
    case M = 'Masculino';

    /**
     * @return array<int, string>
     */
    public static function getValuesInAlphabeticalOrder(): array
    {
        /**
         * @var array<int, string>
         */
        $arr = [];

        foreach (self::cases() as $case) {
            $arr[] = $case->value;
        }

        sort($arr, SORT_NATURAL | SORT_FLAG_CASE);

        return $arr;
    }
}
