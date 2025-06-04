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
final class HelperRegexpValidateIp4Test extends TestCase
{
    #[Test]
    public function regexpValidateIp4(): void
    {
        $boolean = Helper::regexpValidateIp4('');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateIp4('111.222.33.44');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateIp4('111.222.33.44/24');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateIp4('111.222.0.0/16');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateIp4('0.0.0.0');
        $this->assertTrue($boolean === true);

        $boolean = Helper::regexpValidateIp4('256.256.256.256');
        $this->assertTrue($boolean === false);

        $boolean = Helper::regexpValidateIp4(null);
        $this->assertTrue($boolean === false);
    }
}
