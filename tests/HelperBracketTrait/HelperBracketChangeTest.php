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
final class HelperBracketChangeTest extends TestCase
{
    #[Test]
    public function bracketChange(): void
    {
        $example1 = '(1 + 2 * (3 - 4 / (5 - 3)))';

        $string = Helper::bracketChange($example1, '(', ')', 0, 'x');
        $this->assertEquals($string, '(x)');

        $string = Helper::bracketChange($example1, '(', ')', 1, 'x');
        $this->assertEquals($string, '(1 + 2 * (x))');

        $string = Helper::bracketChange($example1, '(', ')', 2, 'x');
        $this->assertEquals($string, '(1 + 2 * (3 - 4 / (x)))');

        $string = Helper::bracketChange($example1, '(', ')', -1, 'x');
        $this->assertEquals($string, '(1 + 2 * (3 - 4 / (x)))');

        $string = Helper::bracketChange($example1, '(', ')', -2, 'x');
        $this->assertEquals($string, '(1 + 2 * (x))');

        $string = Helper::bracketChange($example1, '(', ')', -3, 'x');
        $this->assertEquals($string, '(x)');

        $string = Helper::bracketChange($example1, '(', ')', -4, 'x');
        $this->assertEquals($string, $example1);

        $example2 = '<div><div>1</div></div> <div>2</div>';

        $string = Helper::bracketChange($example2, '<div>', '</div>', 0, '3');
        $this->assertEquals($string, '<div>3</div> <div>2</div>');

        $string = Helper::bracketChange($example2, '<div>', '</div>', 1, '3');
        $this->assertEquals($string, '<div><div>3</div></div> <div>2</div>');

        $string = Helper::bracketChange($example2, '<div>', '</div>', 2, '3');
        $this->assertEquals($string, '<div><div>1</div></div> <div>3</div>');

        $example3 = '<a href="1"><a href="2"></a></a> <a href="3"></a>';

        $string = Helper::bracketChange($example3, '<a ', '>', 0, 'href="x"');
        $this->assertEquals($string, '<a href="x"><a href="2"></a></a> <a href="3"></a>');

        $string = Helper::bracketChange($example3, '<a ', '>', 1, 'href="x"');
        $this->assertEquals($string, '<a href="1"><a href="x"></a></a> <a href="3"></a>');

        $string = Helper::bracketChange($example3, '<a ', '>', 2, 'href="x"');
        $this->assertEquals($string, '<a href="1"><a href="2"></a></a> <a href="x"></a>');

        $string = Helper::bracketChange('abc', '(', ')', 0, null);
        $this->assertEquals($string, 'abc');

        $string = Helper::bracketChange(null, '(', ')', 0, null);
        $this->assertEquals($string, '');
    }
}