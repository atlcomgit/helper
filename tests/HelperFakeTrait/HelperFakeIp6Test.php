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
final class HelperFakeIp6Test extends TestCase
{
    #[Test]
    public function fakeIp6(): void
    {
        $ip = Hlp::fakeIp6();
        $this->assertIsString($ip);

        $string = Helper::fakeIp6();
        $this->assertMatchesRegularExpression(HelperRegexpEnum::Ip6->value, $string);

        $string1 = Helper::fakeIp6();
        $string2 = Helper::fakeIp6();
        $this->assertNotSame($string1, $string2, 'Strings should be different');
    }
}
