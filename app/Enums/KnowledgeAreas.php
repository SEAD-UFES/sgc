<?php

namespace App\Enums;

enum KnowledgeAreas: string
{
    case EXATAS = 'Ciências Exatas e da Terra';
    case BIOLOGICAS = 'Ciências Biológicas';
    case ENGENHARIAS = 'Engenharias';
    case SAUDE = 'Ciências da Saúde';
    case AGRARIAS = 'Ciências Agrárias';
    case SOCIAIS = 'Ciências Sociais Aplicadas';
    case HUMANAS = 'Ciências Humanas';
    case LINGUISTICA = 'Linguística, Letras e Artes';

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
