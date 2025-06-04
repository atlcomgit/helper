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
final class HelperSqlFieldsTest extends TestCase
{
    #[Test]
    public function sqlFields(): void
    {
        $array = Helper::sqlFields('SELECT * FROM users WHERE id = 1 AND active = 0');
        $this->assertEquals(['users.*', 'users.id', 'users.active'], $array);

        $array = Helper::sqlFields('SELECT u.name FROM users u WHERE id = 1 AND u.active = 0');
        $this->assertEquals(['users.name', 'users.id', 'users.active'], $array);

        $array = Helper::sqlFields("INSERT INTO logs (level, message, created_at) VALUES ('info', 'test', NOW());");
        $this->assertEquals(['logs.level', 'logs.message', 'logs.created_at'], $array);

        $array = Helper::sqlFields('/*select name from logs*/ select * FROM users WHERE id = 1 AND active = 0');
        $this->assertEquals(['users.*', 'users.id', 'users.active'], $array);

        $array = Helper::sqlFields("--SELECT name\nSELECT * FROM users WHERE id = 1 AND active = '0'");
        $this->assertEquals(['users.*', 'users.id', 'users.active'], $array);

        $array = Helper::sqlFields("SELECT u.*, p.* FROM users as u LEFT JOIN posts p ON p.user_id = u.id WHERE u.id = '335938dd-45c0-4356-9e4d-dbf43a91c9df' AND p.active = 0");
        $this->assertEquals([
            'users.*',
            'users.id',
            'posts.*',
            'posts.user_id',
            'posts.active',
        ], $array);

        $array = Helper::sqlFields("TRUNCATE users");
        $this->assertEquals(['users' => []], $array);

        $array = Helper::sqlFields("DROP DATABASE core");
        $this->assertEquals([], $array);

        $array = Helper::sqlFields(null);
        $this->assertEquals([], $array);

    }
}
