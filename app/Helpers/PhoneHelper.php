<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class PhoneHelper
{
    /**
     * @param string $phone
     *
     * @return array<string, string>
     */
    public static function analysePhone(string $phone): array
    {
        $mobileN8 = ['9', '8', '7', '6'];
        $landlineN8 = ['2', '3', '4', '5'];

        if ((Str::length($phone) === 11) && in_array(substr($phone, 2, 1), $mobileN8)) {
            return [
                'number' => substr($phone, 2),
                'areaCode' => substr($phone, 0, 2),
                'type' => 'mobile',
            ];
        }
        if ((Str::length($phone) === 9) && in_array(substr($phone, 0, 1), $mobileN8)) {
            return [
                'number' => $phone,
                'areaCode' => '',
                'type' => 'mobile',
            ];
        }
        if ((Str::length($phone) === 10) && in_array(substr($phone, 2, 1), $landlineN8)) {
            return [
                'number' => substr($phone, 2),
                'areaCode' => substr($phone, 0, 2),
                'type' => 'landline',
            ];
        }
        if ((Str::length($phone) === 8) && in_array(substr($phone, 0, 1), $landlineN8)) {
            return [
                'number' => $phone,
                'areaCode' => '',
                'type' => 'landline',
            ];
        }
        return [
            'number' => $phone,
            'areaCode' => '',
            'type' => 'unknown',
        ];
    }

    /**
     * @param string $phone
     * @param ?string $areaCode
     *
     * @return string
     */
    public static function ensureAreaCode(string $phone, ?string $areaCode = '27'): string
    {
        /**
         * @var array<string, string> $analyse
         */
        $analyse = self::analysePhone($phone);

        if ($analyse['areaCode'] === '') {
            $phone = $areaCode . $phone;
        }

        return $phone;
    }

    /**
     * @param string $primaryNumber
     * @param string $secondaryNumber
     * @param ?string $defaultAreaCode
     *
     * @return string
     */
    public static function firstAreaCode(string $primaryNumber, string $secondaryNumber, ?string $defaultAreaCode = '27'): string
    {
        /**
         * @var array<string, string> $primaryAnalyse
         */
        $primaryAnalyse = self::analysePhone($primaryNumber);

        /**
         * @var array<string, string> $secondaryAnalyse
         */
        $secondaryAnalyse = self::analysePhone($secondaryNumber);

        if ($primaryAnalyse['areaCode'] !== '') {
            return $primaryAnalyse['areaCode'];
        }

        if ($secondaryAnalyse['areaCode'] !== '') {
            return $secondaryAnalyse['areaCode'];
        }

        return $defaultAreaCode;
    }
}
