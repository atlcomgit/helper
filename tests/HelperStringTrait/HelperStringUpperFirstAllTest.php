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
final class HelperStringUpperFirstAllTest extends TestCase
{
    #[Test]
    public function stringUpperFirstAll(): void
    {
        $string = Helper::stringUpperFirstAll('abc');
        $this->assertTrue($string === 'Abc');

        $string = Helper::stringUpperFirstAll('abc Def ghi-jkl_mno');
        $this->assertTrue($string === 'Abc Def Ghi-Jkl_Mno');

        $string = Helper::stringUpperFirstAll('абв Где еёж-зик_лмн');
        $this->assertTrue($string === 'Абв Где Еёж-Зик_Лмн');

        $string = Helper::stringUpperFirstAll(null);
        $this->assertTrue($string === '');
    }
}
