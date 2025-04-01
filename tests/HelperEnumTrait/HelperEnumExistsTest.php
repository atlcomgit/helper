<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnumTrait;

use Atlcom\Helper;
use Atlcom\Traits\HelperEnumTrait;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

enum HelperEnumExistsEnum: string
{
    use HelperEnumTrait;

    case Name1 = 'value1';
    case Name2 = 'value2';
}

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnumTrait
 */
final class HelperEnumExistsTest extends TestCase
{
    #[Test]
    public function enumExists(): void
    {
        $boolean = HelperEnumExistsEnum::enumExists(HelperEnumExistsEnum::class);
        $this->assertTrue($boolean === false);

        $boolean = HelperEnumExistsEnum::enumExists(HelperEnumExistsEnum::Name1);
        $this->assertTrue($boolean === true);

        $boolean = HelperEnumExistsEnum::enumExists(HelperEnumExistsEnum::Name1->name);
        $this->assertTrue($boolean === true);

        $boolean = HelperEnumExistsEnum::enumExists(HelperEnumExistsEnum::Name1->value);
        $this->assertTrue($boolean === true);

        $boolean = HelperEnumExistsEnum::enumExists('Name1');
        $this->assertTrue($boolean === true);

        $boolean = HelperEnumExistsEnum::enumExists('value1');
        $this->assertTrue($boolean === true);

        $boolean = Helper::enumExists(HelperEnumExistsEnum::class);
        $this->assertTrue($boolean === false);

        $boolean = Helper::enumExists(HelperEnumExistsEnum::Name1);
        $this->assertTrue($boolean === true);

        $boolean = Helper::enumExists(HelperEnumExistsEnum::Name1->name);
        $this->assertTrue($boolean === false);

        $boolean = Helper::enumExists(HelperEnumExistsEnum::Name1->value);
        $this->assertTrue($boolean === false);

        $boolean = Helper::enumExists('Name1');
        $this->assertTrue($boolean === false);

        $boolean = Helper::enumExists('value1');
        $this->assertTrue($boolean === false);

        $boolean = Helper::enumExists(null);
        $this->assertTrue($boolean === false);
    }
}
