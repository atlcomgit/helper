<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с размерами
 */
trait HelperSizeTrait
{
    /**
     * Конвертирует размер файла в строку
     * @see ./tests/HelperSizeTrait/HelperSizeBytesToStringTest.php
     *
     * @param int $bytes
     * @return string
     */
    //?!? 
    public static function sizeBytesToString(int $value): string
    {
        $units = ['байт', 'Кб', 'Мб', 'Гб', 'Тб', 'Пб'];

        for ($i = 0; $value > 1000; $i++) {
            $bytes /= 1000;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }


    /**
     * Конвертирует строку в размер файла
     * @see ./tests/HelperSizeTrait/HelperSizeStringToBytesTest.php
     *
     * @param string $size
     * @return int
     */
    //?!? 
    public static function sizeStringToBytes(string $value): int
    {
        $exp = match (static::stringUpper(preg_replace('/[^a-zа-яё]/ui', '', $value))) {
            'Б', 'B' => pow(1000, 0),
            'КБ', 'KB', 'KIB' => pow(1000, 1),
            'МБ', 'MB', 'MIB' => pow(1000, 2),
            'ГБ', 'GB', 'GIB' => pow(1000, 3),
            'ТБ', 'TB', 'TIB' => pow(1000, 4),
            'ПБ', 'PB', 'PIB' => pow(1000, 5),
            default => 1,
        };

        $size = (int)preg_replace('/[^0-9]/ui', '', $value);

        return (int)($size * $exp);
    }
}
