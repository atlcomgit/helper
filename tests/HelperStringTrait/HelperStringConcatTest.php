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
final class HelperStringConcatTest extends TestCase
{
    #[Test]
    public function stringConcat(): void
    {
        $string = Helper::stringConcat(' ', 'Иванов', 'Иван', 'Иванович');
        $this->assertTrue($string === 'Иванов Иван Иванович');

        $string = Helper::stringConcat(' ', 'Иванов', null, 'Иванович');
        $this->assertTrue($string === 'Иванов Иванович');

        $string = Helper::stringConcat(' ', 'Иванов', '', 'Иванович');
        $this->assertTrue($string === 'Иванов Иванович');

        $string = Helper::stringConcat(' ', ['Иванов', 'Иван', 'Иванович']);
        $this->assertTrue($string === 'Иванов Иван Иванович');

        $string = Helper::stringConcat(' ', ['Иванов', null, 'Иванович']);
        $this->assertTrue($string === 'Иванов Иванович');

        $string = Helper::stringConcat(' ', ['Иванов', '', 'Иванович']);
        $this->assertTrue($string === 'Иванов Иванович');

        $string = Helper::stringConcat(' ', ['Иванов', ['Иван', '', 'Иванович']]);
        $this->assertTrue($string === 'Иванов Иван Иванович');

        $string = Helper::stringConcat('', 'a', ['b'], (object)['c'], (object)['name' => 'd']);
        $this->assertTrue($string === 'abcd');

        $string = Helper::stringConcat(' ', ['a' => 'Иванов', 'b' => ['c' => 'Иван', 'd' => 'Иванович']]);
        $this->assertTrue($string === 'Иванов Иван Иванович');

        $string = Helper::stringConcat('/', 'a', 'b');
        $this->assertTrue($string === 'a/b');

        $string = Helper::stringConcat('/', 'a', '');
        $this->assertTrue($string === 'a');

        $string = Helper::stringConcat(' ', '');
        $this->assertTrue($string === '');

        $string = Helper::stringConcat(null, null);
        $this->assertTrue($string === '');
    }
}
