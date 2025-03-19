<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с массивами
 */
trait HelperIpTrait
{
    /**
     * Проверка ip на вхождение в подсети
     *
     * @param string $value
     * @param array $rangeMaskIp
     * @return bool
     */
    public static function ipInRange(string $value, array $rangeMaskIp): bool
    {
        foreach ($rangeMaskIp as $maskIp) {
            // Получаем массив, состоящий из IP и номера маски
            $maskIpRange = explode('/', trim($maskIp, ' '));

            if (isset($maskIpRange[1])) {
                // Элемент массива [0] является начальным IP (сеть)
                // Конвертируем строку, содержащую IPv4-адрес в целое число
                $rangeStart = ip2long(trim($maskIpRange[0]));

                // Выделяем число адресов в диапазоне как 2^(32-номер_маски)
                // Делаем -1, иначе захватываем широковещательный канал
                $rangeEnd = $rangeStart + pow(2, 32 - intval($maskIpRange[1])) - 1;
                $ipLong = ip2long($value);

                if ($ipLong >= $rangeStart && $ipLong <= $rangeEnd) {
                    return true;
                }
            } elseif ($value == trim($maskIpRange[0])) {
                return true;
            } elseif (trim($maskIpRange[0]) === '*') {
                return true;
            }
        }

        return false;
    }
}
