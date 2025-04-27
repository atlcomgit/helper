<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperReflectionTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;


#[\Attribute]
class ArgTestAttribute
{
    public function __construct(public $value = null) {}
}

#[\Attribute]
class AnotherArgTestAttribute
{
    public function __construct(public $foo = null) {}
}

class ClassWithArgumentAttributes
{
    public function methodWithArgAttributes(
        #[ArgTestAttribute('foo')] $param1,
        #[AnotherArgTestAttribute('bar')] $param2,
        $param3,
    ) {}


    public function methodWithoutArgAttributes($a, $b) {}
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperReflectionTrait
 */
final class HelperReflectionArgumentAttributesTest extends TestCase
{
    #[Test]
    public function testReturnsAttributesForAllArgumentsOfAllMethods(): void
    {
        $result = Helper::reflectionArgumentAttributes(ClassWithArgumentAttributes::class);

        $this->assertArrayHasKey('methodWithArgAttributes', $result);
        $this->assertArrayHasKey('methodWithoutArgAttributes', $result);

        $this->assertArrayHasKey('param1', $result['methodWithArgAttributes']);
        $this->assertArrayHasKey('param2', $result['methodWithArgAttributes']);
        $this->assertArrayHasKey('param3', $result['methodWithArgAttributes']);

        $this->assertCount(1, $result['methodWithArgAttributes']['param1']);
        $this->assertSame(ArgTestAttribute::class, $result['methodWithArgAttributes']['param1'][0]['attribute']);
        $this->assertSame(['foo'], $result['methodWithArgAttributes']['param1'][0]['arguments']);
        $this->assertInstanceOf(ArgTestAttribute::class, $result['methodWithArgAttributes']['param1'][0]['instance']);

        $this->assertCount(1, $result['methodWithArgAttributes']['param2']);
        $this->assertSame(AnotherArgTestAttribute::class, $result['methodWithArgAttributes']['param2'][0]['attribute']);
        $this->assertSame(['bar'], $result['methodWithArgAttributes']['param2'][0]['arguments']);
        $this->assertInstanceOf(AnotherArgTestAttribute::class, $result['methodWithArgAttributes']['param2'][0]['instance']);

        $this->assertCount(0, $result['methodWithArgAttributes']['param3']);

        $this->assertCount(0, $result['methodWithoutArgAttributes']['a']);
        $this->assertCount(0, $result['methodWithoutArgAttributes']['b']);
    }


    #[Test]
    public function testReturnsAttributesForArgumentsOfSpecificMethod(): void
    {
        $result = Helper::reflectionArgumentAttributes(ClassWithArgumentAttributes::class, 'methodWithArgAttributes');

        $this->assertArrayHasKey('param1', $result);
        $this->assertArrayHasKey('param2', $result);
        $this->assertArrayHasKey('param3', $result);

        $this->assertCount(1, $result['param1']);
        $this->assertSame(ArgTestAttribute::class, $result['param1'][0]['attribute']);
        $this->assertSame(['foo'], $result['param1'][0]['arguments']);

        $this->assertCount(1, $result['param2']);
        $this->assertSame(AnotherArgTestAttribute::class, $result['param2'][0]['attribute']);
        $this->assertSame(['bar'], $result['param2'][0]['arguments']);

        $this->assertCount(0, $result['param3']);
    }


    #[Test]
    public function testReturnsAttributesForSpecificArgument(): void
    {
        $result = Helper::reflectionArgumentAttributes(ClassWithArgumentAttributes::class, 'methodWithArgAttributes', 'param1');
        $this->assertCount(1, $result);
        $this->assertSame(ArgTestAttribute::class, $result[0]['attribute']);
        $this->assertSame(['foo'], $result[0]['arguments']);
    }


    #[Test]
    public function testReturnsEmptyArrayForUnknownMethodOrArgument(): void
    {
        $result = Helper::reflectionArgumentAttributes(ClassWithArgumentAttributes::class, 'unknownMethod');
        $this->assertSame([], $result);

        $result = Helper::reflectionArgumentAttributes(ClassWithArgumentAttributes::class, 'methodWithArgAttributes', 'unknownParam');
        $this->assertSame([], $result);
    }


    #[Test]
    public function testReturnsEmptyArrayForNullClass(): void
    {
        $result = Helper::reflectionArgumentAttributes(null);
        $this->assertSame([], $result);
    }
}
