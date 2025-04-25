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
final class HelperEnvLocalTest extends TestCase
{
    #[Test]
    public function envLocal(): void
    {
        Helper::cacheRuntimeSet(HelperInternal::class . 'internalEnv', ['APP_ENV' => 'Local']);
        $boolean = Helper::envLocal();
        $this->assertTrue($boolean === true);

        Helper::cacheRuntimeSet(HelperInternal::class . 'internalEnv', ['APP_ENV' => 'none']);
        $boolean = Helper::envLocal();
        $this->assertTrue($boolean === false);
    }
}
