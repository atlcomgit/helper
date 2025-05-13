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
final class HelperCastToJsonTest extends TestCase
{
    #[Test]
    public function castToJson(): void
    {
        $this->assertSame('""', Helper::castToJson(''));

        $this->assertSame('"0"', Helper::castToJson('0'));
        $this->assertSame('0', Helper::castToJson(0));
        $this->assertSame('false', Helper::castToJson(false));
        $this->assertSame('"false"', Helper::castToJson('false'));

        $this->assertSame('"1"', Helper::castToJson('1'));
        $this->assertSame('1', Helper::castToJson(1));
        $this->assertSame('true', Helper::castToJson(true));
        $this->assertSame('"true"', Helper::castToJson('true'));


        $this->assertSame('[]', Helper::castToJson([]));
        $this->assertSame('{"a":1}', Helper::castToJson(['a' => 1]));
        $this->assertSame('"[\"a\"]"', Helper::castToJson('["a"]'));
        $this->assertSame('"{\"a\": 1}"', Helper::castToJson('{"a": 1}'));
        $this->assertSame('"{\"a\"}"', Helper::castToJson('{"a"}'));
        $this->assertSame('{}', Helper::castToJson((object)[]));
        $this->assertSame('{"a":1}', Helper::castToJson((object)['a' => 1]));

        $this->assertSame('1', Helper::castToJson(static fn () => 1));

        $this->assertSame(null, Helper::castToJson(null));
    }
}
