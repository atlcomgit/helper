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
final class HelperStringCutTest extends TestCase
{
    #[Test]
    public function stringCut(): void
    {
        $source = 'abc';
        $string = Helper::stringCut($source, 1, 1);
        $this->assertTrue($source === 'ac');
        $this->assertTrue($string === 'b');

        $source = 123;
        $string = Helper::stringCut($source, 1, 1);
        $this->assertTrue($source === '13');
        $this->assertTrue($string === '2');

        $source = 'abc';
        $string = Helper::stringCut($source, 0, 0);
        $this->assertTrue($source === 'abc');
        $this->assertTrue($string === '');

        $source = 'abc';
        $string = Helper::stringCut($source, 0);
        $this->assertTrue($source === '');
        $this->assertTrue($string === 'abc');
    }
}
