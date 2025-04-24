<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFakeTrait;

use Atlcom\Enums\HelperRegexpEnum;
use Atlcom\Helper;
use Atlcom\Hlp;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFakeTrait
 */
final class HelperFakeUuid7Test extends TestCase
{
    #[Test]
    public function fakeUuid7(): void
    {
        $url = Hlp::fakeUuid7();
        $this->assertIsString($url);

        $string = Helper::fakeUuid7();
        $this->assertMatchesRegularExpression(HelperRegexpEnum::Uuid7->value, $string);

        $string1 = Helper::fakeUuid7();
        $string2 = Helper::fakeUuid7();
        $this->assertNotSame($string1, $string2, 'Strings should be different');
    }
}
