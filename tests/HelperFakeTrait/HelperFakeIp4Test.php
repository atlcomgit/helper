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
final class HelperFakeIp4Test extends TestCase
{
    #[Test]
    public function fakeIp4(): void
    {
        $ip = Hlp::fakeIp4();
        $this->assertIsString($ip);

        $string = Helper::fakeIp4();
        $this->assertMatchesRegularExpression(HelperRegexpEnum::Ip4->value, $string);

        $string1 = Helper::fakeIp4();
        $string2 = Helper::fakeIp4();
        $this->assertNotSame($string1, $string2, 'Strings should be different');
    }
}
