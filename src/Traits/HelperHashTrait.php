<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Internal\HelperHash;
use InvalidArgumentException;

/**
 * Трейт для работы с хешем
 * @mixin \Atlcom\Helper
 */
trait HelperHashTrait
{
    /**
     * Кодирует числовой идентификатор в короткий токен
     * @see ../../tests/HelperHashTrait/HelperHashIdEncodeTest.php
     *
     * @param int|string|null $value
     * @param string|null $password
     * @return string
     */
    public static function hashIdEncode(int|string|null $value, ?string $password = null): string
    {
        if (is_null($value)) {
            return '';
        }

        if (is_string($value)) {
            $value = trim($value);

            if ($value === '' || preg_match('/^-?\d+$/', $value) !== 1) {
                throw new InvalidArgumentException('Значение идентификатора должно быть целым числом.');
            }

            $value = (int)$value;
        }

        if ($value < 0) {
            throw new InvalidArgumentException('Идентификатор должен быть неотрицательным.');
        }

        return HelperHash::makeIdToken($value, $password ?? '');
    }


    /**
     * Декодирует короткий токен в исходный идентификатор
     * @see ../../tests/HelperHashTrait/HelperHashIdDecodeTest.php
     *
     * @param string|null $token
     * @param string|null $password
     * @return int|null
     */
    public static function hashIdDecode(?string $token, ?string $password = null): ?int
    {
        if ($token === null) {
            return null;
        }

        $token = trim($token);
        if ($token === '') {
            return null;
        }

        return HelperHash::readIdToken($token, $password ?? '');
    }


    /**
     * Возвращает xxh128 хеш значения
     * @see ../../tests/HelperHashTrait/HelperHashXxh128Test.php
     *
     * @param mixed $value
     * @param string|null $prefix
     * @param string|null $suffix
     * @return string
     */
    public static function hashXxh128(mixed $value, ?string $prefix = null, ?string $suffix = null): string
    {
        is_scalar($value) ?: $value = json_encode($value);

        return hash('xxh128', (string)$prefix . $value . (string)$suffix);
    }
}
