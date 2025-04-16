<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumRandomEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumRandomTest extends TestCase
{
    #[Test]
    public function enumRandom(): void
    {
        $enum = HelperEnumRandomEnum::enumRandom(HelperEnumRandomEnum::class);
        $this->assertInstanceOf(HelperEnumRandomEnum::class, $enum);

        $enum = HelperEnumRandomEnum::enumRandom();
        $this->assertInstanceOf(HelperEnumRandomEnum::class, $enum);

        $enum = HelperEnumRandomEnum::enumRandom(null);
        $this->assertInstanceOf(HelperEnumRandomEnum::class, $enum);

        $enum = Helper::enumRandom(HelperEnumRandomEnum::class);
        $this->assertInstanceOf(HelperEnumRandomEnum::class, $enum);

        $enum = Helper::enumRandom();
        $this->assertTrue($enum === null);

        $enum = Helper::enumRandom(null);
        $this->assertTrue($enum === null);
    }
}
