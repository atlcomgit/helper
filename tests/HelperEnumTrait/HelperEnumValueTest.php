<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumValueEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumValueTest extends TestCase
{
    #[Test]
    public function enumValue(): void
    {
        $mixed = HelperEnumValueEnum::enumValue(HelperEnumValueEnum::Name1);
        $this->assertTrue($mixed === 'value1');

        $mixed = HelperEnumValueEnum::enumValue(HelperEnumValueEnum::Name1->name);
        $this->assertTrue($mixed === 'value1');

        $mixed = HelperEnumValueEnum::enumValue(HelperEnumValueEnum::Name1->value);
        $this->assertTrue($mixed === 'value1');

        $mixed = HelperEnumValueEnum::enumValue('Name1');
        $this->assertTrue($mixed === 'value1');

        $mixed = HelperEnumValueEnum::enumValue('value1');
        $this->assertTrue($mixed === 'value1');

        $mixed = HelperEnumValueEnum::enumValue('value1');
        $this->assertTrue($mixed === 'value1');

        $mixed = HelperEnumValueEnum::enumValue(null);
        $this->assertTrue($mixed === null);

        $mixed = Helper::enumValue(HelperEnumValueEnum::Name1);
        $this->assertTrue($mixed === 'value1');

        $mixed = Helper::enumValue(HelperEnumValueEnum::Name1->name);
        $this->assertTrue($mixed === null);

        $mixed = Helper::enumValue(HelperEnumValueEnum::Name1->value);
        $this->assertTrue($mixed === null);

        $mixed = Helper::enumValue('value1');
        $this->assertTrue($mixed === null);

        $mixed = Helper::enumValue(null);
        $this->assertTrue($mixed === null);
    }
}
