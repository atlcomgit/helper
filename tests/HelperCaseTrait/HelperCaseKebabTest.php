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
final class HelperCaseKebabTest extends TestCase
{
    #[Test]
    public function caseKebab(): void
    {
        $string = Helper::caseKebab('abc-def');
        $this->assertTrue($string === 'abc-def');

        $string = Helper::caseKebab('-abc-Def-');
        $this->assertTrue($string === 'abc-Def-');

        $string = Helper::caseKebab('AbcDef');
        $this->assertTrue($string === 'Abc-Def');

        $string = Helper::caseKebab('_abc_def_');
        $this->assertTrue($string === 'abc-def-');

        $string = Helper::caseKebab('абв_Где_еёж_123');
        $this->assertTrue($string === 'абв-Где-еёж-123');

    }
}
