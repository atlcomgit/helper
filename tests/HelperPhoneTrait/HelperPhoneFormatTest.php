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
final class HelperPhoneFormatTest extends TestCase
{
    #[Test]
    public function phoneFormat(): void
    {
        $string = Helper::phoneFormat('79001112233');
        $this->assertEquals('+7 (900) 111-22-33', $string);

        $string = Helper::phoneFormat('9001112233');
        $this->assertEquals('+7 (900) 111-22-33', $string);

        $string = Helper::phoneFormat('7-900-111-22-33');
        $this->assertEquals('+7 (900) 111-22-33', $string);

        $string = Helper::phoneFormat('7-900-111-22-33', '');
        $this->assertEquals('+7 (900) 111-22-33', $string);

        $string = Helper::phoneFormat('900-111-22-33', null);
        $this->assertEquals('(900) 111-22-33', $string);

        Helper::$phoneFormat = '%s(%s%s%s)%s%s%s%s%s%s%s';
        $string = Helper::phoneFormat('79001112233');
        $this->assertEquals('7(900)1112233', $string);
    }
}
