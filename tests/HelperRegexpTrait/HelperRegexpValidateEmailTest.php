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
final class HelperRegexpValidateEmailTest extends TestCase
{
    #[Test]
    public function regexpValidateEmail(): void
    {
        $boolean = Helper::regexpValidateEmail('Test.example_1@domain.com');
        $this->assertTrue($boolean);

        $boolean = Helper::regexpValidateEmail('!Test.example_1@domain.com');
        $this->assertFalse($boolean);

        $boolean = Helper::regexpValidateEmail('test.example_domain.com');
        $this->assertFalse($boolean);

        $boolean = Helper::regexpValidateEmail(null);
        $this->assertTrue($boolean === false);
    }
}
