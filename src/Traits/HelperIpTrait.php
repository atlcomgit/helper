<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с массивами
 * @mixin \Atlcom\Helper
 */
trait HelperIpTrait
{
    /**
     * Возвращает массив найденных масок подсетей, в которые входит ip адрес
     * @see ../../tests/HelperIpTrait/HelperIpInRangeTest.php
     *
     * @param string|null $value
     * @param mixed ...$masks
     * @return array
     */
    public static function ipInRange(?string $value, mixed ...$masks): array
    {
        $result = [];

        foreach ($masks as $mask) {

            if (is_null($mask)) {
                continue;

            } else if (is_array($mask) || is_object($mask)) {
                $result = [...$result, ...static::ipInRange($value, ...(array)$mask)];

            } else {
                $mask = (string)$mask;

                // Получаем массив, состоящий из IP и номера маски
                $maskExplode = explode('/', trim($mask, ' '));

                if (isset($maskExplode[1])) {
                    // Элемент массива [0] является начальным IP (сеть)
                    // Конвертируем строку, содержащую IPv4-адрес в целое число
                    $rangeStart = ip2long(trim($maskExplode[0]));

                    // Выделяем число адресов в диапазоне как 2^(32-номер_маски)
                    // Делаем -1, иначе захватываем широковещательный канал
                    $rangeEnd = $rangeStart + pow(2, 32 - intval($maskExplode[1])) - 1;
                    $ipLong = ip2long($value);

                    if ($ipLong >= $rangeStart && $ipLong <= $rangeEnd) {
                        $result[] = $mask;
                    }

                } else if ($value === trim($maskExplode[0])) {
                    $result[] = $mask;

                } else if (trim($maskExplode[0]) === '*') {
                    $result[] = $mask;
                }
            }
        }

        return $result;
    }
}
