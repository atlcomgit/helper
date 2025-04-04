<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperStringTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringSegmentTest extends TestCase
{
    #[Test]
    public function stringSegment(): void
    {
        $string = Helper::stringSegment('abc2defXyz');
        $this->assertTrue($string === 'abc 2 def Xyz');
    }
}
