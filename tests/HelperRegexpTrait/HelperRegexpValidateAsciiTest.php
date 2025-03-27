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
final class HelperRegexpValidateAsciiTest extends TestCase
{
    #[Test]
    public function regexpValidateAscii(): void
    {
        $boolean = Helper::regexpValidateAscii('');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateAscii('0-9 AZ az');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateAscii('АЯ ая');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateAscii('0-9 AZ az АЯ ая');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateAscii('01 AZ az АЯ ая 😀');
        $this->assertTrue($boolean === false);
    }
}
