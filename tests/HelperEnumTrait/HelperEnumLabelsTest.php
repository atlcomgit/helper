<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use BackedEnum;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumLabelsEnum: string
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
final class HelperEnumLabelsTest extends TestCase
{
    #[Test]
    public function enumLabels(): void
    {
        $array = HelperEnumLabelsEnum::enumLabels();
        $this->assertTrue($array === [
            ['value' => 'value1', 'label' => 'Описание 1'],
            ['value' => 'value2', 'label' => 'Описание 2'],
        ]);

        $array = HelperEnumLabelsEnum::enumLabels('id', 'description');
        $this->assertTrue($array === [
            ['id' => 'value1', 'description' => 'Описание 1'],
            ['id' => 'value2', 'description' => 'Описание 2'],
        ]);

        $array = Helper::enumLabels();
        $this->assertTrue($array === []);
    }
}
