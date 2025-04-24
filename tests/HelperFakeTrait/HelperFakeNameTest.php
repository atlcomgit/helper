<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFakeTrait;

use Atlcom\Helper;
use Atlcom\Hlp;
use Atlcom\Enums\HelperFakeLocaleEnum as Locale;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFakeTrait
 */
final class HelperFakeNameTest extends TestCase
{
    #[Test]
    public function fakeName(): void
    {
        $string = Hlp::fakeName();
        $this->assertIsString($string);
        $this->assertNotEmpty($string);
    }


    #[Test]
    public function fakeNameRandomness(): void
    {
        $string1 = Helper::fakeName();
        $string2 = Helper::fakeName();
        $this->assertNotSame($string1, $string2, 'Strings should be different');
    }


    #[Test]
    public function fakeNameRussianLocale(): void
    {
        $string = Helper::fakeName(Locale::Russian);
        $this->assertIsString($string);
        $this->assertNotEmpty($string);
    }


    #[Test]
    public function fakeNameEnglishLocale(): void
    {
        $string = Helper::fakeName(Locale::English);
        $this->assertIsString($string);
        $this->assertNotEmpty($string);
    }


    #[Test]
    public function fakeNameOnlySurname(): void
    {
        $string = Helper::fakeName(Locale::Russian, true, false, false);
        $this->assertIsString($string);
        $this->assertNotEmpty($string);
        $this->assertCount(1, explode(' ', $string));
    }


    #[Test]
    public function fakeNameOnlyFirstName(): void
    {
        $string = Helper::fakeName(Locale::Russian, false, true, false);
        $this->assertIsString($string);
        $this->assertNotEmpty($string);
        $this->assertCount(1, explode(' ', $string));
    }


    #[Test]
    public function fakeNameOnlyPatronymic(): void
    {
        $string = Helper::fakeName(Locale::Russian, false, false, true);
        $this->assertIsString($string);
        $this->assertNotEmpty($string);
        $this->assertCount(1, explode(' ', $string));
    }


    #[Test]
    public function fakeNameFullRussian(): void
    {
        $string = Helper::fakeName(Locale::Russian, true, true, true);
        $this->assertIsString($string);
        $this->assertNotEmpty($string);
        $this->assertGreaterThanOrEqual(2, count(explode(' ', $string)));
    }


    #[Test]
    public function fakeNameFullEnglish(): void
    {
        $string = Helper::fakeName(Locale::English, true, true, false);
        $this->assertIsString($string);
        $this->assertNotEmpty($string);
        $this->assertGreaterThanOrEqual(2, count(explode(' ', $string)));
    }


    #[Test]
    public function fakeNameCustomSurnames(): void
    {
        $string = Helper::fakeName(Locale::English, ['Smith'], false, false);
        $this->assertSame('Smith', $string);
    }


    #[Test]
    public function fakeNameCustomFirstNames(): void
    {
        $string = Helper::fakeName(Locale::English, false, ['John'], false);
        $this->assertSame('John', $string);
    }


    #[Test]
    public function fakeNameCustomPatronymics(): void
    {
        $string = Helper::fakeName(Locale::Russian, false, false, ['Иванович']);
        $this->assertSame('Иванович', $string);
    }
}
