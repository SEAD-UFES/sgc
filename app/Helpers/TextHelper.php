<?php

namespace App\Helpers;

class TextHelper
{
    /**
     * returns text correctly cased
     *
     * @param string $string
     * @param array<int, string> $delimiters
     * @param array<int, string> $exceptions
     *
     * @return string
     */
    public static function titleCase(
        string $string = '',
        array $delimiters = [' '/* , "-", ".", "'", "O'", "Mc" */],
        array $exceptions = ['da', 'de', 'do', 'das', 'dos', /* "út", "u", "s", "és", "utca", "tér", "krt", "körút", "sétány", */ 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII', 'XVIII', 'XIX', 'XX', 'XXI', 'XXII', 'XXIII', 'XXIV', 'XXV', 'XXVI', 'XXVII', 'XXVIII', 'XXIX', 'XXX', '1st', '2nd', '3rd', '4th', '5th', '6th', '7th', '8th', '9th', '10th']
    ): string {
        /*
        * Exceptions in lower case are words you don't want converted
        * Exceptions all in upper case are any words you don't want converted to title case
        * but should be converted to upper case, e.g.:
        * ing henry viii or king henry Viii should be King Henry VIII
        */
        $string = mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');

        foreach ($delimiters as $delimiter) {
            if ($delimiter === '') {
                continue;
            }

            $words = explode($delimiter, $string);
            $newwords = [];
            foreach ($words as $word) {
                if (in_array(mb_strtoupper($word, 'UTF-8'), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtoupper($word, 'UTF-8');
                } elseif (in_array(mb_strtolower($word, 'UTF-8'), $exceptions)) {
                    // check exceptions list for any words that should be in upper case
                    $word = mb_strtolower($word, 'UTF-8');
                } elseif (! in_array($word, $exceptions)) {
                    // convert to uppercase (non-utf8 only)
                    $word = ucfirst($word);
                }

                $newwords[] = $word;
            }

            $string = implode($delimiter, $newwords);
        }

        return $string;
    }

    /*
     * replaces characters with plain latin versions
     *
     * @param string $str
     *
     * @return string
     */
    public static function removeAccents(string $str): string
    {
        $newStr = transliterator_transliterate('NFKC; [:Nonspacing Mark:] Remove; NFKC; Any-Latin; Latin-ASCII', $str);

        return $newStr !== false ? $newStr : $str;
    }

    /**
     * @param string $str
     *
     * @return string
     */
    public static function removeNonDigits($str)
    {
        $newStr = preg_replace('/\D/', '', $str);

        if ($newStr === null) {
            throw new \Exception('Error removing non digits from string: ' . $str);
        }

        return $newStr;
    }
}
