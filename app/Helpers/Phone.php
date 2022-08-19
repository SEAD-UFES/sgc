<?php

namespace App\Helpers;

class Phone
{
    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $areaCode;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $defaultAreaCode = '27';

    /**
     * @param string $phone
     */
    public function __construct(string $phone)
    {
        $phone = self::removeAreaCodeZero($phone);

        //landline N8 => '2', '3', '4', '5'
        //mobile N8 => '9', '8', '7', '6'

        $landlineRe = '/^([1-9]{1}[1-9]{1})?([2-5]\d{7})$/';
        $mobileRe = '/^([1-9]{1}[1-9]{1})?(9[6-9]\d{7})$/';

        preg_match($landlineRe, $phone, $landlineMatch);
        preg_match($mobileRe, $phone, $mobileMatch);

        if (! empty($landlineMatch)) {
            $this->number = $landlineMatch[2];
            $this->areaCode = $landlineMatch[1];
            $this->type = 'landline';
        } elseif (! empty($mobileMatch)) {
            $this->number = $mobileMatch[2];
            $this->areaCode = $mobileMatch[1];
            $this->type = 'mobile';
        } else {
            $this->number = $phone;
            $this->areaCode = '';
            $this->type = 'unknown';
        }
    }

    /**
     * Get the value of number
     *
     * @return  string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * Get the value of areaCode
     *
     * @return string
     */
    public function getAreaCode(): string
    {
        return $this->areaCode;
    }

    /**
     * Get the value of type
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isLandline(): bool
    {
        return $this->type === 'landline';
    }

    /**
     * @return bool
     */
    public function isMobile(): bool
    {
        return $this->type === 'mobile';
    }

    /**
     * @return bool
     */
    public function isUnknown(): bool
    {
        return $this->type === 'unknown';
    }

    /**
     * Get the value of defaultAreaCode
     *
     * @return string
     */
    public function getDefaultAreaCode(): string
    {
        return $this->defaultAreaCode;
    }

    public function overwriteEmptyAreaCodeWithDefault(): void
    {
        if ($this->areaCode === '') {
            $this->areaCode = $this->defaultAreaCode;
        }
    }

    /**
     * @param string $number
     *
     * @return string
     */
    private function removeAreaCodeZero(string $number): string
    {
        return ltrim($number, '0');
    }
}
