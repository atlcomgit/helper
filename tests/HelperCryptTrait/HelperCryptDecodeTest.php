<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCryptTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperCryptTrait
 */
final class HelperCryptDecodeTest extends TestCase
{
    #[Test]
    public function cryptDecode(): void
    {
        $string = Helper::cryptDecode('nh93432NibR3td26', 'password');
        $this->assertTrue($string === 'abc');
    }
}
