<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumNamesEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumNamesTest extends TestCase
{
    #[Test]
    public function enumNames(): void
    {
        $array = HelperEnumNamesEnum::enumNames();
        $this->assertEquals($array, ['Name1', 'Name2']);

        $array = Helper::enumNames(HelperEnumNamesEnum::class);
        $this->assertEquals($array, ['Name1', 'Name2']);
    }
}
