<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperArrayTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperArrayTrait
 */
final class HelperArrayExcludeTraceVendorTest extends TestCase
{
    #[Test]
    public function arrayExcludeTraceVendor(): void
    {
        // $array = Helper::arrayExcludeTraceVendor(debug_backtrace());
        // $this->assertEquals([], $array);

        $array = Helper::arrayExcludeTraceVendor([
            [
                'file' => 'test.php',
                'line' => '1',
                'class' => 'App\TestClass',
                'type' => '->',
                'function' => 'test',
            ],
            ...debug_backtrace(),
        ]);
        $this->assertEquals([['file' => 'test.php:1', 'func' => 'TestClass->test()']], $array);

        $array = Helper::arrayExcludeTraceVendor([
            [
                'file' => 'test.php',
                'class' => 'TestClass',
                'type' => '::',
                'function' => 'test',
            ],
            ...debug_backtrace(),
        ]);
        $this->assertEquals([['file' => 'test.php', 'func' => 'TestClass::test()']], $array);
    }
}