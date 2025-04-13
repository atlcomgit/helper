<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperPhoneTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperPhoneTrait
 */
final class HelperPhoneNumberTest extends TestCase
{
    #[Test]
    public function phoneNumber(): void
    {
        $string = Helper::phoneNumber('+7 (900) 111-22-33');
        $this->assertEquals('79001112233', $string);

        $string = Helper::phoneNumber('9001112233');
        $this->assertEquals('79001112233', $string);

        $string = Helper::phoneNumber('7-900-111-22-33');
        $this->assertEquals('79001112233', $string);

        $string = Helper::phoneNumber('900-111-22-33');
        $this->assertEquals('79001112233', $string);

        $string = Helper::phoneNumber('7-900-111-22-33', null);
        $this->assertEquals('79001112233', $string);

        $string = Helper::phoneNumber('900-111-22-33', null);
        $this->assertEquals('9001112233', $string);
    }
}
