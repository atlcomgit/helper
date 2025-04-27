<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperReflectionTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;


#[\Attribute]
class MethodTestAttribute
{
    public function __construct(public $value = null) {}
}

#[\Attribute]
class AnotherMethodTestAttribute
{
    public function __construct(public $foo = null) {}
}

class ClassWithMethodAttributes
{
    #[MethodTestAttribute('foo')]
    public function methodWithAttribute() {}


    public function methodWithoutAttribute() {}


    #[MethodTestAttribute('bar')]
    #[AnotherMethodTestAttribute('baz')]
    private function privateMethodWithAttributes() {}
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperReflectionTrait
 */
final class HelperReflectionMethodAttributesTest extends TestCase
{
    #[Test]
    public function testReturnsAttributesForAllMethods(): void
    {
        $result = Helper::reflectionMethodAttributes(ClassWithMethodAttributes::class);

        $this->assertArrayHasKey('methodWithAttribute', $result);
        $this->assertArrayHasKey('methodWithoutAttribute', $result);
        $this->assertArrayHasKey('privateMethodWithAttributes', $result);

        $this->assertCount(1, $result['methodWithAttribute']);
        $this->assertSame(MethodTestAttribute::class, $result['methodWithAttribute'][0]['attribute']);
        $this->assertSame(['foo'], $result['methodWithAttribute'][0]['arguments']);
        $this->assertInstanceOf(MethodTestAttribute::class, $result['methodWithAttribute'][0]['instance']);

        $this->assertCount(0, $result['methodWithoutAttribute']);

        $this->assertCount(2, $result['privateMethodWithAttributes']);
        $this->assertSame(MethodTestAttribute::class, $result['privateMethodWithAttributes'][0]['attribute']);
        $this->assertSame(['bar'], $result['privateMethodWithAttributes'][0]['arguments']);
        $this->assertInstanceOf(MethodTestAttribute::class, $result['privateMethodWithAttributes'][0]['instance']);
        $this->assertSame(AnotherMethodTestAttribute::class, $result['privateMethodWithAttributes'][1]['attribute']);
        $this->assertSame(['baz'], $result['privateMethodWithAttributes'][1]['arguments']);
        $this->assertInstanceOf(AnotherMethodTestAttribute::class, $result['privateMethodWithAttributes'][1]['instance']);
    }


    #[Test]
    public function testReturnsAttributesForSpecificMethod(): void
    {
        $result = Helper::reflectionMethodAttributes(ClassWithMethodAttributes::class, 'methodWithAttribute');
        $this->assertCount(1, $result);
        $this->assertSame(MethodTestAttribute::class, $result[0]['attribute']);
        $this->assertSame(['foo'], $result[0]['arguments']);
    }


    #[Test]
    public function testReturnsEmptyArrayForUnknownMethod(): void
    {
        $result = Helper::reflectionMethodAttributes(ClassWithMethodAttributes::class, 'unknownMethod');
        $this->assertSame([], $result);
    }


    #[Test]
    public function testReturnsEmptyArrayForNullClass(): void
    {
        $result = Helper::reflectionMethodAttributes(null);
        $this->assertSame([], $result);
    }
}