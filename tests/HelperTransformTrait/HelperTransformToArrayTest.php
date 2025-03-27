<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperTransformTrait;

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
 * @see \Atlcom\Traits\HelperTransformTrait
 */
final class HelperTransformToArrayTest extends TestCase
{
    #[Test]
    public function transformToArray(): void
    {
        $array = Helper::transformToArray(['a', 'b']);
        $this->assertTrue($array === ['a', 'b']);

        $array = Helper::transformToArray('a');
        $this->assertTrue($array === ['a']);

        $array = Helper::transformToArray([]);
        $this->assertTrue($array === []);

        $object = new stdClass();
        $object->a = 1;
        $array = Helper::transformToArray($object);
        $this->assertTrue($array === ['a' => 1]);

        $array = Helper::transformToArray(new TestObject());
        $this->assertTrue($array === ['a' => 1]);

        $array = Helper::transformToArray(new ArrayableObject());
        $this->assertTrue($array === ['a' => 1]);

        $array = Helper::transformToArray(static fn () => ['a' => 1]);
        $this->assertTrue($array === ['a' => 1]);

        $array = Helper::transformToArray(static fn () => 'a');
        $this->assertTrue($array === ['a']);

        $array = Helper::transformToArray(static fn () => new ArrayableObject());
        $this->assertTrue($array === ['a' => 1]);
    }
}
