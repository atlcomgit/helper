<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperReflectionTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;


#[\Attribute]
class TestAttribute
{
    public function __construct(public $value = null) {}
}

class TestClass
{
    #[TestAttribute('foo')]
    public string $propertyWithAttribute;

    public int $propertyWithoutAttribute;

    #[TestAttribute(['bar' => 123])]
    private float $privatePropertyWithAttribute;
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperReflectionTrait
 */
final class HelperReflectionPropertyAttributesTest extends TestCase
{
    #[Test]
    public function testReturnsAttributesForAllProperties(): void
    {
        $result = Helper::reflectionPropertyAttributes(TestClass::class);

        $this->assertArrayHasKey('propertyWithAttribute', $result);
        $this->assertArrayHasKey('propertyWithoutAttribute', $result);
        $this->assertArrayHasKey('privatePropertyWithAttribute', $result);

        $this->assertCount(1, $result['propertyWithAttribute']);
        $this->assertSame(TestAttribute::class, $result['propertyWithAttribute'][0]['attribute']);
        $this->assertSame(['foo'], $result['propertyWithAttribute'][0]['arguments']);
        $this->assertInstanceOf(TestAttribute::class, $result['propertyWithAttribute'][0]['instance']);

        $this->assertCount(0, $result['propertyWithoutAttribute']);

        $this->assertCount(1, $result['privatePropertyWithAttribute']);
        $this->assertSame(TestAttribute::class, $result['privatePropertyWithAttribute'][0]['attribute']);
        $this->assertSame([['bar' => 123]], $result['privatePropertyWithAttribute'][0]['arguments']);
        $this->assertInstanceOf(TestAttribute::class, $result['privatePropertyWithAttribute'][0]['instance']);
    }


    #[Test]
    public function testReturnsAttributesForSpecificProperty(): void
    {
        $result = Helper::reflectionPropertyAttributes(TestClass::class, 'propertyWithAttribute');
        $this->assertCount(1, $result);
        $this->assertSame(TestAttribute::class, $result[0]['attribute']);
        $this->assertSame(['foo'], $result[0]['arguments']);
    }


    #[Test]
    public function testReturnsEmptyArrayForUnknownProperty(): void
    {
        $result = Helper::reflectionPropertyAttributes(TestClass::class, 'unknownProperty');
        $this->assertSame([], $result);
    }


    #[Test]
    public function testReturnsEmptyArrayForNullClass(): void
    {
        $result = Helper::reflectionPropertyAttributes(null);
        $this->assertSame([], $result);
    }
}