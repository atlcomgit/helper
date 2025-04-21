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
final class HelperBracketDeleteTest extends TestCase
{
    #[Test]
    public function bracketDelete(): void
    {
        $this->assertSame(Helper::bracketDelete('(a)(b)', '(', ')', 0), '(b)');

        $example1 = '(1 + 2 * (3 - 4 / (5 - 3)))';

        $string = Helper::bracketDelete($example1, '(', ')', 0);
        $this->assertEquals($string, '');

        $string = Helper::bracketDelete($example1, '(', ')', 1);
        $this->assertEquals($string, '(1 + 2 * )');

        $string = Helper::bracketDelete($example1, '(', ')', 2);
        $this->assertEquals($string, '(1 + 2 * (3 - 4 / ))');

        $string = Helper::bracketDelete($example1, '(', ')', -1);
        $this->assertEquals($string, '(1 + 2 * (3 - 4 / ))');

        $string = Helper::bracketDelete($example1, '(', ')', -2);
        $this->assertEquals($string, '(1 + 2 * )');

        $string = Helper::bracketDelete($example1, '(', ')', -3);
        $this->assertEquals($string, '');

        $string = Helper::bracketDelete($example1, '(', ')', -4);
        $this->assertEquals($string, $example1);

        $example2 = '<div><div>1</div></div> <div>2</div>';

        $string = Helper::bracketDelete($example2, '<div>', '</div>', 0);
        $this->assertEquals($string, ' <div>2</div>');

        $string = Helper::bracketDelete($example2, '<div>', '</div>', 1);
        $this->assertEquals($string, '<div></div> <div>2</div>');

        $string = Helper::bracketDelete($example2, '<div>', '</div>', 2);
        $this->assertEquals($string, '<div><div>1</div></div> ');

        $example3 = '<a href="1"><a href="2"></a></a> <a href="3"></a>';

        $string = Helper::bracketDelete($example3, '<a', '>', 0);
        $this->assertEquals($string, '<a href="2"></a></a> <a href="3"></a>');

        $string = Helper::bracketDelete($example3, '<a', '>', 1);
        $this->assertEquals($string, '<a href="1"></a></a> <a href="3"></a>');

        $string = Helper::bracketDelete($example3, '<a', '>', 2);
        $this->assertEquals($string, '<a href="1"><a href="2"></a></a> </a>');

        $string = Helper::bracketDelete('abc', '(', ')', 0);
        $this->assertEquals($string, 'abc');

        $string = Helper::bracketDelete(null, '(', ')', 0);
        $this->assertEquals($string, '');
    }
}
