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
final class HelperBracketCopyTest extends TestCase
{
    #[Test]
    public function bracketCopy(): void
    {
        $example1 = '(1 + 2 * (3 - 4 / (5 - 3)))';

        $string = Helper::bracketCopy($example1, '(', ')', 0);
        $this->assertEquals($string, '1 + 2 * (3 - 4 / (5 - 3))');

        $string = Helper::bracketCopy($example1, '(', ')', 1);
        $this->assertEquals($string, '3 - 4 / (5 - 3)');

        $string = Helper::bracketCopy($example1, '(', ')', 2);
        $this->assertEquals($string, '5 - 3');

        $string = Helper::bracketCopy($example1, '(', ')', -1);
        $this->assertEquals($string, '5 - 3');

        $string = Helper::bracketCopy($example1, '(', ')', -2);
        $this->assertEquals($string, '3 - 4 / (5 - 3)');

        $string = Helper::bracketCopy($example1, '(', ')', -3);
        $this->assertEquals($string, '1 + 2 * (3 - 4 / (5 - 3))');

        $string = Helper::bracketCopy($example1, '(', ')', -4);
        $this->assertEquals($string, '');

        $example2 = '<div><div>1</div></div> <div>2</div>';

        $string = Helper::bracketCopy($example2, '<div>', '</div>', 0);
        $this->assertEquals($string, '<div>1</div>');

        $string = Helper::bracketCopy($example2, '<div>', '</div>', 1);
        $this->assertEquals($string, '1');

        $string = Helper::bracketCopy($example2, '<div>', '</div>', 2);
        $this->assertEquals($string, '2');

        $example3 = '<a href="1"><a href="2"></a></a> <a href="3"></a>';

        $string = Helper::bracketCopy($example3, '<a ', '>', 0);
        $this->assertEquals($string, 'href="1"');

        $string = Helper::bracketCopy($example3, '<a ', '>', 1);
        $this->assertEquals($string, 'href="2"');

        $string = Helper::bracketCopy($example3, '<a ', '>', 2);
        $this->assertEquals($string, 'href="3"');

        $string = Helper::bracketCopy(null, '(', ')', 0);
        $this->assertEquals($string, '');
    }
}