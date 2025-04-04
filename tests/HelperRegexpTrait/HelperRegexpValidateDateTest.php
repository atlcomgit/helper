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
final class HelperRegexpValidateDateTest extends TestCase
{
    #[Test]
    public function regexpValidateDate(): void
    {
        $boolean = Helper::regexpValidateDate('');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateDate('01.01.2025');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateDate('2025.01.01');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateDate('01-01-2025');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateDate('2025-01-01');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateDate('01/01/2025');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateDate('2025/01/01');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateDate('01012025');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateDate('20250101');
        $this->assertTrue($boolean === false);
    }
}
