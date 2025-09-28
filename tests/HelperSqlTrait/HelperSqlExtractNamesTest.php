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
        $array = Helper::sqlExtractNames('select * from "tests" where "tests"."deleted_at" is null limit 1');
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'tests' => ['fields' => ['*', 'deleted_at']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames('insert into "tests" ("name", "updated_at", "created_at") values (?, ?, ?) returning "id"');
        $this->assertSame([
            'databases' => [
                '' => [
                    'tables' => [
                        'tests' => [
                            'fields' => ['created_at', 'name', 'updated_at'],
                        ],
                    ],
                ],
            ],
        ], $array);

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
                                'fields' => ['*', 'data', 'error_id', 'id'],
                            ],
                            'table_b' => [
                                'fields' => ['active', 'id', 'status', 'user_id'],
                            ],
                            'table_d' => [
                                'fields' => ['id', 'name', 'phone'],
                            ],
                            'table_e' => [
                                'fields' => ['*', 'id'],
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
                        'table_a' => ['fields' => ['deleted_at', 'id', 'level', 'message']],
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
                        'users' => ['fields' => ['*', 'active', 'id']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames("--SELECT name\nSELECT * FROM users WHERE id = 1 AND active = '0' OR status IN (1)");
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'users' => ['fields' => ['*', 'active', 'id', 'status']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames("SELECT u.*, p.* FROM users as u LEFT JOIN posts p ON p.user_id = u.id WHERE u.id = '335938dd-45c0-4356-9e4d-dbf43a91c9df' AND p.active = 0");
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'users' => ['fields' => ['*', 'id']],
                        'posts' => ['fields' => ['*', 'active', 'user_id']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames("TRUNCATE users");
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

        $array = Helper::sqlExtractNames("DROP DATABASE core");
        $this->assertEquals([
            'databases' => [
                'core' => [
                    'tables' => [
                        '' => ['fields' => []],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames("SELECT name FROM users");
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'users' => ['fields' => ['name']],
                    ],
                ],
            ],
        ], $array);

        $array = Helper::sqlExtractNames(null);
        $this->assertEquals(['databases' => []], $array);
    }


    #[Test]
    public function sqlExtractNamesTables(): void
    {
        $array = Helper::sqlExtractNames('select "warehouses"."id", "warehouses"."group_id", "warehouses"."time_zone_id", "warehouses"."country_id", "warehouses"."city_id", "warehouses"."restriction_id", "warehouses"."name", "warehouses"."description", "warehouses"."address", "warehouses"."latitude", "warehouses"."longitude", "warehouses"."timetable", "warehouses"."type", "warehouses"."status", "warehouses"."opening_date_at", "warehouses"."address_en", "warehouses"."_min_price", (select count(*) from "slots" where "warehouses"."id" = "slots"."warehouse_id" and "moderation_status" = \'ACCEPTED\' and "status" in (\'FOR_RENT\', \'GUEST\') and (not exists (select * from "orders" where "slots"."id" = "orders"."product_id" and "orders"."product_type" = \'SLOTS\' and "status" in (\'CONFIRMATION\', \'STORED\', \'REVIEWED\', \'BLOCKED\', \'FORCED_OPENING\') and "orders"."deleted_at" is null) or exists (select * from "orders" where "slots"."id" = "orders"."product_id" and "status" = \'STORED\' and "type" = \'GUEST\' and "product_type" = \'SLOTS\' and "status" in (\'BOOKED\', \'PRESALE\', \'CONFIRMATION\', \'RESERVED\', \'STORED\', \'REVIEWED\', \'BLOCKED\', \'FORCED_OPENING\') and "orders"."deleted_at" is null)) and "slots"."deleted_at" is null) as "free_or_guest_slots_count" from "warehouses" where "warehouses"."type" in (\'STORAGE\', \'LUGGAGE_STORAGE\', \'LEAD_STORAGE\') and "warehouses"."active" = 1 and "warehouses"."moderation_status" = \'ACCEPTED\' and "warehouses"."status" in (\'CONSTRUCTION\', \'PRESALE\', \'ACTIVE\') and "warehouses"."deleted_at" is null order by free_or_guest_slots_count desc, "name" asc');
        $this->assertEquals([
            'databases' => [
                '' => [
                    'tables' => [
                        'slots' => [
                            'fields' => [
                                '*',
                                'warehouse_id',
                                'moderation_status',
                                'status',
                                'id',
                                'deleted_at',
                                'free_or_guest_slots_count',
                                'name',
                            ],
                        ],
                        'warehouses' => [
                            'fields' => [
                                'id',
                                'group_id',
                                'time_zone_id',
                                'country_id',
                                'city_id',
                                'restriction_id',
                                'name',
                                'description',
                                'address',
                                'latitude',
                                'longitude',
                                'timetable',
                                'type',
                                'status',
                                'opening_date_at',
                                'address_en',
                                '_min_price',
                                'moderation_status',
                                'deleted_at',

                            ],
                        ],
                        'orders' => [
                            'fields' => [
                                'product_id',
                                'product_type',
                                'deleted_at',
                                'orders',
                                'type',
                                'status',
                            ],
                        ],
                    ],
                ],
            ],
        ], $array);
    }
}
