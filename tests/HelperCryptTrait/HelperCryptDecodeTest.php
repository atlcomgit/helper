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
        $string = Helper::cryptDecode('3eg3374c37wb3f6dcb244aUff2Cf2eya466047934cybq0rfX0pd31K85do3XcO3W49Cy3Rgn', 'password');
        $this->assertTrue($string === 'abc');

        $string = Helper::cryptDecode('error3eg3374c37wb3f6dcb244aUff2Cf2eya466047934cybq0rfX0pd31K85do3XcO3W49Cy3Rgn', 'password');
        $this->assertTrue($string === null);

        $string = Helper::cryptDecode('---', 'password');
        $this->assertTrue($string === null);
    }
}
