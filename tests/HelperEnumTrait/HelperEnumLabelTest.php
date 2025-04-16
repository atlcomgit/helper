<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use BackedEnum;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumLabelEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';

    public static function enumLabel(?BackedEnum $value): ?string
    {
        return match ($value) {
            self::Name1 => 'Описание 1',
            self::Name2 => 'Описание 2',

            default => null,
        };
    }
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumLabelTest extends TestCase
{
    #[Test]
    public function enumLabel(): void
    {
        $string = HelperEnumLabelEnum::enumLabel(HelperEnumLabelEnum::Name1);
        $this->assertTrue($string === 'Описание 1');

        $string = HelperEnumLabelEnum::Name1->label();
        $this->assertTrue($string === 'Описание 1');

        $string = HelperEnumLabelEnum::enumLabel(null);
        $this->assertTrue($string === null);

        $string = Helper::enumLabel(HelperEnumLabelEnum::Name1);
        $this->assertTrue($string === 'Name1 (value1)');

        $string = Helper::enumLabel(null);
        $this->assertTrue($string === null);
    }
}
