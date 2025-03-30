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
final class HelperEnvProdTest extends TestCase
{
    #[Test]
    public function envProd(): void
    {
        putenv('APP_ENV=Prod');
        $boolean = Helper::envProd();
        $this->assertTrue($boolean === true);

        putenv('APP_ENV=none');
        $boolean = Helper::envProd();
        $this->assertTrue($boolean === false);
    }
}
