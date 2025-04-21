<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperJsonTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperJsonTrait
 */
final class HelperJsonFlagsTest extends TestCase
{
    #[Test]
    public function jsonFlags(): void
    {
        $integer = Helper::jsonFlags();
        $this->assertEquals(3146048, $integer);
    }
}
