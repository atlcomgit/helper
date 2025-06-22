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
final class HelperStringSplitRangeTest extends TestCase
{
    #[Test]
    public function stringSplitRange(): void
    {
        $string = Helper::stringSplitRange("update abc set name = 'def'", [' where ', ' WHERE '], 1);
        $this->assertSame('', $string);

        $string = Helper::stringSplitRange("update abc set name = 'def' where def=1", [' where '], 1);
        $this->assertSame('def=1', $string);

        $string = Helper::stringSplitRange('abc,def', ',');
        $this->assertSame('abc,def', $string);

        $string = Helper::stringSplitRange('abc,def,xyz', ',', 0, 0);
        $this->assertSame('abc', $string);

        $string = Helper::stringSplitRange('abc,def,xyz', ',', 1, 1);
        $this->assertSame('def', $string);

        $string = Helper::stringSplitRange(',abc,def,', ',', 1, -2);
        $this->assertSame('abc,def', $string);

        $string = Helper::stringSplitRange(',abc,def,xyz,', ',', -2, 3);
        $this->assertSame('xyz', $string);

        $string = Helper::stringSplitRange(',abc,def,xyz,', ',', 1, 3);
        $this->assertSame('abc,def,xyz', $string);

        $string = Helper::stringSplitRange(',abc,def,xyz,', ',', 1, -3);
        $this->assertSame('abc,def', $string);

        $string = Helper::stringSplitRange('abc,def,xyz', ',', 1);
        $this->assertSame('def,xyz', $string);

        $string = Helper::stringSplitRange('abc,def,xyz', ',', -1);
        $this->assertSame('xyz', $string);

        $string = Helper::stringSplitRange('abc,def,xyz', ',', 1, -1);
        $this->assertSame('def,xyz', $string);

        $string = Helper::stringSplitRange('abc__def__xyz', '__', -2);
        $this->assertSame('def__xyz', $string);

        $string = Helper::stringSplitRange('abc', [','], 1, -1);
        $this->assertSame('', $string);

        $string = Helper::stringSplitRange('', ',', 1, -1);
        $this->assertSame('', $string);

        $string = Helper::stringSplitRange(',abc,def,xyz,', ',', null, null);
        $this->assertSame(',abc,def,xyz,', $string);

        $string = Helper::stringSplitRange(',abc,def/xyz,', [',', '/'], 1, 3);
        $this->assertSame('abc,def/xyz', $string);

        $string = Helper::stringSplitRange(null, null);
        $this->assertSame('', $string);
    }
}