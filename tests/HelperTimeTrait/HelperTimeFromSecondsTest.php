<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperTimeTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperTimeTrait
 */
final class HelperTimeFromSecondsTest extends TestCase
{
    #[Test]
    public function timeFromSeconds(): void
    {
        $string = Helper::timeFromSeconds(0);
        $this->assertEquals('00:00:00', $string);

        $string = Helper::timeFromSeconds('3661');
        $this->assertEquals('01:01:01', $string);

        $string = Helper::timeFromSeconds(86401);
        $this->assertEquals('00:00:01', $string);

        $string = Helper::timeFromSeconds(null);
        $this->assertEquals('00:00:00', $string);
    }
}
