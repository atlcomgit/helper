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
final class HelperColorRandomHexTest extends TestCase
{
    /**
     * Тестирует генерацию случайного HEX цвета
     * @see \Atlcom\Traits\HelperColorTrait::colorRandomHex()
     *
     * @return void
     */
    #[Test]
    public function colorRandomHex(): void
    {
        // Генерируем массив случайных HEX цветов
        $colors = array_map(static fn (): string => Helper::colorRandomHex(), range(1, 5));

        foreach ($colors as $color) {
            // Проверяем формат сгенерированного цвета
            $this->assertMatchesRegularExpression('/^#[0-9a-f]{6}$/', $color);
        }
    }
}
