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
final class HelperBracketSearchTest extends TestCase
{
    #[Test]
    public function bracketSearch(): void
    {
        $this->assertSame(Helper::bracketSearch('a b', '(', ')', 'a'), []);

        $this->assertSame(Helper::bracketSearch('(a)(b)', '(', ')', 'a'), ['a' => [0]]);

        $example1 = '(1 + 2 * (3 - 4 / (5 - 3)))';

        $array = Helper::bracketSearch($example1, '(', ')', '1 + 2');
        $this->assertEquals($array, ['1 + 2' => [0]]);

        $array = Helper::bracketSearch($example1, '(', ')', 1);
        $this->assertEquals($array, [1 => [0]]);

        $array = Helper::bracketSearch($example1, '(', ')', 2);
        $this->assertEquals($array, [2 => [0]]);

        $array = Helper::bracketSearch($example1, '(', ')', 3);
        $this->assertEquals($array, [3 => [0, 1, 2]]);

        $example2 = '<div><div>1</div></div> <div>2</div>';

        $array = Helper::bracketSearch($example2, '<div>', '</div>', '1');
        $this->assertEquals($array, ['1' => [0, 1]]);

        $array = Helper::bracketSearch($example2, '<div>', '</div>', '2');
        $this->assertEquals($array, ['2' => [2]]);

        $array = Helper::bracketSearch($example2, '<div>', '</div>', '<div>1</div>');
        $this->assertEquals($array, ['<div>1</div>' => [0]]);

        $example3 = '<a href="1"><a href="2"></a></a> <a href="3"></a>';

        $array = Helper::bracketSearch($example3, '<a ', '>', 'href="1"');
        $this->assertEquals($array, ['href="1"' => [0]]);

        $array = Helper::bracketSearch($example3, '<a ', '>', 'href="2"');
        $this->assertEquals($array, ['href="2"' => [1]]);

        $array = Helper::bracketSearch($example3, '<a ', '>', 'href="3"');
        $this->assertEquals($array, ['href="3"' => [2]]);

        $array = Helper::bracketSearch('abc', '(', ')', null);
        $this->assertEquals($array, []);

        $array = Helper::bracketSearch(null, '(', ')', null);
        $this->assertEquals($array, []);
    }
}