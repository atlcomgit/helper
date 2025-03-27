<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperStringTrait;

use Atlcom\Enums\HelperRegexpEnum;
use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringRandomTest extends TestCase
{
    #[Test]
    public function stringRandom(): void
    {
        $string = Helper::stringRandom($pattern = '/[a-z]{10}/');
        $this->assertTrue(Helper::stringLength($string) === 10);
        $this->assertMatchesRegularExpression($pattern, $string);

        $string = Helper::stringRandom($pattern = '/[A-Z]{10}/');
        $this->assertTrue(Helper::stringLength($string) === 10);
        $this->assertMatchesRegularExpression($pattern, $string);

        $string = Helper::stringRandom($pattern = '/[А-ЯЁ]{10}/u');
        $this->assertMatchesRegularExpression($pattern, $string);

        $string = Helper::stringRandom($pattern = '/[а-яё]{10}/u');
        $this->assertMatchesRegularExpression($pattern, $string);

        $string = Helper::stringRandom($pattern = '/[0-9]{10}/');
        $this->assertMatchesRegularExpression($pattern, $string);

        $string = Helper::stringRandom($pattern = HelperRegexpEnum::Email->value);
        $this->assertMatchesRegularExpression($pattern, $string);

        //?!? 
        $string = Helper::stringRandom($pattern = HelperRegexpEnum::Pattern->value);
        $this->assertMatchesRegularExpression($pattern, $string);

        $string = Helper::stringRandom($pattern = HelperRegexpEnum::Phone->value);
        // $this->assertEquals($pattern, $string);
        $this->assertMatchesRegularExpression($pattern, $string);

        $string = Helper::stringRandom($pattern = HelperRegexpEnum::Uuid->value);
        $this->assertMatchesRegularExpression($pattern, $string);
    }
}
