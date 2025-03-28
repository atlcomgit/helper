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
final class HelperCaseSnakeTest extends TestCase
{
    #[Test]
    public function caseSnake(): void
    {
        $string = Helper::caseSnake('abc_def');
        $this->assertTrue($string === 'abc_def');

        $string = Helper::caseSnake('_abc_Def_');
        $this->assertTrue($string === '_abc_Def_');

        $string = Helper::caseSnake('AbcDef');
        $this->assertTrue($string === 'Abc_Def');

        $string = Helper::caseSnake('-abc-def-');
        $this->assertTrue($string === '_abc_def_');
        
        $string = Helper::caseSnake('абв-Где-еёж-123');
        $this->assertTrue($string === 'абв_Где_еёж_123');

    }
}
