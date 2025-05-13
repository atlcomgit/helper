<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCastTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperCastTrait
 */
final class HelperCastToCallableTest extends TestCase
{
    #[Test]
    public function castToCallable(): void
    {
        $this->assertSame('', Helper::castToCallable('')());
        $this->assertSame(1, Helper::castToCallable(1)());

        $this->assertSame(null, Helper::castToCallable(null));
    }
}