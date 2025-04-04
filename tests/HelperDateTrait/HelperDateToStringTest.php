<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperDateTrait;

use Atlcom\Enums\HelperNumberDeclensionEnum;
use Atlcom\Helper;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperDateTrait
 */
final class HelperDateToStringTest extends TestCase
{
    #[Test]
    public function dateToString(): void
    {
        $string = Helper::dateToString('');
        $this->assertTrue($string === '');

        $string = Helper::dateToString(Carbon::parse('01.02.2025'));
        $this->assertEquals('первое февраля две тысячи двадцать пятый год', $string);

        $string = Helper::dateToString('31.12.2025', HelperNumberDeclensionEnum::Genitive);
        $this->assertEquals('тридцать первого декабря две тысячи двадцать пятого года', $string);

        $string = Helper::dateToString('31.12.2025', HelperNumberDeclensionEnum::Dative);
        $this->assertEquals('тридцать первому декабрю две тысячи двадцать пятому году', $string);

        $string = Helper::dateToString('31122025');
        $this->assertTrue($string === '');
    }
}
