<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperBracketTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperBracketTrait
 */
final class HelperBracketCountTest extends TestCase
{
    #[Test]
    public function bracketCount(): void
    {
        $this->assertSame(Helper::bracketCount('(a)(b)', '(', ')'), 2);

        $integer = Helper::bracketCount('(1 + 2 * (3 - 4 / (5 - 3)))', '(', ')');
        $this->assertEquals($integer, 3);

        $integer = Helper::bracketCount('<div><div>1</div></div> <div>2</div>', '<div>', '</div>');
        $this->assertEquals($integer, 3);

        $integer = Helper::bracketCount('abc', '(', ')');
        $this->assertTrue($integer === 0);

        $integer = Helper::bracketCount(null, '(', ')');
        $this->assertTrue($integer === 0);
    }
}