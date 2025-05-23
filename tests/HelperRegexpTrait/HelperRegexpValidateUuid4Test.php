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
final class HelperRegexpValidateUuid4Test extends TestCase
{
    #[Test]
    public function regexpValidateUuid4(): void
    {
        $boolean = Helper::regexpValidateUuid4('04d19f50-2fab-417a-815d-306b6a6f67ec');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidateUuid4('04D19F50-2FAB-417A-815D-306B6A6F67EC');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidateUuid4('04d19f50-2fab-417a-815d-306b6a6f67e');
        $this->assertFalse($boolean);

        $boolean = Helper::regexpValidateUuid4(null);
        $this->assertTrue($boolean === false);
    }
}
