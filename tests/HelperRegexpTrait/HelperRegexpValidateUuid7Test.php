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
final class HelperRegexpValidateUuid7Test extends TestCase
{
    #[Test]
    public function regexpValidateUuid7(): void
    {
        $boolean = Helper::regexpValidateUuid7('019668a4-02a1-7f64-9d90-52760464b9ce');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidateUuid7('019668A4-02A1-7F64-9D90-52760464B9CE');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidateUuid7('019668a4-02a1-7f64-9d90-52760464b9c');
        $this->assertFalse($boolean);

        $boolean = Helper::regexpValidateUuid7(null);
        $this->assertTrue($boolean === false);
    }
}
