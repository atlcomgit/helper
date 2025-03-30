<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCryptTrait;

use Atlcom\Enums\HelperRegexpEnum;
use Atlcom\Helper;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

class CustomObjectCryptEncode
{
    public string $name;
    public Carbon $date;
}

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
        $this->assertEquals($string, '3eg3374c37wb3f6dcb244aUff2Cf2eya466047934cybq0rfX0pd31K85do3XcO3W49Cy3Rgn');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === 'abc');

        $string = Helper::cryptEncode('123', 'password');
        $this->assertEquals($string, '38g9374037w93e6ec3224aU8fdCa2cy1416544y566x3QdJ740Ic34Jb56o7XfO8W49Cy3Rgn');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === '123');

        $string = Helper::cryptEncode('abc', 'password', true);
        $this->assertTrue($string !== '');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === 'abc');

        $string = Helper::cryptEncode(123, 'password', true);
        $this->assertTrue($string !== '');
        $integer = Helper::cryptDecode($string, 'password');
        $this->assertTrue($integer === 123);

        $string = Helper::cryptEncode(['a' => 'b'], 'password', true);
        $this->assertTrue($string !== '');
        $array = Helper::cryptDecode($string, 'password');
        $this->assertTrue($array === ['a' => 'b']);

        $string = Helper::cryptEncode(['name' => 'Иванов Иван Иванович'], 'Пароль', true);
        $this->assertTrue($string !== '');
        $array = Helper::cryptDecode($string, 'Пароль');
        $this->assertTrue($array === ['name' => 'Иванов Иван Иванович']);

        $string = Helper::cryptEncode(null, 'password', true);
        $this->assertTrue($string !== '');
        $null = Helper::cryptDecode($string, 'password');
        $this->assertTrue($null === null);

        $string = Helper::cryptEncode('', 'password', true);
        $this->assertTrue($string !== '');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === '');

        $value = Helper::stringConcat(
            ',',
            '1000',
            Helper::stringRandom(HelperRegexpEnum::Uuid),
            Carbon::now()->toDateTimeString(),
        );
        $string = Helper::cryptEncode($value, 'password', true);
        $this->assertTrue($string !== '');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === $value);

        $object = new stdClass();
        $object->name = 'Иванов Иван Иванович';
        $string = Helper::cryptEncode($object, 'Пароль', true);
        $this->assertTrue($string !== '');
        $object = Helper::cryptDecode($string, 'Пароль');
        $this->assertInstanceOf(stdClass::class, $object);
        $this->assertTrue($object->name === 'Иванов Иван Иванович');

        $object = new CustomObjectCryptEncode();
        $object->name = 'Иванов Иван Иванович';
        $object->date = Carbon::parse('2025-01-02 01:02:03');
        $string = Helper::cryptEncode($object, 'Пароль', true);
        $this->assertTrue($string !== '');
        $object = Helper::cryptDecode($string, 'Пароль');
        $this->assertInstanceOf(CustomObjectCryptEncode::class, $object);
        $this->assertTrue($object->name === 'Иванов Иван Иванович');
        $this->assertTrue($object->date instanceof Carbon);
        $this->assertTrue($object->date->format('Y-m-d H:i:s') === '2025-01-02 01:02:03');

        $string = Helper::cryptEncode('Иванов Иван Иванович', 'password');
        $this->assertEquals($string, 'MJ1N385kdIPeL4aHDbwW6Nld36dt52YJbT394622d042d5593K5s61iu84FfDPeS2bJVdsDa234SSe4wfmo332fi34352x9436495coVO3gn9mtY345hin');
        $string = Helper::cryptDecode($string, 'password');
        $this->assertTrue($string === 'Иванов Иван Иванович');

        for ($a = 0; $a < 100; $a++) {
            $string = Helper::cryptEncode($a, (string)$a);
            $string = Helper::cryptDecode($string, (string)$a);
            $this->assertEquals($string, $a);
            $this->assertTrue($string === $a);

            $string = Helper::cryptEncode(
                $source = Helper::stringRandom("/[\w\d\s\u]/{$a}"),
                $password = Helper::stringRandom("/[\w\d\s\u]/{$a}"),
            );
            $string = Helper::cryptDecode($string, $password);
            $this->assertEquals($string, $source);
            $this->assertTrue($string === $source);
        }
    }
}
