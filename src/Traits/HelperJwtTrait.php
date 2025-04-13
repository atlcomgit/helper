<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с jwt токеном
 */
trait HelperJwtTrait
{
    /**
     * Возвращает массив с данными из декодированного jwt токена
     * @see ../../tests/HelperJwtTrait/HelperJwtDecodeTest.php
     *
     * @param string $value
     * @param string|null $signKey
     * @return array|null
     */
    public static function jwtDecode(
        string $value,
        ?string $signKey = null,
    ): ?array {
        $token = explode('.', $value);

        if (count($token) !== 3) {
            return null;
        }

        [$base64Header, $base64Payload, $base64Signature] = $token;

        $header = json_decode(base64_decode(strtr($base64Header, '-_', '+/')), true);
        $payload = json_decode(base64_decode(strtr($base64Payload, '-_', '+/')), true);
        $signature = base64_decode(strtr($base64Signature, '-_', '+/'));

        if ($header === null || $payload === null || ($header['typ'] ?? '') !== 'JWT') {
            return null;
        }

        $checkSignature = hash_hmac(
            static::stringLower($header['alg'] ?? ''),
            "{$base64Header}.{$base64Payload}",
            static::stringReplace($signKey ?? getenv('APP_KEY') ?: '', ['base64:', '='], ''),
            true,
        );

        if (!hash_equals($signature, $checkSignature)) {
            return null;
        }

        return [
            'header' => $header,
            'body' => $payload,
        ];
    }


    /**
     * Возвращает строку с jwt токеном из закодированных данных
     * @see ../../tests/HelperJwtTrait/HelperJwtEncodeTest.php
     *
     * @param array $value
     * @param array $header
     * @param string|null $signKey
     * @param string $hash
     * @return string
     */
    public static function jwtEncode(
        array $value,
        array $header = [],
        ?string $signKey = null,
        string $hash = 'sha512',
    ): string {
        $header = array_merge(
            $header,
            $header = [
                "alg" => static::stringUpper($hash),
                "typ" => 'JWT',
            ]
        );

        return static::stringConcat(
            '.',
            $base64Header = rtrim(strtr(base64_encode(json_encode($header)), '+/', '-_'), '='),
            $base64Payload = rtrim(strtr(base64_encode(json_encode($value)), '+/', '-_'), '='),
            rtrim(strtr(base64_encode(
                hash_hmac(
                    $hash,
                    "{$base64Header}.{$base64Payload}",
                    static::stringReplace($signKey ?? getenv('APP_KEY') ?: '', ['base64:', '='], ''),
                    true,
                ),
            ), '+/', '-_'), '='),
        );
    }
}
