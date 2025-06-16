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
final class HelperSqlTablesTest extends TestCase
{
    #[Test]
    public function sqlHasWrite(): void
    {
        $array = Helper::sqlTables("insert into tests (name, created_at) values ('Абв', '') returning id");
        $this->assertSame(['tests'], $array);

        $array = Helper::sqlTables('insert into users (name) values ("test");');
        $this->assertSame(['users'], $array);

        $array = Helper::sqlTables('SELECT * FROM users;');
        $this->assertSame(['users'], $array);

        $array = Helper::sqlTables('UPDATE users SET a = 1;');
        $this->assertSame(['users'], $array);

        $array = Helper::sqlTables('DELETE FROM users;');
        $this->assertSame(['users'], $array);

        $array = Helper::sqlTables('DROP TABLE users;');
        $this->assertSame(['users'], $array);

        $array = Helper::sqlTables("-- drop table users\nSELECT * FROM test;");
        $this->assertSame(['test'], $array);

        $array = Helper::sqlTables('/* truncate users */ SELECT 1');
        $this->assertSame([], $array);

        $array = Helper::sqlTables('INSERT INTO log VALUES (\'x\'); SELECT 1;');
        $this->assertSame(['log'], $array);

        $array = Helper::sqlTables('DROP DATABASE test_db;');
        $this->assertSame([], $array);

        $array = Helper::sqlTables('ALTER TABLE users ADD COLUMN age INT;');
        $this->assertSame(['users'], $array);

        $array = Helper::sqlTables('/* alter table users */ SELECT 1');
        $this->assertSame([], $array);

        $array = Helper::sqlTables(null);
        $this->assertSame([], $array);
    }
}
