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
        $array = Helper::stringSearchAny('abcd', 'bc');
        $this->assertTrue($array === ['bc']);

        $array = Helper::stringSearchAny('abcd', 'f');
        $this->assertTrue($array === []);

        $array = Helper::stringSearchAny('abcd', ['f', 'b', 'c']);
        $this->assertTrue($array === ['b']);

        $array = Helper::stringSearchAny('Иванов Иван Иванович', ['name1' => 'Петр', 'name2' => 'Иван']);
        $this->assertTrue($array === ['Иван']);

        $array = Helper::stringSearchAny('Иванов Иван Иванович', '*Иван*');
        $this->assertTrue($array === ['*Иван*']);

        $array = Helper::stringSearchAny('Иванов Иван Иванович', '/Иван/');
        $this->assertTrue($array === ['/Иван/']);

        $array = Helper::stringSearchAny('Иванов Иван Иванович', ['*Петр*', '*Иван*']);
        $this->assertTrue($array === ['*Иван*']);

        $array = Helper::stringSearchAny('abc', 'd');
        $this->assertTrue($array === []);

        $array = Helper::stringSearchAny('DROP DATABSE name', '*DROP*DATABSE*');
        $this->assertTrue($array === ['*DROP*DATABSE*']);

        $array = Helper::stringSearchAny(null, null);
        $this->assertTrue($array === []);
    }
}
