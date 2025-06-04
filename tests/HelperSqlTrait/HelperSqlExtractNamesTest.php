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
final class HelperSqlExtractNamesTest extends TestCase
{
    #[Test]
    public function sqlExtractNames(): void
    {
        $array = Helper::sqlExtractNames("
            SELECT ta.*, tb.id -- SELECT tc.*
            FROM `database1`.`table_a` as ta
                LEFT JOIN [database1].[table_b] tb ON `tb`.`user_id` = ta.id AND tb.status = 'success'
                LEFT JOIN database2.table_c AS tc ON tc.role_id = ta.id
                FULL JOIN table_d AS td ON td.id = tc.post_id
                /*
                    комментарий
                */
            WHERE ta.id = '335938dd-45c0-4356-9e4d-dbf43a91c9df'
                AND EXISTS(SELECT * FROM table_e WHERE table_e.id = ta.error_id ORDER BY data DESC LIMIT 1)
                AND tb.active = \"0\"
                AND tc.deleted_at IS NULL
                AND LOWER(td.phone) = 'abc'
            ORDER BY td.name
            HAVING count(td.id) > 1
        ");
        $this->assertEquals(
            [
                'databases' => [
                    'database1' => [
                        'tables' => [
                            'table_a' => [
                                'fields' => ['*', 'error_id', 'id'],
                            ],
                            'table_b' => [
                                'fields' => ['active', 'id', 'status', 'user_id'],
                            ],
                            'table_d' => [
                                'fields' => ['id', 'name', 'phone'],
                            ],
                            'table_e' => [
                                'fields' => ['*', 'data', 'id'],
                            ],
                        ],
                    ],
                    'database2' => [
                        'tables' => [
                            'table_c' => [
                                'fields' => ['deleted_at', 'post_id', 'role_id'],
                            ],
                        ],
                    ],
                ],
            ],
            $array,
        );

        $array = Helper::sqlExtractNames('SELECT u.name FROM users u WHERE id = 1 AND u.active = 0');
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'users' => ['fields' => ['active', 'id', 'name']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames("
            INSERT INTO table_a (id, name, phone) VALUES ('info', 'test', NOW());
            INSERT INTO database2.table_b (level, message, created_at) VALUES ('info', 'test', NOW());
        ");
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'table_a' => ['fields' => ['id', 'name', 'phone']],
                    ],
                ],
                'database2' => [
                    'tables' => [
                        'table_b' => ['fields' => ['created_at', 'level', 'message']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames("
            UPDATE table_a SET level=1, message = 'text' WHERE id IN NOT NULL OR deleted_at IS NULL;
            UPDATE database2.table_b SET table_b.xxx=1, table_b.yyy = 'text' WHERE table_b.zzz IN NOT NULL OR table_b.www IS NULL;
        ");
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'table_a' => ['fields' => ['id', 'deleted_at', 'level', 'message']],
                    ],
                ],
                'database2' => [
                    'tables' => [
                        'table_b' => ['fields' => ['www', 'xxx', 'yyy', 'zzz']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames("
            DELETE FROM table_a WHERE level=1 AND message = 'text' OR id IN IN (1,2,3);
            DELETE FROM database2.table_b WHERE table_b.xxx=1 AND table_b.yyy = 'text' OR table_b.zzz IN NOT NULL OR table_b.www IS NULL;
        ");
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'table_a' => ['fields' => ['id', 'level', 'message']],
                    ],
                ],
                'database2' => [
                    'tables' => [
                        'table_b' => ['fields' => ['www', 'xxx', 'yyy', 'zzz']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames('/*select name from logs*/ select * FROM users WHERE id = 1 AND (active = 0 OR active = 1)');
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'users' => ['fields' => ['*', 'id', 'active']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlFields("--SELECT name\nSELECT * FROM users WHERE id = 1 AND active = '0' OR status IN (1)");
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'users' => ['fields' => ['*', 'id', 'active']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlFields("SELECT u.*, p.* FROM users as u LEFT JOIN posts p ON p.user_id = u.id WHERE u.id = '335938dd-45c0-4356-9e4d-dbf43a91c9df' AND p.active = 0");
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'users' => ['fields' => ['*', 'id']],
                        'posts' => ['fields' => ['*', 'user_id', 'active']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlFields("TRUNCATE users");
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'users' => [
                            'fields' => [],
                        ],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlFields("DROP DATABASE core");
        $this->assertEquals([
            'databases' => [
                'core' => [
                    'tables' => [],
                ],
            ],
        ], $array);

        $array = Helper::sqlFields(null);
        $this->assertEquals([], $array);

    }
}
