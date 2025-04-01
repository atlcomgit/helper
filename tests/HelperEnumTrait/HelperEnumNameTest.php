<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumNameEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumNameTest extends TestCase
{
    #[Test]
    public function enumName(): void
    {
        $string = HelperEnumNameEnum::enumName(HelperEnumNameEnum::Name1);
        $this->assertTrue($string === 'Name1');

        $string = HelperEnumNameEnum::enumName(HelperEnumNameEnum::Name1->name);
        $this->assertTrue($string === 'Name1');

        $string = HelperEnumNameEnum::enumName(HelperEnumNameEnum::Name1->value);
        $this->assertTrue($string === 'Name1');

        $string = HelperEnumNameEnum::enumName('Name1');
        $this->assertTrue($string === 'Name1');

        $string = HelperEnumNameEnum::enumName('value1');
        $this->assertTrue($string === 'Name1');

        $string = HelperEnumNameEnum::enumName('value1');
        $this->assertTrue($string === 'Name1');

        $string = Helper::enumName(HelperEnumNameEnum::Name1);
        $this->assertTrue($string === 'Name1');

        $string = Helper::enumName(HelperEnumNameEnum::Name1->name);
        $this->assertTrue($string === null);

        $string = Helper::enumName(HelperEnumNameEnum::Name1->value);
        $this->assertTrue($string === null);

        $string = Helper::enumName('Name1');
        $this->assertTrue($string === null);
    }
}