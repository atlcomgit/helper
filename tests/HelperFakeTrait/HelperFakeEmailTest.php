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
final class HelperFakeEmailTest extends TestCase
{
    #[Test]
    public function fakeEmail(): void
    {
        $url = Hlp::fakeUrl();
        $this->assertIsString($url);

        $string = Helper::fakeEmail();
        $this->assertMatchesRegularExpression(HelperRegexpEnum::Email->value, $string);

        $string = Helper::fakeEmail();
        $this->assertMatchesRegularExpression('/^[a-z0-9]{5,10}@[a-z]{5,10}\.(com|net|org|io|ru)$/', $string);

        $string1 = Helper::fakeEmail();
        $string2 = Helper::fakeEmail();
        $this->assertNotSame($string1, $string2, 'Strings should be different');
    }
}
