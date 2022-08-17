<?php

namespace Tests\Unit;

use App\Helpers\Phone;
use PHPUnit\Framework\TestCase;

class PhoneVoTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPhoneShouldBeCreated(): void
    {
        //$landlineN8 = ['2', '3', '4', '5'];
        //$mobileN8 = ['9', '8', '7', '6'];

        $phone['landline'][1]['string'] = '2722345678';
        $phone['landline'][1]['areaCode'] = '27';
        $phone['landline'][1]['number'] = '22345678';
        $phone['landline'][1]['type'] = 'landline';

        $phone['landline'][2]['string'] = '2852345678';
        $phone['landline'][2]['areaCode'] = '28';
        $phone['landline'][2]['number'] = '52345678';
        $phone['landline'][2]['type'] = 'landline';

        $phone['mobile'][1]['string'] = '27962345678';
        $phone['mobile'][1]['areaCode'] = '27';
        $phone['mobile'][1]['number'] = '962345678';
        $phone['mobile'][1]['type'] = 'mobile';

        $phone['mobile'][2]['string'] = '28992345678';
        $phone['mobile'][2]['areaCode'] = '28';
        $phone['mobile'][2]['number'] = '992345678';
        $phone['mobile'][2]['type'] = 'mobile';

        $landline1 = new Phone($phone['landline'][1]['string']);
        $landline2 = new Phone($phone['landline'][2]['string']);
        $mobile1 = new Phone($phone['mobile'][1]['string']);
        $mobile2 = new Phone($phone['mobile'][2]['string']);

        $this->assertEquals($phone['landline'][1]['areaCode'], $landline1->getAreaCode());
        $this->assertEquals($phone['landline'][1]['number'], $landline1->getNumber());
        $this->assertEquals($phone['landline'][1]['type'], $landline1->getType());
        $this->assertEquals($phone['landline'][2]['areaCode'], $landline2->getAreaCode());
        $this->assertEquals($phone['landline'][2]['number'], $landline2->getNumber());
        $this->assertEquals($phone['landline'][2]['type'], $landline2->getType());
        $this->assertEquals($phone['mobile'][1]['areaCode'], $mobile1->getAreaCode());
        $this->assertEquals($phone['mobile'][1]['number'], $mobile1->getNumber());
        $this->assertEquals($phone['mobile'][1]['type'], $mobile1->getType());
        $this->assertEquals($phone['mobile'][2]['areaCode'], $mobile2->getAreaCode());
        $this->assertEquals($phone['mobile'][2]['number'], $mobile2->getNumber());
        $this->assertEquals($phone['mobile'][2]['type'], $mobile2->getType());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPhoneShouldBeCreatedUnknown(): void
    {
        $invalidPhones = [
            '',
            '1234567',
            '12345678',
            '2712345678',
            '62345678',
            '2762345678',
            '912345678',
            '27912345678',
            '952345678',
            '27952345678',
        ];

        foreach ($invalidPhones as $phone) {
            $dto = new Phone($phone);
            $this->assertEquals('', $dto->getAreaCode());
            $this->assertEquals($phone, $dto->getNumber());
            $this->assertEquals('unknown', $dto->getType());
        }
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPhoneShouldBeLandline(): void
    {
        $landlinePhones = [
            '2722345678',
            '2852345678',
        ];

        foreach ($landlinePhones as $phone) {
            $dto = new Phone($phone);
            $this->assertTrue($dto->isLandline());
        }
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPhoneShouldBeMobile(): void
    {
        $mobilePhones = [
            '27962345678',
            '28992345678',
        ];

        foreach ($mobilePhones as $phone) {
            $dto = new Phone($phone);
            $this->assertTrue($dto->isMobile());
        }
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testPhoneShouldBeUnknown(): void
    {
        $invalidPhones = [
            '',
            '1234567',
            '12345678',
            '2712345678',
            '62345678',
            '2762345678',
            '912345678',
            '27912345678',
            '952345678',
            '27952345678',
        ];

        foreach ($invalidPhones as $phone) {
            $dto = new Phone($phone);
            $this->assertTrue($dto->isUnknown());
        }
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testShouldGetDefaultAreaCode(): void
    {
        $phone = new Phone('2822345678');
        $this->assertEquals('27', $phone->getDefaultAreaCode());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testShouldGetDefaultAreaCodeOnUnknown(): void
    {
        $phone = new Phone('');
        $this->assertEquals('27', $phone->getDefaultAreaCode());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testShouldOverwriteAreaCode(): void
    {
        $landlineWithoutAreaCode = new Phone('22345678');
        $mobileWithoutAreaCode = new Phone('962345678');

        $landlineWithoutAreaCode->overwriteEmptyAreaCodeWithDefault();
        $mobileWithoutAreaCode->overwriteEmptyAreaCodeWithDefault();

        $this->assertEquals('27', $landlineWithoutAreaCode->getAreaCode());
        $this->assertEquals('27', $mobileWithoutAreaCode->getAreaCode());
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testShouldRemoveAreaCodeZeroes(): void
    {
        $landline = new Phone('02722345678');
        $mobile = new Phone('028962345678');

        $this->assertEquals('27', $landline->getAreaCode());
        $this->assertEquals('22345678', $landline->getNumber());
        $this->assertEquals('landline', $landline->getType());
        $this->assertEquals('28', $mobile->getAreaCode());
        $this->assertEquals('962345678', $mobile->getNumber());
        $this->assertEquals('mobile', $mobile->getType());
    }
}
