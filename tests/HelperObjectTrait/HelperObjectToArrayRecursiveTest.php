<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperObjectTrait;

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

class ArrayableObject2
{
    public ArrayableObject $a;
    public $b = 2;
    public function __construct()
    {
        $this->a = new ArrayableObject();
    }
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperObjectTrait
 */
final class HelperObjectToArrayRecursiveTest extends TestCase
{
    #[Test]
    public function objectToArrayRecursive(): void
    {
        $array = Helper::objectToArrayRecursive((object)['a' => 1, 'b' => (object)['c' => 2], 'd' => (object)[3]]);
        $this->assertSame($array, ['a' => 1, 'b' => ['c' => 2], 'd' => [3]]);

        $object = new stdClass();
        $object->a = 1;
        $array = Helper::objectToArrayRecursive($object);
        $this->assertSame($array, ['a' => 1]);

        $array = Helper::objectToArrayRecursive(new TestObject());
        $this->assertSame($array, ['a' => 1]);

        $array = Helper::objectToArrayRecursive(new ArrayableObject());
        $this->assertSame($array, ['a' => 1]);

        $array = Helper::objectToArrayRecursive(new ArrayableObject2());
        $this->assertSame($array, ['a' => ['a' => 1], 'b' => 2]);

        $array = Helper::objectToArrayRecursive(static fn () => ['a' => 1]);
        $this->assertSame($array, ['a' => 1]);

        $array = Helper::objectToArrayRecursive(static fn () => ['a']);
        $this->assertSame($array, ['a']);

        $array = Helper::objectToArrayRecursive(static fn () => new ArrayableObject());
        $this->assertSame($array, ['a' => 1]);

        $array = Helper::objectToArrayRecursive(null);
        $this->assertSame($array, null);
    }
}