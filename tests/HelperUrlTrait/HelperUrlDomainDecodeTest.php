<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperUrlTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperUrlTrait
 */
final class HelperUrlDomainDecodeTest extends TestCase
{
    #[Test]
    public function urlDomainDecode(): void
    {
        $string = Helper::urlDomainDecode('xn--e1afmkfd.xn--p1ai');
        $this->assertEquals('пример.рф', $string);

        $string = Helper::urlDomainDecode('abc.com');
        $this->assertEquals('abc.com', $string);
    }
}
