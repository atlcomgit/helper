<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с размерами
 * @mixin \Atlcom\Helper
 */
trait HelperSizeTrait
{
    /**
     * Возвращает размер в байтах как строку
     * @see ../../tests/HelperSizeTrait/HelperSizeBytesToStringTest.php
     *
     * @param int|float|string|null $bytes
     * @return string
     */
    public static function sizeBytesToString(int|float|string|null $value): string
    {
        $value = (int)$value;
        $units = ['байт', 'Кб', 'Мб', 'Гб', 'Тб', 'Пб'];

        for ($i = 0; $value >= 1000; $i++) {
            $value /= 1000;
        }

        return round($value, 2) . ' ' . $units[$i];
    }


    /**
     * Возвращает размер в байтах из строки размера
     * @see ../../tests/HelperSizeTrait/HelperSizeStringToBytesTest.php
     *
     * @param string|null $size
     * @return int
     */
    public static function sizeStringToBytes(?string $value): int
    {
        $value = (string)$value;
        $exp = match (static::stringUpper(preg_replace('/[^a-zа-яё]/ui', '', $value))) {
            'ПБ', 'PB', 'PIB', 'ПБАЙТ', 'ПЕТАБАЙТ' => pow(1000, 5),
            'ТБ', 'TB', 'TIB', 'ТБАЙТ', 'ТЕРАБАЙТ' => pow(1000, 4),
            'ГБ', 'GB', 'GIB', 'ГБАЙТ', 'ГИГАБАЙТ' => pow(1000, 3),
            'МБ', 'MB', 'MIB', 'МБАЙТ', 'МЕГАБАЙТ' => pow(1000, 2),
            'КБ', 'KB', 'KIB', 'КБАЙТ', 'КИЛОБАЙТ' => pow(1000, 1),
            'Б', 'B', 'БАЙТ', 'BYTES' => pow(1000, 0),

            default => 0,
        };

        $size = (int)preg_replace('/[^0-9]/ui', '', $value);

        return (int)($size * $exp);
    }
}
