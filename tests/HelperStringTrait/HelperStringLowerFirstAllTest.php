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
final class HelperStringLowerFirstAllTest extends TestCase
{
    #[Test]
    public function stringLowerFirstAll(): void
    {
        $string = Helper::stringLowerFirstAll('Abc def Ghi-Jkl_Mno');
        $this->assertTrue($string === 'abc def ghi-jkl_mno');

        $string = Helper::stringLowerFirstAll('Абв где Еёж-Зик_Лмн');
        $this->assertTrue($string === 'абв где еёж-зик_лмн');

        $string = Helper::stringLowerFirstAll(null);
        $this->assertTrue($string === '');
    }
}
