<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperSqlTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperSqlTrait
 */
final class HelperSqlFieldsInsertTest extends TestCase
{
    #[Test]
    public function sqlFields(): void
    {
        $array = Helper::sqlFieldsInsert("insert into tests (name) values ('Пример')");
        $this->assertEquals(['name'], $array);

        $array = Helper::sqlFieldsInsert("insert into tests (name, id) values (?, ?)");
        $this->assertEquals(['name', 'id'], $array);

        $array = Helper::sqlFieldsInsert(null);
        $this->assertEquals([], $array);

    }
}
