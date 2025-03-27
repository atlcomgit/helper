<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperRegexpTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperRegexpTrait
 */
final class HelperRegexpValidateUnicodeTest extends TestCase
{
    #[Test]
    public function regexpValidateUnicode(): void
    {
        $boolean = Helper::regexpValidateUnicode('');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateUnicode('0-9 AZ az');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateUnicode('АЯ ая');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateUnicode('0-9 AZ az АЯ ая');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateUnicode('01 AZ az АЯ ая 😀');
        $this->assertTrue($boolean === true);
    }
}