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
final class HelperRegexpValidatePhoneTest extends TestCase
{
    #[Test]
    public function regexpValidatePhone(): void
    {
        $boolean = Helper::regexpValidatePhone('+79001234567');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidatePhone('89001234567');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidatePhone('9001234567');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidatePhone('+7 (900) 123-45-67');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidatePhone('(900) 123-45-6');
        $this->assertFalse($boolean);

        $boolean = Helper::regexpValidatePhone('+7 (900) 123-45-6a');
        $this->assertFalse($boolean);

        $boolean = Helper::regexpValidatePhone(null);
        $this->assertTrue($boolean === false);
    }
}
