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
        $string = Helper::stringUpperFirst('abc def');
        $this->assertTrue($string === 'Abc def');

        $string = Helper::stringUpperFirst('абв где');
        $this->assertTrue($string === 'Абв где');
    }
}
