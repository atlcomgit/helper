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
     * @param int|null $minLength
     * @param string|null $alphabet
     * @return string
     */
    public static function hashIdEncode(
        int|string|null $value,
        ?string $password = null,
        ?int $minLength = null,
        ?string $alphabet = null,
    ): string {
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

        $minLength ??= 3;
        if ($minLength < 0) {
            throw new InvalidArgumentException('Минимальная длина не может быть отрицательной.');
        }

        $alphabet = $alphabet !== null ? trim($alphabet) : null;
        if ($alphabet !== null && $alphabet === '') {
            throw new InvalidArgumentException('Алфавит не может быть пустым.');
        }

        return HelperHash::makeIdToken($value, $password ?? '', $minLength, $alphabet);
    }


    /**
     * Декодирует короткий токен в исходный идентификатор
     * @see ../../tests/HelperHashTrait/HelperHashIdDecodeTest.php
     *
     * @param string|null $value
     * @param string|null $password
     * @param int|null $minLength
     * @param string|null $alphabet
     * @return int|null
     */
    public static function hashIdDecode(
        ?string $value,
        ?string $password = null,
        ?int $minLength = null,
        ?string $alphabet = null,
    ): ?int {
        if ($value === null) {
            return null;
        }

        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $minLength ??= 3;
        if ($minLength < 0) {
            throw new InvalidArgumentException('Минимальная длина не может быть отрицательной.');
        }

        $alphabet = $alphabet !== null ? trim($alphabet) : null;
        if ($alphabet !== null && $alphabet === '') {
            throw new InvalidArgumentException('Алфавит не может быть пустым.');
        }

        return HelperHash::readIdToken($value, $password ?? '', $minLength, $alphabet);
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
