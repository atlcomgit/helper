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
        $array = Helper::stringSplit('abc,def', ',');
        $this->assertTrue($array === ['abc', 'def']);

        $array = Helper::stringSplit(',abc,def,', ',');
        $this->assertTrue($array === ['', 'abc', 'def', '']);

        $array = Helper::stringSplit('abc,def', [',']);
        $this->assertTrue($array === ['abc', 'def']);

        $array = Helper::stringSplit('abc,,def/xyz', [',', '/']);
        $this->assertTrue($array === ['abc', '', 'def', 'xyz']);

        $array = Helper::stringSplit('abc def', ',');
        $this->assertTrue($array === ['abc def']);

        $array = Helper::stringSplit('abc def', [',', '/']);
        $this->assertTrue($array === ['abc def']);

        $string = Helper::stringSplit('abc def', ' ', 0);
        $this->assertTrue($string === 'abc');

        $string = Helper::stringSplit('abc def', ' ', 1);
        $this->assertTrue($string === 'def');

        $string = Helper::stringSplit('abc def', ' ', -1);
        $this->assertTrue($string === 'def');

        $string = Helper::stringSplit('abc def', ' ', 2);
        $this->assertTrue($string === null);

        $string = Helper::stringSplit('abc def', ' ', -3);
        $this->assertTrue($string === null);
    }
}
