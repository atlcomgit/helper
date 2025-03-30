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
final class HelperEnvDevTest extends TestCase
{
    #[Test]
    public function envDev(): void
    {
        putenv('APP_ENV=Dev');
        $boolean = Helper::envDev();
        $this->assertTrue($boolean === true);

        putenv('APP_ENV=none');
        $boolean = Helper::envDev();
        $this->assertTrue($boolean === false);
    }
}
