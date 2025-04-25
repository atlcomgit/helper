<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperEnvTrait;

use Atlcom\Helper;
use Atlcom\Internal\HelperInternal;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperEnvTrait
 */
final class HelperEnvTestingTest extends TestCase
{
    #[Test]
    public function envTesting(): void
    {
        Helper::cacheRuntimeSet(HelperInternal::class . 'internalEnv', ['APP_ENV' => 'Testing']);
        $boolean = Helper::envTesting();
        $this->assertTrue($boolean === true);

        Helper::cacheRuntimeSet(HelperInternal::class . 'internalEnv', ['APP_ENV' => 'none']);
        $boolean = Helper::envTesting();
        $this->assertTrue($boolean === false);
    }
}
