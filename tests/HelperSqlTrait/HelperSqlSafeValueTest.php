<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperSqlTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperSqlTrait::sqlSafeValue()
 */
final class HelperSqlSafeValueTest extends TestCase
{
    /**
     * Проверяет очистку опасных конструкций
     * @see \Atlcom\Traits\HelperSqlTrait::sqlSafeValue()
     *
     * @return void
     */
    #[Test]
    public function sqlSafeValueRemovesDangerousStatements(): void
    {
        $string = Helper::sqlSafeValue('John; DROP TABLE users; -- comment');
        $this->assertSame('John', $string);
    }


    /**
     * Проверяет удаление типичных булевых инъекций
     * @see \Atlcom\Traits\HelperSqlTrait::sqlSafeValue()
     *
     * @return void
     */
    #[Test]
    public function sqlSafeValueRemovesBooleanInjections(): void
    {
        $string = Helper::sqlSafeValue("'1' OR '1'='1'");
        $this->assertSame(addslashes("'1'"), $string);
    }


    /**
     * Проверяет обработку массивов и экранирование кавычек
     * @see \Atlcom\Traits\HelperSqlTrait::sqlSafeValue()
     *
     * @return void
     */
    #[Test]
    public function sqlSafeValueHandlesArrays(): void
    {
        $sanitized = Helper::sqlSafeValue(['name' => "O'Brian", 'age' => 30]);

        $this->assertSame(addslashes("O'Brian"), $sanitized['name']);
        $this->assertSame(30, $sanitized['age']);
    }
}
