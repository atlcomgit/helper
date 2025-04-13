<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с цветами
 */
trait HelperColorTrait
{
    /**
     * Возвращает массив с RGB цветом из цвета HEX строки
     * @see ../../tests/HelperColorTrait/HelperColorHexToRgbTest.php
     *
     * @param string $value
     * @return array
     */
    public static function colorHexToRgb(string $value): array
    {
        $value = trim($value, '#');

        $rgba = match (static::stringLength($value)) {
            3 => sscanf('#' . trim($value, '#'), "#%1x%1x%1x"),
            6 => sscanf('#' . trim($value, '#'), "#%02x%02x%02x"),
            8 => sscanf('#' . trim($value, '#'), "#%02x%02x%02x%02x"),

            default => [],
        };

        return ($rgba && [$r, $g, $b, $a] = $rgba) ? ['r' => $r, 'g' => $g, 'b' => $b, 'a' => $a] : [];
    }


    /**
     * Возвращает HEX строку из RGB цвета
     * @see ../../tests/HelperColorTrait/HelperColorRgbToHexTest.php
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param int $alpha
     * @return string
     */
    public static function colorRgbToHex(int $red, int $green, int $blue, int $alpha = null): string
    {
        return is_null($alpha)
            ? sprintf("#%02x%02x%02x", $red, $green, $blue)
            : sprintf("#%02x%02x%02x%02x", $red, $green, $blue, $alpha);
    }
}
