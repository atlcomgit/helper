<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperNumberTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperNumberTrait
 */
final class HelperNumberFormatTest extends TestCase
{
    #[Test]
    public function numberFormat(): void
    {
        $string = Helper::numberFormat(1000.123);
        $this->assertSame($string, '1 000,123');

        $string = Helper::numberFormat('1234.567', 1, '.');
        $this->assertSame($string, '1 234.6');

        $string = Helper::numberFormat(1000000);
        $this->assertSame($string, '1 000 000');

        $string = Helper::numberFormat(null, null, null);
        $this->assertSame($string, '0');
    }
}