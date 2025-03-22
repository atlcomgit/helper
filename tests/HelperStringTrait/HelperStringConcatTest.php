<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringConcatTest extends TestCase
{
    #[Test]
    public function stringConcat(): void
    {
        $string = Helper::stringConcat(' ', 'Иванов', 'Иван', 'Иванович');
        $this->assertTrue($string === 'Иванов Иван Иванович');

        $string = Helper::stringConcat(' ', ['Иванов', 'Иван', 'Иванович']);
        $this->assertTrue($string === 'Иванов Иван Иванович');

        $string = Helper::stringConcat(' ', ['Иванов', ['Иван', '', 'Иванович']]);
        $this->assertTrue($string === 'Иванов Иван Иванович');

        $string = Helper::stringConcat(' ', ['a' => 'Иванов', 'b' => ['c' => 'Иван', 'd' => 'Иванович']]);
        $this->assertTrue($string === 'Иванов Иван Иванович');
    }
}
