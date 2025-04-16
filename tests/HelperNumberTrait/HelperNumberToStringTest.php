<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperNumberTrait;

use Atlcom\Enums\HelperNumberDeclensionEnum;
use Atlcom\Enums\HelperNumberEnumerationEnum;
use Atlcom\Enums\HelperNumberGenderEnum;
use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperNumberTrait
 */
final class HelperNumberToStringTest extends TestCase
{
    #[Test]
    public function numberToString(): void
    {
        $string = Helper::numberToString(123.456);
        $this->assertEquals('сто двадцать три целых четыреста пятьдесят шесть тысячных', $string, );

        $string = Helper::numberToString(
            1234567890,
            HelperNumberDeclensionEnum::Nominative,
            HelperNumberGenderEnum::Male,
            HelperNumberEnumerationEnum::Numerical,
        );
        $this->assertEquals(
            'один миллиард двести тридцать четыре миллиона пятьсот шестьдесят семь тысяч восемьсот девяносто',
            $string,
        );

        $string = Helper::numberToString(
            -1,
            HelperNumberDeclensionEnum::Nominative,
            HelperNumberGenderEnum::Male,
            HelperNumberEnumerationEnum::Numerical,
        );
        $this->assertEquals('минус один', $string);

        $string = Helper::numberToString(
            123,
            HelperNumberDeclensionEnum::Genitive,
            HelperNumberGenderEnum::Female,
            HelperNumberEnumerationEnum::Ordinal,
        );
        $this->assertEquals('сто двадцать третьей', $string);

        $string = Helper::numberToString(
            '+-13579',
            HelperNumberDeclensionEnum::Prepositional,
            HelperNumberGenderEnum::Neuter,
            HelperNumberEnumerationEnum::Numerical,
        );
        $this->assertEquals('плюс минус тринадцати тысяч пятиста семидести девяти', $string);

        $string = Helper::numberToString(null);
        $this->assertEquals('', $string);
    }
}
