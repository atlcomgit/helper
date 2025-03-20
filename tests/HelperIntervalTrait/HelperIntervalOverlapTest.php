<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тесты трейта
 * @see \Atlcom\Traits\HelperIntervalTrait
 */
final class HelperIntervalOverlapTest extends TestCase
{
    #[Test]
    public function intervalOverlapTrue(): void
    {
        $array = Helper::intervalOverlap(1, 2, 3, 4, 2);
        $this->assertEquals([2], $array);

        $array = Helper::intervalOverlap([1, 3], [2, 4]);
        $this->assertEquals(['1..3', '2..4'], $array);

        $array = Helper::intervalOverlap([[1, 3], [2, 4]]);
        $this->assertEquals(['1..3', '2..4'], $array);

        $array = Helper::intervalOverlap([1, 3], [null, null]);
        $this->assertEquals(['1..3', '..'], $array);

        $array = Helper::intervalOverlap([1, 3], [4, 6], [null, null]);
        $this->assertEquals(['1..3', '..', '4..6'], $array);

        $array = Helper::intervalOverlap([[1, 3], [null, null]]);
        $this->assertEquals(['1..3', '..'], $array);

        $array = Helper::intervalOverlap([[1, 3], [4, 6], [null, null]]);
        $this->assertEquals(['1..3', '..', '4..6'], $array);

        $array = Helper::intervalOverlap('1..3', '2..4');
        $this->assertEquals(['1..3', '2..4'], $array);

        $array = Helper::intervalOverlap('2, 1..3');
        $this->assertEquals(['2', '1..3'], $array);

        $array = Helper::intervalOverlap('1..3, 2..4');
        $this->assertEquals(['1..3', '2..4'], $array);

        $array = Helper::intervalOverlap(['1..3', '2..4']);
        $this->assertEquals(['1..3', '2..4'], $array);

        $array = Helper::intervalOverlap(['1..3, 2..4']);
        $this->assertEquals(['1..3', '2..4'], $array);

        $array = Helper::intervalOverlap(['1.., ..4']);
        $this->assertEquals(['1..', '..4'], $array);

        $array = Helper::intervalOverlap('1.., ..');
        $this->assertEquals(['1..', '..'], $array);

        $array = Helper::intervalOverlap('.., ..');
        $this->assertEquals(['..'], $array);
    }


    #[Test]
    public function intervalOverlapFalse(): void
    {
        $array = Helper::intervalOverlap(1, 2, 3, 4);
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap([1, 3], [4, 6]);
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap([[1, 3], [4, 6]]);
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap('1..3', '4..6');
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap('2, 4..6');
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap('1..3, 4..6');
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap(['1..3', '4..6']);
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap(['1..3']);
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap(['1..3, 4..6']);
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap(['..3, 4..']);
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap([null, null]);
        $this->assertEquals([], $array);

        $array = Helper::intervalOverlap('..');
        $this->assertEquals([], $array);
    }
}
