<?php

declare(strict_types=1);

namespace Atlcom\Internal;

use Throwable;

/**
 * @internal
 * Внутренний помощник для современного шифрования значений
 */
final class HelperCipher
{
    private const VERSION = 1;
    private const FLAG_COMPRESSED = 1;
    private const COMPRESSION_THRESHOLD = 32;
    private const JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    private const PWHASH_OPSLIMIT = SODIUM_CRYPTO_PWHASH_OPSLIMIT_INTERACTIVE;
    private const PWHASH_MEMLIMIT = SODIUM_CRYPTO_PWHASH_MEMLIMIT_INTERACTIVE;
    private const BASE58_ALPHABET = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';


    /**
     * @var array<string, string>
     */
    private static array $masterKeyCache = [];


    /**
     * Шифрует значение с использованием пароля
     *
     * @param mixed $value
     * @param string|null $password
     * @return string
     */
    public static function encode(mixed $value, ?string $password = null): string
    {
        $nonce = '';

        try {
            if (!static::sodiumAvailable()) {
                return '';
            }

            $normalizedPassword = static::resolvePassword($password);

            if ($normalizedPassword === '') {
                return '';
            }

            $stringValue = static::stringifyValue($value);
            [$payload, $flags] = static::maybeCompress($stringValue);
            $nonce = random_bytes(SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES);

            if (strlen($nonce) !== SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES) {
                return '';
            }

            $key = static::deriveKey($normalizedPassword, $nonce);

            /** @var callable(string, string, string, string):string $encrypt */
            $encrypt = 'sodium_crypto_aead_chacha20poly1305_ietf_encrypt';
            $ciphertext = $encrypt($payload, '', $nonce, $key);

            if (function_exists('sodium_memzero')) {
                sodium_memzero($key);
            }

            $packet = static::packHeader(self::VERSION, $flags) . $nonce . $ciphertext;

            return static::base58Encode($packet);
        } catch (Throwable $exception) {
            return '';
        }
    }


    /**
     * Дешифрует значение с использованием пароля
     *
     * @param string|null $value
     * @param string|null $password
     * @return mixed
     */
    public static function decode(?string $value, ?string $password = null): mixed
    {
        try {
            if (!static::sodiumAvailable()) {
                return null;
            }

            if ($value === null || $value === '') {
                return null;
            }

            $normalizedPassword = static::resolvePassword($password);

            if ($normalizedPassword === '') {
                return null;
            }

            $packet = static::base58Decode($value);

            if ($packet === null) {
                return null;
            }

            $headerLength = 1 + SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES + SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_ABYTES;

            if (strlen($packet) < $headerLength) {
                return null;
            }

            [$version, $flags] = static::unpackHeader(ord($packet[0]));

            if ($version !== self::VERSION) {
                return null;
            }

            $offset = 1;
            $nonce = substr($packet, $offset, SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES);

            if (strlen($nonce) !== SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES) {
                return null;
            }

            $offset += SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_NPUBBYTES;
            $ciphertext = substr($packet, $offset);

            if ($ciphertext === '') {
                return null;
            }

            $key = static::deriveKey($normalizedPassword, $nonce);
            /** @var callable(string, string, string, string):string|false $decrypt */
            $decrypt = 'sodium_crypto_aead_chacha20poly1305_ietf_decrypt';
            $plaintext = $decrypt($ciphertext, '', $nonce, $key);

            if (function_exists('sodium_memzero')) {
                sodium_memzero($key);
            }

            if ($plaintext === false) {
                return null;
            }

            if (($flags & self::FLAG_COMPRESSED) === self::FLAG_COMPRESSED) {
                $decompressed = @gzinflate($plaintext);

                if ($decompressed === false) {
                    return null;
                }

                $plaintext = $decompressed;
            }

            return static::restoreValue($plaintext);
        } catch (Throwable $exception) {
            return null;
        }
    }


    /**
     * Проверяет доступность библиотеки sodium
     *
     * @return bool
     */
    private static function sodiumAvailable(): bool
    {
        return function_exists('sodium_crypto_aead_chacha20poly1305_ietf_encrypt')
            && function_exists('sodium_crypto_aead_chacha20poly1305_ietf_decrypt')
            && function_exists('sodium_crypto_pwhash')
            && function_exists('sodium_crypto_generichash');
    }


    /**
     * Подготавливает строку для шифрования
     *
     * @param mixed $value
     * @return string
     */
    private static function stringifyValue(mixed $value): string
    {
        return match (true) {
            $value === null => 'n|',
            is_int($value) => 'i|' . (string)$value,
            is_float($value) => 'f|' . (string)$value,
            is_string($value) => 's|' . $value,
            is_bool($value) => 'b|' . ($value ? '1' : '0'),
            is_array($value) => 'a|' . json_encode($value, self::JSON_FLAGS),
            is_object($value) => 'o|' . serialize($value),

            default => '?|' . (string)$value,
        };
    }


    /**
     * Восстанавливает значение из строки
     *
     * @param string $value
     * @return mixed
     */
    private static function restoreValue(string $value): mixed
    {
        [$type, $payload] = array_pad(explode('|', $value, 2), 2, null);

        if ($type === null) {
            return null;
        }

        $payload ??= '';

        return match ($type) {
            'n' => null,
            'i' => (int)$payload,
            'f' => (float)$payload,
            's' => (string)$payload,
            'b' => $payload === '1',
            'a' => json_decode($payload, true),
            'o' => unserialize($payload, ['allowed_classes' => true]),
            '?' => $payload,

            default => null,
        };
    }


    /**
     * Сжимает данные при необходимости
     *
     * @param string $value
     * @return array
     */
    private static function maybeCompress(string $value): array
    {
        if (strlen($value) < self::COMPRESSION_THRESHOLD) {
            return [$value, 0];
        }

        $compressed = gzdeflate($value, 9);

        if ($compressed === false || strlen($compressed) >= strlen($value)) {
            return [$value, 0];
        }

        return [$compressed, self::FLAG_COMPRESSED];
    }


    /**
     * Декодирует значение из base58 строки
     *
     * @param string $value
     * @return string|null
     */
    private static function base58Decode(string $value): ?string
    {
        if ($value === '') {
            return '';
        }

        $alphabet = self::BASE58_ALPHABET;
        $indexes = static::base58Indexes();
        $length = strlen($value);
        $leadingZeros = 0;

        while ($leadingZeros < $length && $value[$leadingZeros] === $alphabet[0]) {
            $leadingZeros++;
        }

        $trimmed = substr($value, $leadingZeros);
        $bytes = [];

        if ($trimmed !== '' && $trimmed !== false) {
            $bytes = [0];
            for ($i = 0, $trimmedLength = strlen($trimmed); $i < $trimmedLength; $i++) {
                $char = $trimmed[$i];
                if (!isset($indexes[$char])) {
                    return null;
                }

                $carry = $indexes[$char];

                for ($j = 0, $bytesCount = count($bytes); $j < $bytesCount; $j++) {
                    $carry += $bytes[$j] * 58;
                    $bytes[$j] = $carry & 0xFF;
                    $carry >>= 8;
                }

                while ($carry > 0) {
                    $bytes[] = $carry & 0xFF;
                    $carry >>= 8;
                }
            }
        }

        $result = str_repeat("\0", $leadingZeros);

        for ($i = count($bytes) - 1; $i >= 0; $i--) {
            $result .= chr($bytes[$i]);
        }

        return $result;
    }


    /**
     * Кодирует двоичную строку в base58
     *
     * @param string $value
     * @return string
     */
    private static function base58Encode(string $value): string
    {
        if ($value === '') {
            return '';
        }

        $alphabet = self::BASE58_ALPHABET;
        $length = strlen($value);
        $leadingZeros = 0;

        while ($leadingZeros < $length && $value[$leadingZeros] === "\0") {
            $leadingZeros++;
        }

        $trimmed = substr($value, $leadingZeros);

        if ($trimmed === '' || $trimmed === false) {
            return str_repeat($alphabet[0], $leadingZeros);
        }

        $digits = [0];

        for ($i = 0, $trimmedLength = strlen($trimmed); $i < $trimmedLength; $i++) {
            $carry = ord($trimmed[$i]);

            for ($j = 0, $digitsCount = count($digits); $j < $digitsCount; $j++) {
                $carry += $digits[$j] << 8;
                $digits[$j] = $carry % 58;
                $carry = intdiv($carry, 58);
            }

            while ($carry > 0) {
                $digits[] = $carry % 58;
                $carry = intdiv($carry, 58);
            }
        }

        $result = str_repeat($alphabet[0], $leadingZeros);

        for ($i = count($digits) - 1; $i >= 0; $i--) {
            $result .= $alphabet[$digits[$i]];
        }

        return $result;
    }


    /**
     * Возвращает индекс символов base58
     *
     * @return array<string, int>
     */
    private static function base58Indexes(): array
    {
        static $indexes = null;

        if ($indexes === null) {
            $indexes = [];
            for ($i = 0, $length = strlen(self::BASE58_ALPHABET); $i < $length; $i++) {
                $indexes[self::BASE58_ALPHABET[$i]] = $i;
            }
        }

        return $indexes;
    }


    /**
     * Формирует ключ шифрования из пароля и соли
     *
     * @param string $password
     * @param string $salt
     * @return string
     */
    private static function deriveKey(string $password, string $nonce): string
    {
        $masterKey = static::deriveMasterKey($password);

        return sodium_crypto_generichash(
            $nonce,
            $masterKey,
            SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_KEYBYTES,
        );
    }


    /**
     * Получает пароль из аргумента или .env
     *
     * @param string|null $password
     * @return string
     */
    private static function resolvePassword(?string $password): string
    {
        if (is_string($password) && $password !== '') {
            return $password;
        }

        $envPassword = HelperInternal::internalEnv('APP_KEY');

        return is_string($envPassword) ? $envPassword : '';
    }


    /**
     * Получает мастер-ключ для указанного пароля
     *
     * @param string $password
     * @return string
     */
    private static function deriveMasterKey(string $password): string
    {
        $cacheKey = HelperInternal::hashXxh128($password);

        if (!isset(self::$masterKeyCache[$cacheKey])) {
            self::$masterKeyCache[$cacheKey] = sodium_crypto_pwhash(
                SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_IETF_KEYBYTES,
                $password,
                static::masterSalt(),
                self::PWHASH_OPSLIMIT,
                self::PWHASH_MEMLIMIT,
                SODIUM_CRYPTO_PWHASH_ALG_DEFAULT,
            );
        }

        return self::$masterKeyCache[$cacheKey];
    }


    /**
     * Возвращает соль для мастер-ключа
     *
     * @return string
     */
    private static function masterSalt(): string
    {
        static $salt = null;

        if ($salt === null) {
            $salt = substr(hash('sha256', 'atlcom-helper-cipher:master-salt', true), 0, SODIUM_CRYPTO_PWHASH_SALTBYTES);
        }

        return $salt;
    }


    /**
     * Упаковывает версию и флаги в один байт
     *
     * @param int $version
     * @param int $flags
     * @return string
     */
    private static function packHeader(int $version, int $flags): string
    {
        $version &= 0x0F;
        $flags &= 0x0F;

        return chr(($version << 4) | $flags);
    }


    /**
     * Распаковывает версию и флаги
     *
     * @param int $header
     * @return array{int,int}
     */
    private static function unpackHeader(int $header): array
    {
        $version = ($header >> 4) & 0x0F;
        $flags = $header & 0x0F;

        return [$version, $flags];
    }
}
