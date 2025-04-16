<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumFromEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumFromTest extends TestCase
{
    #[Test]
    public function enumFrom(): void
    {
        $enum = HelperEnumFromEnum::enumFrom(HelperEnumFromEnum::class);
        $this->assertTrue($enum === null);

        $enum = HelperEnumFromEnum::enumFrom(HelperEnumFromEnum::Name1);
        $this->assertTrue($enum === HelperEnumFromEnum::Name1);

        $enum = HelperEnumFromEnum::enumFrom(HelperEnumFromEnum::Name1->name);
        $this->assertTrue($enum === HelperEnumFromEnum::Name1);

        $enum = HelperEnumFromEnum::enumFrom(HelperEnumFromEnum::Name1->value);
        $this->assertTrue($enum === HelperEnumFromEnum::Name1);

        $enum = HelperEnumFromEnum::enumFrom('Name1');
        $this->assertTrue($enum === HelperEnumFromEnum::Name1);

        $enum = HelperEnumFromEnum::enumFrom('value1');
        $this->assertTrue($enum === HelperEnumFromEnum::Name1);

        $enum = HelperEnumFromEnum::enumFrom(null);
        $this->assertTrue($enum === null);

        $enum = Helper::enumFrom(HelperEnumFromEnum::class);
        $this->assertTrue($enum === null);

        $enum = Helper::enumFrom(HelperEnumFromEnum::Name1);
        $this->assertTrue($enum === HelperEnumFromEnum::Name1);

        $enum = Helper::enumFrom(HelperEnumFromEnum::Name1->name);
        $this->assertTrue($enum === null);

        $enum = Helper::enumFrom(HelperEnumFromEnum::Name1->value);
        $this->assertTrue($enum === null);

        $enum = Helper::enumFrom('Name1');
        $this->assertTrue($enum === null);

        $enum = Helper::enumFrom('value1');
        $this->assertTrue($enum === null);

        $enum = Helper::enumFrom(null);
        $this->assertTrue($enum === null);
    }
}
