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
final class HelperStringSegmentTest extends TestCase
{
    #[Test]
    public function stringSegment(): void
    {
        $string = Helper::stringSegment('abc2defXyz');
        $this->assertEquals($string, 'abc 2 def Xyz');

        $string = Helper::stringSegment('abc:defXyz:123:456::');
        $this->assertEquals($string, 'abc: def Xyz: 123: 456::');

        $string = Helper::stringSegment('abc;defXyz;123;456;;');
        $this->assertEquals($string, 'abc; def Xyz; 123; 456;;');

        $string = Helper::stringSegment('abc!defXyz!123!456!!');
        $this->assertEquals($string, 'abc! def Xyz! 123! 456!!');

        $string = Helper::stringSegment('abc?defXyz?123?456??');
        $this->assertEquals($string, 'abc? def Xyz? 123? 456??');

        $string = Helper::stringSegment('abc@defXyz@123@456@@');
        $this->assertEquals($string, 'abc @def Xyz @ 123 @ 456 @@');

        $string = Helper::stringSegment('abc%defXyz%123%456%%');
        $this->assertEquals($string, 'abc% def Xyz% 123% 456%%');

        $string = Helper::stringSegment('abc.defXyz.123.456..');
        $this->assertEquals($string, 'abc. def Xyz. 123.456..');

        $string = Helper::stringSegment('abc,defXyz,123,456,,');
        $this->assertEquals($string, 'abc, def Xyz, 123, 456,,');

        $string = Helper::stringSegment('abc#defXyz#123#456##');
        $this->assertEquals($string, 'abc #def Xyz #123 #456 ##');

        $string = Helper::stringSegment('abc$defXyz123$456$$');
        $this->assertEquals($string, 'abc $def Xyz 123 $456 $$');

        $string = Helper::stringSegment('abc/defXyz/123/456//');
        $this->assertEquals($string, 'abc / def Xyz / 123 / 456 //');

        $string = Helper::stringSegment('abc\\defXyz\\123\\456\\\\');
        $this->assertEquals($string, 'abc \\ def Xyz \\ 123 \\ 456 \\\\');

        $string = Helper::stringSegment('abc-defXyz-123-456--');
        $this->assertEquals($string, 'abc-def Xyz -123 -456 --');

        $string = Helper::stringSegment('abc+defXyz+123+456++');
        $this->assertEquals($string, 'abc + def Xyz + 123 + 456 ++');

        $string = Helper::stringSegment('abc*defXyz*123*456**');
        $this->assertEquals($string, 'abc * def Xyz * 123 * 456 **');

        $string = Helper::stringSegment('abc=defXyz=123=456==');
        $this->assertEquals($string, 'abc = def Xyz = 123 = 456 ==');

        $string = Helper::stringSegment('abc_defXyz_123_456__');
        $this->assertEquals($string, 'abc_def Xyz_123_456__');

        $string = Helper::stringSegment('abc  defXyz  123  456  ');
        $this->assertEquals($string, 'abc def Xyz 123 456');

        $string = Helper::stringSegment('abc(defXyz)123(456)()()');
        $this->assertEquals($string, 'abc (def Xyz) 123 (456) () ()');

        $string = Helper::stringSegment('abc[defXyz]123[456][][]');
        $this->assertEquals($string, 'abc [def Xyz] 123 [456] [] []');

        $string = Helper::stringSegment('abc<defXyz>123<456><><>');
        $this->assertEquals($string, 'abc <def Xyz> 123 <456> <> <>');

        $string = Helper::stringSegment('abc{defXyz}123{456}{}{}');
        $this->assertEquals($string, 'abc {def Xyz} 123 {456} {} {}');

        $string = Helper::stringSegment('abc!=defXyz!=123!=456!=!=');
        $this->assertEquals($string, 'abc != def Xyz != 123 != 456 != !=');

        $string = Helper::stringSegment('abcабвГде123опр-сту@клм');
        $this->assertEquals($string, 'abc абв Где 123 опр-сту @ клм');

        $string = Helper::stringSegment('abc"defXyz"123"456"');
        $this->assertEquals($string, 'abc"def Xyz"123"456"');

        $string = Helper::stringSegment('1+2*(3+4-1)');
        $this->assertEquals($string, '1 + 2 * (3 + 4 -1)');

        $string = Helper::stringSegment(null);
        $this->assertEquals($string, '');
    }
}