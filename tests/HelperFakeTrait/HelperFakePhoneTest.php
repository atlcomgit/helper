<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFakeTrait;

use Atlcom\Enums\HelperRegexpEnum;
use Atlcom\Helper;
use Atlcom\Hlp;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFakeTrait
 */
final class HelperFakePhoneTest extends TestCase
{
    #[Test]
    public function fakePhone(): void
    {
        $url = Hlp::fakePhone();
        $this->assertIsString($url);

        $string = Helper::fakePhone();
        $this->assertMatchesRegularExpression(HelperRegexpEnum::Phone->value, $string);

        $string = Helper::fakePhone();
        $this->assertMatchesRegularExpression('/^79[\d]{9}$/', $string);

        $string = Helper::fakePhone('+7');
        $this->assertMatchesRegularExpression('/^\+79\d{9}$/', $string);

        $string = Helper::fakePhone('8');
        $this->assertMatchesRegularExpression('/^89\d{9}$/', $string);

        $string = Helper::fakePhone('');
        $this->assertMatchesRegularExpression('/^9\d{9}$/', $string);

        $string = Helper::fakePhone(null);
        $this->assertMatchesRegularExpression('/^9\d{9}$/', $string);

        $string1 = Helper::fakePhone();
        $string2 = Helper::fakePhone();
        $this->assertNotSame($string1, $string2, 'Strings should be different');
    }
}
