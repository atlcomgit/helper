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
        $string = Helper::cryptDecode('njsA4Z2GeKzvWLh29u9452', 'password');
        $this->assertTrue($string === 'abc');

        $string = Helper::cryptDecode('nh93432NibR3td26', 'password');
        $this->assertTrue($string === null);

        $string = Helper::cryptDecode('njsA4Z2GeKzvWLh29u94520', 'password');
        $this->assertTrue($string === null);

        $string = Helper::cryptDecode('---', 'password');
        $this->assertTrue($string === null);
    }
}
