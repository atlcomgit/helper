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
final class HelperEnvGetTest extends TestCase
{
    #[Test]
    public function envGet(): void
    {
        Helper::cacheRuntimeClear();
        $this->assertSame('Atlcom Helper', Helper::envGet(Helper::$keyAppEnv));

        $this->assertSame('', Helper::envGet('EMPTY_1'));
        $this->assertSame(true, Helper::envGet('BOOL_TRUE_1'));
        $this->assertSame(false, Helper::envGet('BOOL_FALSE_1'));
        $this->assertSame(0, Helper::envGet('INT_1'));
        $this->assertSame('string', Helper::envGet('STRING_1'));
        $this->assertSame([0 => 1, 1 => 2], Helper::envGet('ARRAY_3'));
        $this->assertSame(null, Helper::envGet('ABC'));
        $this->assertSame(null, Helper::envGet('NULL_1'));
        $this->assertSame(null, Helper::envGet(null));
        
        Helper::cacheRuntimeSet(HelperInternal::class . 'internalEnv' . Helper::hashXxh128(''), [Helper::$keyAppEnv => 'none']);
        $this->assertSame('none', Helper::envGet(Helper::$keyAppEnv));
    }
}
