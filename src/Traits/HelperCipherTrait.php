<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Internal\HelperCipher;

/**
 * Трейт для безопасного шифрования данных типа mixed
 * @mixin \Atlcom\Helper
 */
trait HelperCipherTrait
{
    /**
     * Шифрует значение с помощью безопасного алгоритма
     * @see ../../tests/HelperCipherTrait/HelperCipherEncodeTest.php
     *
     * @param mixed $value
     * @param string|null $password
     * @return string
     */
    public static function cipherEncode(mixed $value, ?string $password = null): string
    {
        return HelperCipher::encode($value, $password);
    }


    /**
     * Дешифрует значение с помощью безопасного алгоритма
     * @see ../../tests/HelperCipherTrait/HelperCipherDecodeTest.php
     *
     * @param string|null $value
     * @param string|null $password
     * @return mixed
     */
    public static function cipherDecode(?string $value, ?string $password = null): mixed
    {
        return HelperCipher::decode($value, $password);
    }
}
