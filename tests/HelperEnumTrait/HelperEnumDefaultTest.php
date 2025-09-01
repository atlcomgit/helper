<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumDefaultEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

enum HelperEnumDefault2Enum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';

    public static function enumDefault(): mixed
    {
        return self::Name1;
    }
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumDefaultTest extends TestCase
{
    #[Test]
    public function enumDefault(): void
    {
        $value = HelperEnumDefaultEnum::enumDefault();
        $this->assertSame(null, $value);

        $value = HelperEnumDefault2Enum::enumDefault();
        $this->assertSame(HelperEnumDefault2Enum::Name1, $value);
    }
}
