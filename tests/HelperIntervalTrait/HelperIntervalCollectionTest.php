<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperIntervalTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperIntervalTrait
 */
final class HelperIntervalCollectionTest extends TestCase
{
    #[Test]
    public function intervalCollection(): void
    {
        $array = Helper::intervalCollection(1, 3);
        $this->assertEquals([
            ['equal' => 1],
            ['equal' => 3],
        ], $array);

        $array = Helper::intervalCollection([1, 3], 5);
        $this->assertEquals([
            ['min' => 1, 'max' => 3],
            ['equal' => 5],
        ], $array);

        $array = Helper::intervalCollection([1, 3, 5]);
        $this->assertEquals([
            ['equal' => 1],
            ['equal' => 3],
            ['equal' => 5],
        ], $array);

        $array = Helper::intervalCollection('1..3');
        $this->assertEquals([
            ['min' => '1', 'max' => '3'],
        ], $array);

        $array = Helper::intervalCollection('1..');
        $this->assertEquals([
            ['min' => '1'],
        ], $array);

        $array = Helper::intervalCollection('..1');
        $this->assertEquals([
            ['max' => '1'],
        ], $array);

        $array = Helper::intervalCollection('..');
        $this->assertEquals([
            [],
        ], $array);

        $array = Helper::intervalCollection('1..3, 5', 7, [9], [10, 11], [12, 13, 14], [100, null]);
        $this->assertEquals([
            ['min' => '1', 'max' => '3'],
            ['equal' => '5'],
            ['equal' => '7'],
            ['equal' => '9'],
            ['min' => '10', 'max' => '11'],
            ['equal' => '12'],
            ['equal' => '13'],
            ['equal' => '14'],
            ['min' => '100', 'max' => null],
        ], $array);
    }
}