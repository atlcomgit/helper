<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperArrayTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperArrayTrait
 */
final class HelperArrayGetTest extends TestCase
{
    #[Test]
    public function arrayGet(): void
    {
        $mixed = Helper::arrayGet(['a', 'b'], null);
        $this->assertTrue($mixed === null);

        $mixed = Helper::arrayGet(['a', 'b'], 0);
        $this->assertTrue($mixed === 'a');

        $mixed = Helper::arrayGet(['a' => 1, 'b' => 2], 'a');
        $this->assertTrue($mixed === 1);

        $mixed = Helper::arrayGet(['a' => 1, 'b' => 2], 'c');
        $this->assertTrue($mixed === null);

        $mixed = Helper::arrayGet(['a' => 1, 'b' => 2], 'c', 1);
        $this->assertTrue($mixed === 1);

        $mixed = Helper::arrayGet(['a' => 1, 'b' => 2, 'c' => null], 'c', 1);
        $this->assertTrue($mixed === null);

        $mixed = Helper::arrayGet(['a' => ['b' => null, 'c' => 1], 'b' => 2, 'c' => 4], 'a.b', 1);
        $this->assertTrue($mixed === null);

        $mixed = Helper::arrayGet(['a' => ['b' => 1], 'c' => null], 'a.c', 1);
        $this->assertTrue($mixed === 1);

        $mixed = Helper::arrayGet(['a.b' => 1, 'b' => 2], 'a.b');
        $this->assertTrue($mixed === 1);

        $mixed = Helper::arrayGet(['a' => ['b' => 2, 'c' => 3], 'b' => 4], 'a.b');
        $this->assertTrue($mixed === 2);

        $mixed = Helper::arrayGet(['a' => ['b' => 2, 'c' => 3], 'a.b' => 4], 'a.b');
        $this->assertTrue($mixed === 4);

        $mixed = Helper::arrayGet(['a' => ['b' => 2, 'c' => 3], 'a.b' => 4], 'a');
        $this->assertTrue($mixed === ['b' => 2, 'c' => 3]);

        $mixed = Helper::arrayGet([['a' => 1, 'b' => 2]], 'a');
        $this->assertTrue($mixed === null);

        $mixed = Helper::arrayGet([['a' => 1, 'b' => 2], ['a' => 3, 'b' => 4]], '0.a');
        $this->assertTrue($mixed === 1);

        $mixed = Helper::arrayGet([['a' => [['b' => 1]], 'c' => 2]], '0.a.0.b');
        $this->assertTrue($mixed === 1);

        $mixed = Helper::arrayGet(null, null);
        $this->assertTrue($mixed === null);
    }
}
