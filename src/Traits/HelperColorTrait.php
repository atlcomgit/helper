<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с цветами
 * @mixin \Atlcom\Helper
 */
trait HelperColorTrait
{
    /**
     * Возвращает случайный HEX цвет в формате #rrggbb
     * @see ../../tests/HelperColorTrait/HelperColorRandomHexTest.php
     *
     * @return string
     */
    public static function colorRandomHex(): string
    {
        $value = random_int(0, 0xFFFFFF);

        return sprintf('#%06x', $value);
    }


    /**
     * Возвращает случайный RGB цвет в формате массива
     * @see ../../tests/HelperColorTrait/HelperColorRandomRgbTest.php
     *
     * @return array
     */
    public static function colorRandomRgb(): array
    {
        $color = [
            'r' => random_int(0, 255),
            'g' => random_int(0, 255),
            'b' => random_int(0, 255),
            'a' => null,
        ];

        return $color;
    }


    /**
     * Возвращает массив с RGB цветом из цвета HEX строки
     * @see ../../tests/HelperColorTrait/HelperColorHexToRgbTest.php
     *
     * @param string|null $value
     * @return array
     */
    public static function colorHexToRgb(?string $value): array
    {
        $value = trim($value ?? '', '#');

        $rgba = match (static::stringLength($value)) {
            3 => sscanf('#' . trim($value, '#'), "#%1x%1x%1x"),
            6 => sscanf('#' . trim($value, '#'), "#%02x%02x%02x"),
            8 => sscanf('#' . trim($value, '#'), "#%02x%02x%02x%02x"),

            default => [],
        };

        count($rgba) !== 3 ?: $rgba = array_pad($rgba, 4, null);

        return ($rgba && [$r, $g, $b, $a] = $rgba) ? ['r' => $r, 'g' => $g, 'b' => $b, 'a' => $a] : [];
    }


    /**
     * Возвращает HEX строку из RGB цвета
     * @see ../../tests/HelperColorTrait/HelperColorRgbToHexTest.php
     *
     * @param int|null $red
     * @param int|null $green
     * @param int|null $blue
     * @param int|null $alpha
     * @return string
     */
    public static function colorRgbToHex(?int $red, ?int $green, ?int $blue, ?int $alpha = null): string
    {
        return is_null($alpha)
            ? sprintf("#%02x%02x%02x", $red, $green, $blue)
            : sprintf("#%02x%02x%02x%02x", $red, $green, $blue, $alpha);
    }
}
