<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тесты трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringChangeTest extends TestCase
{
    #[Test]
    public function stringChange(): void
    {
        $string = Helper::stringChange('abc', 'd', 2);
        $this->assertTrue($string === 'abd');

        $string = Helper::stringChange('abc', 'd', 3);
        $this->assertTrue($string === 'abcd');

        $string = Helper::stringChange('abc', 'd', 0);
        $this->assertTrue($string === 'dbc');

        $string = Helper::stringChange('abc', 'efgh', 0);
        $this->assertTrue($string === 'efgh');

        $string = Helper::stringChange('abc', 'd', -1);
        $this->assertTrue($string === 'abd');

        $string = Helper::stringChange('abc', 'efg', -1);
        $this->assertTrue($string === 'abefg');

        $string = Helper::stringChange('abcefg', 'x', -3);
        $this->assertTrue($string === 'abcxfg');

        $string = Helper::stringChange('abcefg', 'xyz', -1);
        $this->assertTrue($string === 'abcefxyz');
    }
}
