<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperRegexpTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperRegexpTrait
 */
final class HelperRegexpValidateIp6Test extends TestCase
{
    #[Test]
    public function regexpValidateIp6(): void
    {
        $boolean = Helper::regexpValidateIp6('');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateIp6('2001:0db8:0058:0074:1c60:2c18:1025:2b5f');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateIp6('0.0.0.0');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateIp6(null);
        $this->assertTrue($boolean === false);
    }
}
