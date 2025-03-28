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
final class HelperStringUpperFirstTest extends TestCase
{
    #[Test]
    public function stringUpperFirst(): void
    {
        $string = Helper::stringUpperFirst('abc');
        $this->assertTrue($string === 'Abc');

        $string = Helper::stringUpperFirst('abc Def ghi');
        $this->assertTrue($string === 'Abc Def ghi');

        $string = Helper::stringUpperFirst('абв Где еёж');
        $this->assertTrue($string === 'Абв Где еёж');
    }
}