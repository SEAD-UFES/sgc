<?php

namespace Tests\Unit;

use App\Helpers\TextHelper;
use PHPUnit\Framework\TestCase;

final class TextHelperTest extends TestCase
{
    /**
     * Test titleCase helper function works
     *
     * @return void
     */
    public function testTitleCaseHelper(): void
    {
        $this->assertEquals(TextHelper::titleCase('king henry viii'), 'King Henry VIII');
        $this->assertEquals(TextHelper::titleCase('dom joão vi'), 'Dom João VI');
        $this->assertEquals(TextHelper::titleCase('dona maria ii'), 'Dona Maria II');
    }

    /**
     * Test removeAccent helper function can deal
     * with Portuguese characters
     *
     * @return void
     */
    public function testRemoveAccentHelper(): void
    {
        $this->assertEquals(
            TextHelper::removeAccents('Açalpão, Caçapa, Época, Açaí'),
            'Acalpao, Cacapa, Epoca, Acai'
        );

        $this->assertEquals(TextHelper::removeAccents('áéíóúÁÉÍÓÚ'), 'aeiouAEIOU');
        $this->assertEquals(TextHelper::removeAccents('àãÀÃçÇ'), 'aaAAcC');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testShouldRemoveNonDigits(): void
    {
        $landline = '(0d27) j223s4 5678';
        $mobile = '(028) 96s234 5j67d8';

        $expectedLandline = '02722345678';
        $expectedMobile = '028962345678';

        $fixedLandline = TextHelper::removeNonDigits($landline);
        $fixedMobile = TextHelper::removeNonDigits($mobile);

        $this->assertEquals($expectedLandline, $fixedLandline);
        $this->assertEquals($expectedMobile, $fixedMobile);
    }
}
