<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnvTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnvTrait
 */
final class HelperEnvTestTest extends TestCase
{
    #[Test]
    public function envTest(): void
    {
        putenv('APP_ENV=Test');
        $boolean = Helper::envTest();
        $this->assertTrue($boolean === true);

        putenv('APP_ENV=none');
        $boolean = Helper::envTest();
        $this->assertTrue($boolean === false);
    }
}
