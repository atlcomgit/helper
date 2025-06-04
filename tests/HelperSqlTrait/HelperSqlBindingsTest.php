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
final class HelperSqlBindingsTest extends TestCase
{
    #[Test]
    public function sqlBindings(): void
    {
        $string = Helper::sqlBindings('SELECT * FROM users WHERE id = ? AND active = ?', [42, true]);
        $this->assertSame('SELECT * FROM users WHERE id = 42 AND active = 1', $string);

        $string = Helper::sqlBindings(null);
        $this->assertSame('', $string);

        $this->assertSame(
            "INSERT INTO t VALUES (42, 3.14, 'foo', 1, NULL)",
            Helper::sqlBindings('INSERT INTO t VALUES (?, ?, ?, ?, ?)', [42, 3.14, 'foo', true, null]),
        );

        $this->assertSame(
            'UPDATE t SET a = 1, b = NULL WHERE c = NULL',
            Helper::sqlBindings('UPDATE t SET a = ?, b = ? WHERE c = ?', [1]),
        );

        $this->assertSame(
            'SELECT * FROM t WHERE a = 1',
            Helper::sqlBindings('SELECT * FROM t WHERE a = ?', [1, 2, 3]),
        );

        $this->assertSame(
            'SELECT NULL',
            Helper::sqlBindings('SELECT ?', []),
        );

        $this->assertSame(
            "SELECT * FROM t WHERE s = 'foo\\\"bar'",
            Helper::sqlBindings("SELECT * FROM t WHERE s = ?", ['foo"bar']),
        );

        $this->assertSame(
            "SELECT * FROM t WHERE s = 'foo\'bar'",
            Helper::sqlBindings("SELECT * FROM t WHERE s = ?", ['foo\'bar']),
        );

        $this->assertSame(
            "SELECT * FROM t WHERE s = 'a\nb'",
            Helper::sqlBindings('SELECT * FROM t WHERE s = ?', ["a\nb"]),
        );

        $this->assertSame('', Helper::sqlBindings(null, []));
    }
}
