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
final class HelperStringReplaceTest extends TestCase
{
    #[Test]
    public function stringReplace(): void
    {
        $string = Helper::stringReplace('abcd', 'd', 'x');
        $this->assertTrue($string === 'abcx');

        $string = Helper::stringReplace('abcd', ['d' => 'x']);
        $this->assertTrue($string === 'abcx');

        $string = Helper::stringReplace('abcd', ['a', 'd' => 'x'], ['x']);
        $this->assertTrue($string === 'xbcx');

        $string = Helper::stringReplace('abcd', ['a', 'd' => 'x']);
        $this->assertTrue($string === 'bcx');

        $string = Helper::stringReplace('abcd', ['a', 'd' => 'x']);
        $this->assertTrue($string === 'bcx');

        $string = Helper::stringReplace('abcd', 'a', ['x']);
        $this->assertTrue($string === 'xbcd');

        $string = Helper::stringReplace('abcd', ['/a/' => 'x']);
        $this->assertTrue($string === 'xbcd');

        $string = Helper::stringReplace('abc', ['a', 'b'], 'x');
        $this->assertTrue($string === 'xxc');

        $string = Helper::stringReplace(null, null);
        $this->assertTrue($string === '');
    }
}
