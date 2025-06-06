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
final class HelperStringPasteTest extends TestCase
{
    #[Test]
    public function stringPaste(): void
    {
        $string = Helper::stringPaste('abc', 'd', 3);
        $this->assertTrue($string === 'abcd');

        $string = Helper::stringPaste('abc', 'd', 2);
        $this->assertTrue($string === 'abdc');

        $string = Helper::stringPaste('abc', 'd', 0);
        $this->assertTrue($string === 'dabc');

        $string = Helper::stringPaste('abc', 'd', -1);
        $this->assertTrue($string === 'abdc');

        $string = Helper::stringPaste('abc', 'd', -2);
        $this->assertTrue($string === 'adbc');

        $string = Helper::stringPaste(123, 0, 0);
        $this->assertTrue($string === '0123');

        $string = Helper::stringPaste(123, 4, 3);
        $this->assertTrue($string === '1234');

        $string = Helper::stringPaste(null, '0', 0);
        $this->assertTrue($string === '0');

        $string = Helper::stringPaste(null, null, 0);
        $this->assertTrue($string === '');
    }
}
