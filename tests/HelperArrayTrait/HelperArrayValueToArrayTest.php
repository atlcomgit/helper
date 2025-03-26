<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperArrayTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;

class TestObject
{
    public int $a = 1;
}

class ArrayableObject
{
    public function toArray()
    {
        return ['a' => 1];
    }
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperArrayTrait
 */
final class HelperArrayValueToArrayTest extends TestCase
{
    #[Test]
    public function arrayValueToArray(): void
    {
        $array = Helper::arrayValueToArray(['a', 'b']);
        $this->assertTrue($array === ['a', 'b']);

        $array = Helper::arrayValueToArray('a');
        $this->assertTrue($array === ['a']);

        $array = Helper::arrayValueToArray([]);
        $this->assertTrue($array === []);

        $object = new stdClass();
        $object->a = 1;
        $array = Helper::arrayValueToArray($object);
        $this->assertTrue($array === ['a' => 1]);

        $array = Helper::arrayValueToArray(new TestObject());
        $this->assertTrue($array === ['a' => 1]);

        $array = Helper::arrayValueToArray(new ArrayableObject());
        $this->assertTrue($array === ['a' => 1]);

        $array = Helper::arrayValueToArray(static fn () => ['a' => 1]);
        $this->assertTrue($array === ['a' => 1]);

        $array = Helper::arrayValueToArray(static fn () => 'a');
        $this->assertTrue($array === ['a']);

        $array = Helper::arrayValueToArray(static fn () => new ArrayableObject());
        $this->assertTrue($array === ['a' => 1]);
    }
}
