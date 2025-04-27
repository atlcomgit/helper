<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperReflectionTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;


#[\Attribute]
class ClassTestAttribute
{
    public function __construct(public $value = null) {}
}

#[\Attribute]
class AnotherClassTestAttribute
{
    public function __construct(public $foo = null) {}
}

#[ClassTestAttribute('main')]
#[AnotherClassTestAttribute('bar')]
class ClassWithAttributes
{
}

class ClassWithoutAttributes
{
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperReflectionTrait
 */
final class HelperReflectionClassAttributesTest extends TestCase
{
    #[Test]
    public function testReturnsAttributesForClassWithAttributes(): void
    {
        $result = Helper::reflectionClassAttributes(ClassWithAttributes::class);

        $this->assertCount(2, $result);

        $this->assertSame(ClassTestAttribute::class, $result[0]['attribute']);
        $this->assertSame(['main'], $result[0]['arguments']);
        $this->assertInstanceOf(ClassTestAttribute::class, $result[0]['instance']);

        $this->assertSame(AnotherClassTestAttribute::class, $result[1]['attribute']);
        $this->assertSame(['bar'], $result[1]['arguments']);
        $this->assertInstanceOf(AnotherClassTestAttribute::class, $result[1]['instance']);
    }


    #[Test]
    public function testReturnsEmptyArrayForClassWithoutAttributes(): void
    {
        $result = Helper::reflectionClassAttributes(ClassWithoutAttributes::class);
        $this->assertSame([], $result);
    }


    #[Test]
    public function testReturnsEmptyArrayForNullClass(): void
    {
        $result = Helper::reflectionClassAttributes(null);
        $this->assertSame([], $result);
    }


    #[Test]
    public function testThrowsExceptionForUnknownClass(): void
    {
        $this->expectException(\Atlcom\Exceptions\HelperException::class);
        Helper::reflectionClassAttributes('UnknownClass');
    }
}