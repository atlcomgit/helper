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


    /**
     * Проверяет обработку массивов и экранирование кавычек
     * @see \Atlcom\Traits\HelperSqlTrait::sqlSafeValue()
     *
     * @return void
     */
    #[Test]
    public function sqlSafeValueLong(): void
    {
        $sql = 'Импорт пользователей из AD через LDAP
            \'1\'
            "2"
            #0 Пользователь: Есипов Андрей Александрович,Email: a.esipov@dialog-auto.ru,Роли: dealer, Обновление отключено
            #1 Пользователь: Винников Андрей Леонидович,Email: A300727@expobank.ru,Блокировка отключена
            #2 Пользователь: Дудина Мария Григорьевна,Email: mariad@vedisoft.ru,Блокировка отключена
            #151 Пользователь: Михайлова Юлия Андреевна,Email: yuamikhaylova@maximum.auto,Роли: dealer, Обновление отключено
            #183 Пользователь: Бобкова Ирина Николаевна,Email: inbobkova@marten.ru,Роли: dealer, Обновление отключено
            #207 Пользователь: Лавров Павел Владимирович,Email: A300503@expobank.ru,Блокировка отключена
            #209 Пользователь: Реуцких Виктория Андреевна,Email: A300728@expobank.ru,Блокировка отключена
            #282 Пользователь: Курбанов Руслан Фарходович,Email: A300750@expobank.ru,Блокировка отключена

            Результат: Array
            (
                [Загружено] => 283
                [Пропущено] => 8
                [Добавлено] => 0
            )
        ';
        $sanitized = Helper::sqlSafeValue($sql);

        $this->assertSame(addslashes($sql), $sanitized);
    }
}
