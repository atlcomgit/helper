<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperNumberTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperNumberTrait
 */
final class HelperNumberCalculateTest extends TestCase
{
    #[Test]
    public function numberCalculate(): void
    {
        $string = Helper::numberCalculate('(1 + 2 + 3 - 4) * 5 / 6');
        $this->assertEquals($string, '1.66666666666666666666');

        $string = Helper::numberCalculate('1 / 3');
        $this->assertEquals($string, '0.33333333333333333333');

        $string = Helper::numberCalculate('10 / 6', 4);
        $this->assertEquals($string, '1.6666');

        $string = Helper::numberCalculate('2 * 3.1415', 3);
        $this->assertEquals($string, '6.283');

        $string = Helper::numberCalculate('1 + 2.345');
        $this->assertEquals($string, '3.345');

        $string = Helper::numberCalculate('10000000000000000000000000000000000000000000000000 + 1');
        $this->assertEquals($string, '10000000000000000000000000000000000000000000000001');

        $string = Helper::numberCalculate('100000000000000000000000000000 + 0.000000000000000000000000000001', 40);
        $this->assertEquals($string, '100000000000000000000000000000.000000000000000000000000000001');

        $string = Helper::numberCalculate('1. + .1');
        $this->assertEquals($string, '1.1');

        $string = Helper::numberCalculate('-1 - 2');
        $this->assertEquals($string, '-3');

        $string = Helper::numberCalculate('10 + (2 - 3) * 4');
        $this->assertEquals($string, '6');

        $string = Helper::numberCalculate('1 0 + 2 0');
        $this->assertEquals($string, '30');

        $string = Helper::numberCalculate('1 / 0');
        $this->assertEquals($string, 'NaN');

        $string = Helper::numberCalculate(null);
        $this->assertTrue($string === '0');

        $string = Helper::numberCalculate(null, null);
        $this->assertTrue($string === '0');
    }


    public function testBasicOperations()
    {
        $this->assertSame('3', Helper::numberCalculate('1 + 2'));
        $this->assertSame('7', Helper::numberCalculate('10 - 3'));
        $this->assertSame('20', Helper::numberCalculate('4 * 5'));
        $this->assertSame('5', Helper::numberCalculate('20 / 4'));
    }


    public function testNegativeNumbers()
    {
        $this->assertSame('1', Helper::numberCalculate('-1 + 2'));
        $this->assertSame('-1', Helper::numberCalculate('1 + -2'));
        $this->assertSame('-3', Helper::numberCalculate('-1 + -2'));
        $this->assertSame('-3', Helper::numberCalculate('-1 - 2'));
        $this->assertSame('-1', Helper::numberCalculate('1 - -2'));
        $this->assertSame('4', Helper::numberCalculate('-2 * -2'));
        $this->assertSame('-4', Helper::numberCalculate('-2 * 2'));
        $this->assertSame('2', Helper::numberCalculate('-4 / -2'));
        $this->assertSame('-2', Helper::numberCalculate('-4 / 2'));
    }


    public function testBrackets()
    {
        $this->assertSame('6', Helper::numberCalculate('1 + (2 + 3)'));
        $this->assertSame('14', Helper::numberCalculate('2 * (3 + 4)'));
        $this->assertSame('21', Helper::numberCalculate('(1 + 2) * (3 + 4)'));
        $this->assertSame('-3', Helper::numberCalculate('-(1 + 2)'));
        $this->assertSame('-5', Helper::numberCalculate('(-1) * (2 + 3)'));
        $this->assertSame('-5', Helper::numberCalculate('(-1) (2 + 3)'));
    }


    public function testOperatorPrecedence()
    {
        $this->assertSame('7', Helper::numberCalculate('1 + 2 * 3'));
        $this->assertSame('9', Helper::numberCalculate('(1 + 2) * 3'));
        $this->assertSame('2', Helper::numberCalculate('10 - 2 * 4'));
    }


    public function testZeroAndDecimals()
    {
        $this->assertSame('0', Helper::numberCalculate('0'));
        $this->assertSame('0', Helper::numberCalculate('0 + 0'));
        $this->assertSame('0.3', Helper::numberCalculate('0.1 + 0.2'));
        $this->assertSame('0.1', Helper::numberCalculate('0.5 * 0.2'));
        $this->assertSame('0.5', Helper::numberCalculate('1 / 2'));
        $this->assertSame('0.5', Helper::numberCalculate('2 / 4'));
        $this->assertSame('5', Helper::numberCalculate('2.5 * 2'));
        $this->assertSame('0.0003', Helper::numberCalculate('0.0001 + 0.0002'));
    }


    public function testPrecisionRounding()
    {
        $this->assertSame('0.33', Helper::numberCalculate('1 / 3', 2));
        $this->assertSame('0.3333', Helper::numberCalculate('1 / 3', 4));
        $this->assertSame('0.66666', Helper::numberCalculate('2 / 3', 5));
        $this->assertSame('3', Helper::numberCalculate('10 / 3', 0));
        $this->assertSame('4.8182', Helper::numberCalculate('10.6 / 2.2', 4));
        $this->assertSame('10.6', Helper::numberCalculate('4.8182 * 2.2', 4));
    }


    public function testDivisionByZero()
    {
        $this->assertSame('NaN', Helper::numberCalculate('1 / 0'));
        $this->assertSame('NaN', Helper::numberCalculate('10 / 0.0'));
        $this->assertSame('NaN', Helper::numberCalculate('0 / 0'));
        $this->assertSame('NaN', Helper::numberCalculate('1 / 000.000'));
    }


    public function testSpacing()
    {
        $this->assertSame('3', Helper::numberCalculate('   1    +   2 '));
        $this->assertSame('9', Helper::numberCalculate(' ( 1 + 2 ) * 3 '));
        $this->assertSame('2', Helper::numberCalculate(' - ( -2 ) '));
        $this->assertSame('-5', Helper::numberCalculate('-(2 + 3)'));
        $this->assertSame('6', Helper::numberCalculate('-( -3 * 2 )'));
    }
}
