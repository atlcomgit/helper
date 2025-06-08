<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperExceptionTrait;

use Atlcom\Helper;
use Exception;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperExceptionTrait
 */
final class HelperExceptionToArrayTest extends TestCase
{
    #[Test]
    public function exceptionToArray(): void
    {
        $array = Helper::exceptionToArray(new Exception('Exception message', 400));
        $this->assertIsArray($array);
        $this->assertArrayHasKey('code', $array);
        $this->assertArrayHasKey('message', $array);
        $this->assertArrayHasKey('exception', $array);
        $this->assertArrayHasKey('file', $array);
        $this->assertArrayHasKey('trace', $array);

        $this->assertEquals(400, $array['code']);
        $this->assertEquals('Exception message', $array['message']);
        $this->assertEquals('Exception', $array['exception']);
        $this->assertMatchesRegularExpression('/HelperExceptionToArrayTest.php:[0-9]{1,}/', $array['file']);
        $this->assertIsArray($array['trace']);

        $array = Helper::exceptionToArray(null);
        $this->assertEquals([], $array);
    }
}
