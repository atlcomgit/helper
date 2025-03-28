<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperStringTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperStringTrait
 */
final class HelperStringLowerFirstTest extends TestCase
{
    #[Test]
    public function stringLowerFirst(): void
    {
        $string = Helper::stringLowerFirst('Abc Def ghi');
        $this->assertTrue($string === 'abc Def ghi');

        $string = Helper::stringLowerFirst('Абв Где еёж');
        $this->assertTrue($string === 'абв Где еёж');
    }
}
