<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumLastEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumLastTest extends TestCase
{
    #[Test]
    public function enumLast(): void
    {
        $enum = HelperEnumLastEnum::enumLast();
        $this->assertInstanceOf(HelperEnumLastEnum::class, $enum);
        $this->assertSame(HelperEnumLastEnum::Name2, $enum);

        $enum = Helper::enumLast();
        $this->assertSame(null, $enum);
    }
}
