<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCaseTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperCaseTrait
 */
final class HelperCaseCamelTest extends TestCase
{
    #[Test]
    public function caseCamel(): void
    {
        $string = Helper::caseCamel('abc-def');
        $this->assertTrue($string === 'abcDef');

        $string = Helper::caseCamel('AbcDef');
        $this->assertTrue($string === 'abcDef');

        $string = Helper::caseCamel('abc_def');
        $this->assertTrue($string === 'abcDef');

        $string = Helper::caseCamel('абв_где_123');
        $this->assertEquals($string, 'абвГде123');
        $this->assertTrue($string === 'абвГде123');

    }
}