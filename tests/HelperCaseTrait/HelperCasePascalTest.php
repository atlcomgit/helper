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
final class HelperCasePascalTest extends TestCase
{
    #[Test]
    public function casePascal(): void
    {
        $string = Helper::casePascal('abcDef');
        $this->assertTrue($string === 'AbcDef');

        $string = Helper::casePascal('abc-def');
        $this->assertTrue($string === 'AbcDef');

        $string = Helper::casePascal('AbcDef');
        $this->assertTrue($string === 'AbcDef');

        $string = Helper::casePascal('abc_def');
        $this->assertTrue($string === 'AbcDef');

        $string = Helper::casePascal('абв_Где_еёж_123');
        $this->assertTrue($string === 'АбвГдеЕёж123');

    }
}