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
final class HelperSqlFieldsUpdateTest extends TestCase
{
    #[Test]
    public function sqlFields(): void
    {
        $array = Helper::sqlFieldsUpdate("update tests set name='1', id=2");
        $this->assertEquals(['name', 'id'], $array);

        $array = Helper::sqlFieldsUpdate("update `tests` set `name`= ?, id= ?");
        $this->assertEquals(['name', 'id'], $array);

        $array = Helper::sqlFieldsUpdate(null);
        $this->assertEquals([], $array);

    }
}
