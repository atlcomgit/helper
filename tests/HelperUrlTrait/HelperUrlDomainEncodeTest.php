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
final class HelperUrlDomainEncodeTest extends TestCase
{
    #[Test]
    public function urlDomainEncode(): void
    {
        $string = Helper::urlDomainEncode('пример.рф');
        $this->assertEquals('xn--e1afmkfd.xn--p1ai', $string);

        $string = Helper::urlDomainEncode('abc.com');
        $this->assertEquals('abc.com', $string);

        $string = Helper::urlDomainEncode(null);
        $this->assertEquals('', $string);
    }
}
