<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperStringTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringPluralTest extends TestCase
{
    #[Test]
    public function stringPlural(): void
    {
        $string = Helper::stringPlural(0, ['штук', 'штука', 'штуки']);
        $this->assertSame('0 штук', $string);

        $string = Helper::stringPlural(1, ['штук', 'штука', 'штуки']);
        $this->assertSame('1 штука', $string);

        $string = Helper::stringPlural(2, ['штук', 'штука', 'штуки']);
        $this->assertSame('2 штуки', $string);

        $string = Helper::stringPlural(4, ['штук', 'штука', 'штуки']);
        $this->assertSame('4 штуки', $string);

        $string = Helper::stringPlural(5, ['штук', 'штука', 'штуки']);
        $this->assertSame('5 штук', $string);

        $string = Helper::stringPlural(10, ['штук', 'штука', 'штуки']);
        $this->assertSame('10 штук', $string);

        $string = Helper::stringPlural(1, ['штук', 'штука', 'штуки'], false);
        $this->assertSame('штука', $string);

        $string = Helper::stringPlural(null, ['штук', 'штука', 'штуки'], false);
        $this->assertSame('', $string);

        $string = Helper::stringPlural(0.0, ['секунд', 'секунда', 'секунды']);
        $this->assertSame('0 секунд', $string);

        $string = Helper::stringPlural(round(0.0001, 1), ['секунд', 'секунда', 'секунды']);
        $this->assertSame('0 секунд', $string);

        $string = Helper::stringPlural(round(0.003544, 1), ['секунд', 'секунда', 'секунды']);
        $this->assertSame('0 секунд', $string);

        $string = Helper::stringPlural(round(0.203544, 1), ['секунд', 'секунда', 'секунды']);
        $this->assertSame('0.2 секунды', $string);

        $string = Helper::stringPlural(0.003544, ['секунд', 'секунда', 'секунды']);
        $this->assertSame('0.003544 секунды', $string);

        $string = Helper::stringPlural(0.01, ['секунд', 'секунда', 'секунды']);
        $this->assertSame('0.01 секунды', $string);
    }
}
