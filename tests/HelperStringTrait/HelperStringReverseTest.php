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
final class HelperStringReverseTest extends TestCase
{
    #[Test]
    public function stringReverse(): void
    {
        $string = Helper::stringReverse('abc');
        $this->assertTrue($string === 'cba');

        $string = Helper::stringReverse(123);
        $this->assertTrue($string === '321');
    }
}