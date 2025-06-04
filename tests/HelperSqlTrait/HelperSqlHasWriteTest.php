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
final class HelperSqlHasWriteTest extends TestCase
{
    #[Test]
    public function sqlHasWrite(): void
    {
        $boolean = Helper::sqlHasWrite('SELECT * FROM users');
        $this->assertTrue($boolean === false);

        $boolean = Helper::sqlHasWrite('DROP TABLE users;');
        $this->assertTrue($boolean === true);

        $boolean = Helper::sqlHasWrite('-- drop table users\nSELECT * FROM test;');
        $this->assertTrue($boolean === false);

        $boolean = Helper::sqlHasWrite('/* truncate users */ SELECT 1');
        $this->assertTrue($boolean === false);

        $boolean = Helper::sqlHasWrite('INSERT INTO log VALUES (\'x\'); SELECT 1;');
        $this->assertTrue($boolean === true);

        $boolean = Helper::sqlHasWrite('DROP DATABASE test_db;');
        $this->assertTrue($boolean === true);

        $boolean = Helper::sqlHasWrite('ALTER TABLE users ADD COLUMN age INT;');
        $this->assertTrue($boolean === true);

        $boolean = Helper::sqlHasWrite('/* alter table users */ SELECT 1');
        $this->assertTrue($boolean === false);

        $boolean = Helper::sqlHasWrite(null);
        $this->assertTrue($boolean === false);
    }
}
