<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperPathTrait;

use Atlcom\Helper;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperPathTrait
 */
final class HelperPathClassNameTest extends TestCase
{
    #[Test]
    public function pathClassName(): void
    {
        $string = Helper::pathClassName(Exception::class);
        $this->assertTrue($string === 'Exception');

        $string = Helper::pathClassName('/test/Test.php');
        $this->assertTrue($string === 'Test');
    }
}
