<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperReflectionTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Класс для тестирования phpdoc
 *
 * Описание класса.
 *
 * @author test
 * @package test
 */
class PhpDocTestClass
{
    /**
     * Константа теста
     *
     * @var int
     */
    public const TEST_CONST = 42;

    /**
     * Открытое свойство
     *
     * @var string
     */
    public string $publicProp;

    /**
     * Приватное свойство
     *
     * @var int
     */
    private int $privateProp;

    /**
     * Метод с phpdoc
     *
     * @param int $a Описание a
     * @param string $b Описание b
     * @return bool Описание возврата
     */
    public function methodWithPhpDoc(int $a, string $b): bool
    {
        return true;
    }


    public function methodWithoutPhpDoc() {}
}

class PhpDocEmptyClass
{
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperReflectionTrait
 */
final class HelperReflectionPhpDocTest extends TestCase
{
    #[Test]
    public function testClassPhpDocParsed(): void
    {
        $result = Helper::reflectionPhpDoc(PhpDocTestClass::class);

        $this->assertSame(PhpDocTestClass::class, $result['name']);
        $this->assertArrayHasKey('description', $result['doc']);
        $this->assertStringContainsString('Описание класса', $result['doc']['description'] ?? '');
        $this->assertArrayHasKey('author', $result['doc']);
        $this->assertArrayHasKey('package', $result['doc']);
    }


    #[Test]
    public function testConstantPhpDocParsed(): void
    {
        $result = Helper::reflectionPhpDoc(PhpDocTestClass::class);

        $this->assertArrayHasKey('TEST_CONST', $result['constants']);
        $this->assertArrayHasKey('doc', $result['constants']['TEST_CONST']);
        $this->assertArrayHasKey('var', $result['constants']['TEST_CONST']['doc']);
        $this->assertContains('int', $result['constants']['TEST_CONST']['doc']['var']);
    }


    #[Test]
    public function testPropertyPhpDocParsed(): void
    {
        $result = Helper::reflectionPhpDoc(PhpDocTestClass::class);

        $this->assertArrayHasKey('publicProp', $result['properties']);
        $this->assertArrayHasKey('doc', $result['properties']['publicProp']);
        $this->assertArrayHasKey('var', $result['properties']['publicProp']['doc']);
        $this->assertContains('string', $result['properties']['publicProp']['doc']['var']);
        $this->assertSame('public', $result['properties']['publicProp']['visibility']);

        $this->assertArrayHasKey('privateProp', $result['properties']);
        $this->assertSame('private', $result['properties']['privateProp']['visibility']);
    }


    #[Test]
    public function testMethodPhpDocParsed(): void
    {
        $result = Helper::reflectionPhpDoc(PhpDocTestClass::class);

        $this->assertArrayHasKey('methodWithPhpDoc', $result['methods']);
        $method = $result['methods']['methodWithPhpDoc'];
        $this->assertArrayHasKey('doc', $method);
        $this->assertArrayHasKey('param', $method['doc']);
        $this->assertArrayHasKey('a', $method['doc']['param']);
        $this->assertArrayHasKey('b', $method['doc']['param']);
        $this->assertArrayHasKey('return', $method['doc']);
        $this->assertContains('bool', $method['doc']['return']['types']);
        $this->assertSame('public', $method['visibility']);
        $this->assertFalse($method['static']);
        $this->assertArrayHasKey('params', $method);
        $this->assertArrayHasKey('a', $method['params']);
        $this->assertArrayHasKey('b', $method['params']);
        $this->assertSame('int', $method['params']['a']['type']);
        $this->assertSame('string', $method['params']['b']['type']);
    }


    #[Test]
    public function testMethodWithoutPhpDoc(): void
    {
        $result = Helper::reflectionPhpDoc(PhpDocTestClass::class);

        $this->assertArrayHasKey('methodWithoutPhpDoc', $result['methods']);
        $method = $result['methods']['methodWithoutPhpDoc'];
        $this->assertIsArray($method['doc']);
        $this->assertEmpty($method['doc']);
    }


    #[Test]
    public function testEmptyClass(): void
    {
        $result = Helper::reflectionPhpDoc(PhpDocEmptyClass::class);

        $this->assertSame(PhpDocEmptyClass::class, $result['name']);
        $this->assertIsArray($result['doc']);
        $this->assertEmpty($result['constants']);
        $this->assertEmpty($result['properties']);
        $this->assertArrayHasKey('methods', $result);
    }


    #[Test]
    public function testNullClassReturnsEmptyArray(): void
    {
        $this->assertSame([], Helper::reflectionPhpDoc(null));
    }
}
