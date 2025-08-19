<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumFirstEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumFirstTest extends TestCase
{
    #[Test]
    public function enumFirst(): void
    {
        $enum = HelperEnumFirstEnum::enumFirst();
        $this->assertInstanceOf(HelperEnumFirstEnum::class, $enum);
        $this->assertSame(HelperEnumFirstEnum::Name1, $enum);

        $enum = Helper::enumFirst();
        $this->assertSame(null, $enum);
    }
}
