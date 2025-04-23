<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с генерацией данных
 * @mixin \Atlcom\Helper
 */
trait HelperGenerateTrait
{
    //?!? readme
    /**
     * Возвращает случайно сгенерированный url
     * @see ../../tests/HelperGenerateTrait/HelperGenerateUrlTest.php
     *
     * @param array|string|null|null $protocols
     * @param array|string|null|null $domainNames
     * @param array|string|null|null $domainZones
     * @param array|string|null|null $paths
     * @param array|string|null|null $queries
     * @param array|string|null|null $anchors
     * @return string
     */
    public static function generateUrl(
        array|string|null $protocols = null,
        array|string|null $domainNames = null,
        array|string|null $domainZones = null,
        array|string|null $paths = null,
        array|string|null $queries = null,
        array|string|null $anchors = null,
    ): string {
        !(is_string($protocols) && static::regexpValidatePattern($protocols))
            ?: $protocols = [static::stringRandom($protocols)];
        $protocols ??= ['http', 'https'];
        $protocol = $protocols[array_rand($protocols)];

        !(is_string($domainNames) && static::regexpValidatePattern($domainNames))
            ?: $domainNames = [static::stringRandom($domainNames)];
        if (is_null($domainNames)) {
            $domainName = static::stringRandom('/[a-z0-9\-\.]{1,10}/');
            $domainName = trim(static::stringDeleteMultiples($domainName, ['-', '.']), '-.')
                ?: static::stringRandom('/[a-z0-9]{1,10}/');
            $domainNames ??= [$domainName];
        }
        $domainName = $domainNames[array_rand($domainNames)];

        !(is_string($domainZones) && static::regexpValidatePattern($domainZones))
            ?: $domainZones = [static::stringRandom($domainZones)];
        $domainZones ??= ['com', 'net', 'org', 'ru'];
        $domainZone = $domainZones[array_rand($domainZones)];

        !(is_string($paths) && static::regexpValidatePattern($paths))
            ?: $paths = [static::stringRandom($paths)];
        $paths ??= [static::stringRandom('/[a-z0-9\-\/]{0,20}/')];
        $path = $paths[array_rand($paths)];

        !(is_string($queries) && static::regexpValidatePattern($queries))
            ?: $queries = [static::stringRandom($queries)];
        if (is_null($queries)) {
            $queryParams = [];
            for ($i = 0; $i < rand(0, 10); $i++) {
                $name = static::stringRandom('/[a-z]{1,8}/');
                $value = static::stringRandom('/[a-z0-9]{1,8}/');
                $queryParams[] = "{$name}={$value}";
            }
            $query = implode('&', $queryParams);

        } else {
            $query = static::stringReplace(
                $queries[array_rand($queries ?? [static::stringRandom('/[a-z0-9\-\/]{0,20}/')])],
                ['?' => ''],
            );
        }

        !(is_string($anchors) && static::regexpValidatePattern($anchors))
            ?: $anchors = [static::stringRandom($anchors)];
        $anchors ??= [static::stringRandom('/[a-z0-9\-]{0,10}/')];
        $anchor = $anchors[array_rand($anchors)];

        return static::stringConcat('://', $protocol, $domainName)
            . static::stringConcat(
                '?',
                static::stringConcat('/', rtrim(".$domainZone"), $path),
                $query,
            )
            . ($anchor ? "#$anchor" : '');
    }


    //?!? test
    /**
     * Генерация случайного пароля
     * @param string $pattern
     * @return string
     */
    public static function generatePassword(string $pattern = '/[A-Za-z0-9!@#$%^&*()_+]{8,16}/'): string
    {
        return static::stringRandom($pattern);
    }


    //?!? test
    /**
     * Генерация случайного email
     * @return string
     */
    public static function generateEmail(): string
    {
        $user = static::stringRandom('/[a-z0-9]{5,10}/');
        $domain = static::stringRandom('/[a-z]{5,10}/');
        $tld = ['com', 'net', 'org', 'io', 'ru'][array_rand(['com', 'net', 'org', 'io', 'ru'])];

        return "{$user}@{$domain}.{$tld}";
    }


    //?!? test
    /**
     * Генерация случайного телефона с кодом страны
     * @param string $countryCode
     * @return string
     */
    public static function generatePhone(string $countryCode = '+7'): string
    {
        // Пример для России: +7 9XX XXX-XX-XX
        $number = $countryCode . static::stringRandom('/[0-9]{10}/');

        return $number;
    }


    //?!? test
    /**
     * Генерация случайного UUID v4
     * @return string
     */
    public static function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


    //?!? code
    public static function generateName(): string
    {
        return '';
    }
}
