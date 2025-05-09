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
final class HelperEnvStageTest extends TestCase
{
    #[Test]
    public function envStage(): void
    {
        Helper::cacheRuntimeSet(HelperInternal::class . 'internalEnv', ['APP_ENV' => 'Stage']);
        $boolean = Helper::envStage();
        $this->assertTrue($boolean === true);

        Helper::cacheRuntimeSet(HelperInternal::class . 'internalEnv', ['APP_ENV' => 'none']);
        $boolean = Helper::envStage();
        $this->assertTrue($boolean === false);
    }
}
