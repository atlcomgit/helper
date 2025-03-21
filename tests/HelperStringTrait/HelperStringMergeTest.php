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
final class HelperStringMergeTest extends TestCase
{
    #[Test]
    public function stringMerge(): void
    {
        $string = Helper::stringMerge('abc', 'de');
        $this->assertTrue($string === 'dec');

        $string = Helper::stringMerge(['abc', 'de', 'f']);
        $this->assertTrue($string === 'fec');

        $string = Helper::stringMerge(['abc', 12, 3]);
        $this->assertTrue($string === '32c');

        $string = Helper::stringMerge(['000', '11']);
        $this->assertTrue($string === '110');

        $string = Helper::stringMerge(['000', '11'], '0');
        $this->assertTrue($string === '010');
    }
}
