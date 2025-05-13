<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCastTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperCastTrait
 */
final class HelperCastToObjectTest extends TestCase
{
    #[Test]
    public function castToObject(): void
    {
        $this->assertEquals((object)[''], Helper::castToObject(''));

        $this->assertEquals((object)['0'], Helper::castToObject('0'));
        $this->assertEquals((object)[0], Helper::castToObject(0));
        $this->assertEquals((object)[false], Helper::castToObject(false));
        $this->assertEquals((object)['false'], Helper::castToObject('false'));

        $this->assertEquals((object)['1'], Helper::castToObject('1'));
        $this->assertEquals((object)[1], Helper::castToObject(1));
        $this->assertEquals((object)[true], Helper::castToObject(true));
        $this->assertEquals((object)['true'], Helper::castToObject('true'));


        $this->assertEquals((object)[], Helper::castToObject([]));
        $this->assertEquals((object)['a' => 1], Helper::castToObject(['a' => 1]));
        $this->assertEquals((object)['a'], Helper::castToObject('["a"]'));
        $this->assertEquals((object)['a' => 1], Helper::castToObject('{"a": 1}'));
        $this->assertEquals((object)['{"a"}'], Helper::castToObject('{"a"}'));
        $this->assertEquals((object)[], Helper::castToObject((object)[]));

        $this->assertEquals((object)[1], Helper::castToObject(static fn () => 1));

        $this->assertSame(null, Helper::castToObject(null));
    }
}
