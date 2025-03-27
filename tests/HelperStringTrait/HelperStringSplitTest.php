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
final class HelperStringSplitTest extends TestCase
{
    #[Test]
    public function stringSplit(): void
    {
        $string = Helper::stringSplit('abc,def', ',');
        $this->assertTrue($string === ['abc', 'def']);

        $string = Helper::stringSplit(',abc,def,', ',');
        $this->assertTrue($string === ['', 'abc', 'def', '']);

        $string = Helper::stringSplit('abc,def', [',']);
        $this->assertTrue($string === ['abc', 'def']);

        $string = Helper::stringSplit('abc,,def/xyz', [',', '/']);
        $this->assertTrue($string === ['abc', '', 'def', 'xyz']);

        $string = Helper::stringSplit('abc def', ',');
        $this->assertTrue($string === ['abc def']);

        $string = Helper::stringSplit('abc def', [',', '/']);
        $this->assertTrue($string === ['abc def']);
    }
}
