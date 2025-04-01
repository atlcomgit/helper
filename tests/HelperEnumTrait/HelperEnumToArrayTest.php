<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumToArrayEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumToArrayTest extends TestCase
{
    #[Test]
    public function enumToArray(): void
    {
        $array = HelperEnumToArrayEnum::enumToArray();
        $this->assertEquals($array, ['Name1' => 'value1', 'Name2' => 'value2']);

        $array = Helper::enumToArray(HelperEnumToArrayEnum::class);
        $this->assertEquals($array, ['Name1' => 'value1', 'Name2' => 'value2']);

        $array = Helper::enumToArray(HelperEnumToArrayEnum::Name1);
        $this->assertEquals($array, ['name' => 'Name1', 'value' => 'value1']);

        $array = HelperEnumToArrayEnum::Name1->toArray();
        $this->assertEquals($array, ['name' => 'Name1', 'value' => 'value1']);
    }
}
