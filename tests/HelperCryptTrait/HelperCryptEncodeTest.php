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
        $this->assertTrue($string === 'njsA4Z2GeKzvWLh29u9452');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === 'abc');

        $string = Helper::cryptEncode('123', 'password');
        $this->assertTrue($string === 'njsA4Z2GeKzvWGLW33L52');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === '123');

        $string = Helper::cryptEncode('abc', 'password', true);
        $this->assertTrue($string !== '');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === 'abc');

        $string = Helper::cryptEncode(123, 'password', true);
        $integer = Helper::cryptDecode($string, 'password');
        $this->assertTrue($integer === 123);

        $string = Helper::cryptEncode(['a' => 'b'], 'password', true);
        $array = Helper::cryptDecode($string, 'password');
        $this->assertTrue($array === ['a' => 'b']);

        $string = Helper::cryptEncode(['name' => 'Иванов Иван Иванович'], 'Пароль', true);
        $array = Helper::cryptDecode($string, 'Пароль');
        $this->assertTrue($array === ['name' => 'Иванов Иван Иванович']);

        $object = new stdClass();
        $object->name = 'Иванов Иван Иванович';
        $string = Helper::cryptEncode($object, 'Пароль', true);
        $object = Helper::cryptDecode($string, 'Пароль');
        $this->assertTrue($object->name === 'Иванов Иван Иванович');

        $string = Helper::cryptEncode('Иванов Иван Иванович', 'password');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === 'Иванов Иван Иванович');
    }
}