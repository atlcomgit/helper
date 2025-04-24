<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperFakeTrait;

use Atlcom\Enums\HelperRegexpEnum;
use Atlcom\Helper;
use Atlcom\Hlp;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperFakeTrait
 */
final class HelperFakeUuid4Test extends TestCase
{
    #[Test]
    public function fakeUuid4(): void
    {
        $url = Hlp::fakeUuid4();
        $this->assertIsString($url);

        $string = Helper::fakeUuid4();
        $this->assertMatchesRegularExpression(HelperRegexpEnum::Uuid4->value, $string);

        $string1 = Helper::fakeUuid4();
        $string2 = Helper::fakeUuid4();
        $this->assertNotSame($string1, $string2, 'Strings should be different');
    }
}
