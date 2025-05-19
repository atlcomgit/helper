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
final class HelperCastToBoolTest extends TestCase
{
    #[Test]
    public function castToBool(): void
    {
        $this->assertSame(true, Helper::castToBool(true));

        $this->assertSame(false, Helper::castToBool('0'));
        $this->assertSame(false, Helper::castToBool(0));
        $this->assertSame(false, Helper::castToBool(false));
        $this->assertSame(false, Helper::castToBool('false'));

        $this->assertSame(true, Helper::castToBool('1'));
        $this->assertSame(true, Helper::castToBool(1));
        $this->assertSame(true, Helper::castToBool(true));
        $this->assertSame(true, Helper::castToBool('true'));

        $this->assertSame(false, Helper::castToBool([]));
        $this->assertSame(true, Helper::castToBool(['a']));
        $this->assertSame(true, Helper::castToBool((object)[]));

        $this->assertSame(true, Helper::castToBool(static fn () => 1));

        $this->assertSame(false, Helper::castToBool(''));
        $this->assertSame(null, Helper::castToBool(null));
    }
}
