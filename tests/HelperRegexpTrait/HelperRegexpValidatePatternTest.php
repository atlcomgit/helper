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
final class HelperRegexpValidatePatternTest extends TestCase
{
    #[Test]
    public function regexpValidatePattern(): void
    {
        $boolean = Helper::regexpValidatePattern('/test/');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidatePattern('test');
        $this->assertFalse($boolean);
    }
}
