<?php

namespace App\Enums;

enum MaritalStatuses: string
{
    case SOLTEIRO = 'Solteiro(a)';
    case CASADO = 'Casado(a)';
    case SEPARADO = 'Separado(a)';
    case DIVORCIADO = 'Divorciado(a)';
    case VIUVO = 'Viúvo(a)';
    case UNIAO = 'União Estável';

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
