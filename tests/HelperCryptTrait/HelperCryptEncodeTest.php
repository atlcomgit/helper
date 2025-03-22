<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCryptTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperCryptTrait
 */
final class HelperCryptEncodeTest extends TestCase
{
    #[Test]
    public function cryptEncode(): void
    {
        $string = Helper::cryptEncode('abc', 'password');
        $this->assertTrue($string === 'nh93432NibR3td26');

        $string = Helper::cryptEncode('abc', 'password', true);
        $this->assertTrue($string !== '');

        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === 'abc');

        $string = Helper::cryptEncode(123, 'password', true);
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === '123');

        $string = Helper::cryptEncode(['a' => 'b'], 'password', true);
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === '{"a":"b"}');

        $string = Helper::cryptEncode(['name' => 'Иванов Иван Иванович'], 'Пароль', true);
        $string = Helper::cryptDecode($string, 'Пароль');
        $array = json_decode($string, true);
        $this->assertTrue($array === ['name' => 'Иванов Иван Иванович']);

        $object = new stdClass();
        $object->name = 'Иванов Иван Иванович';
        $string = Helper::cryptEncode($object, 'Пароль', true);
        $string = Helper::cryptDecode($string, 'Пароль');
        $object = json_decode($string);
        $this->assertTrue($object->name === 'Иванов Иван Иванович');
    }
}
