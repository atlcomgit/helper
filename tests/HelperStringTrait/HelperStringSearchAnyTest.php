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
final class HelperStringSearchAnyTest extends TestCase
{
    #[Test]
    public function stringSearchAny(): void
    {
        $string = Helper::stringSearchAny('abcd', 'bc');
        $this->assertTrue($string === 'bc');

        $string = Helper::stringSearchAny('abcd', 'f');
        $this->assertTrue($string === null);

        $string = Helper::stringSearchAny('abcd', ['f', 'b']);
        $this->assertTrue($string === 'b');

        $string = Helper::stringSearchAny('Иванов Иван Иванович', ['name1' => 'Петр', 'name2' => 'Иван']);
        $this->assertTrue($string === 'Иван');

        $string = Helper::stringSearchAny('Иванов Иван Иванович', '*Иван*');
        $this->assertTrue($string === '*Иван*');

        $string = Helper::stringSearchAny('Иванов Иван Иванович', '/Иван/');
        $this->assertTrue($string === '/Иван/');

        $string = Helper::stringSearchAny('Иванов Иван Иванович', ['*Петр*', '*Иван*']);
        $this->assertTrue($string === '*Иван*');

        $string = Helper::stringSearchAny('abc', 'd');
        $this->assertTrue($string === null);
    }
}
