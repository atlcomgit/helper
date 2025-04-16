<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperStringTrait;

use Atlcom\Enums\HelperStringBreakTypeEnum;
use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringBreakByLengthTest extends TestCase
{
    #[Test]
    public function stringBreakByLength(): void
    {
        $array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Word);
        $this->assertTrue($array === ['Иванов Иван Иванович']);

        $array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Word, 100);
        $this->assertTrue($array === ['Иванов Иван Иванович']);

        $array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Word, 10);
        $this->assertTrue($array === ['Иванов', 'Иван', 'Иванович']);

        $array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Word, 15);
        $this->assertTrue($array === ['Иванов Иван', 'Иванович']);

        $array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Word, 1);
        $this->assertTrue($array === ['Иванов', 'Иван', 'Иванович']);

        $array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Line, 100);
        $this->assertTrue($array === ['Иванов Иван Иванович']);

        $array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Line, 10);
        $this->assertTrue($array === ['Иванов Иван Иванович']);

        $array = Helper::stringBreakByLength('Иванов Иван Иванович', HelperStringBreakTypeEnum::Line, 1);
        $this->assertTrue($array === ['Иванов Иван Иванович']);

        $array = Helper::stringBreakByLength(null, HelperStringBreakTypeEnum::Line, 1);
        $this->assertTrue($array === []);
    }
}
