<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumValuesEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumValuesTest extends TestCase
{
    #[Test]
    public function enumValues(): void
    {
        $array = HelperEnumValuesEnum::enumValues();
        $this->assertEquals($array, ['value1', 'value2']);

        $array = HelperEnumValuesEnum::enumValues();
        $this->assertEquals($array, ['value1', 'value2']);

        $array = Helper::enumValues(HelperEnumValuesEnum::class);
        $this->assertEquals($array, ['value1', 'value2']);

        $array = Helper::enumValues(null);
        $this->assertEquals($array, []);
    }
}
