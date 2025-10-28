<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperColorTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperColorTrait
 */
final class HelperColorRandomRgbTest extends TestCase
{
    /**
     * Тестирует генерацию случайного RGB цвета
     * @see \Atlcom\Traits\HelperColorTrait::colorRandomRgb()
     *
     * @return void
     */
    #[Test]
    public function colorRandomRgb(): void
    {
        // Генерируем массив случайных RGB цветов
        $colors = array_map(static fn (): array => Helper::colorRandomRgb(), range(1, 5));

        foreach ($colors as $color) {
            // Проверяем структуру и диапазон значений RGB цвета
            $this->assertArrayHasKey('r', $color);
            $this->assertArrayHasKey('g', $color);
            $this->assertArrayHasKey('b', $color);
            $this->assertArrayHasKey('a', $color);

            $this->assertIsInt($color['r']);
            $this->assertIsInt($color['g']);
            $this->assertIsInt($color['b']);

            $this->assertGreaterThanOrEqual(0, $color['r']);
            $this->assertGreaterThanOrEqual(0, $color['g']);
            $this->assertGreaterThanOrEqual(0, $color['b']);

            $this->assertLessThanOrEqual(255, $color['r']);
            $this->assertLessThanOrEqual(255, $color['g']);
            $this->assertLessThanOrEqual(255, $color['b']);

            $this->assertNull($color['a']);
        }
    }
}
