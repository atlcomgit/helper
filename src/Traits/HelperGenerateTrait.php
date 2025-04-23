<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Consts\HelperConsts as Consts;
use Atlcom\Enums\HelperGenerateLocaleEnum as Locale;

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
        $path = $paths[array_rand($paths)] ?: '/';

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


    //?!? readme
    /**
     * Возвращает случайно сгенерированный пароль
     * @see ../../tests/HelperGenerateTrait/HelperGeneratePasswordTest.php
     * 
     * @param string $pattern
     * @return string
     */
    public static function generatePassword(string $pattern = '/[A-Za-z0-9!@#$%^&\*()_\+]{8,16}/'): string
    {
        return static::stringRandom($pattern);
    }


    //?!? test
    /**
     * Возвращает случайно сгенерированный email
     * @see ../../tests/HelperGenerateTrait/HelperGenerateEmailTest.php
     * 
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
     * Возвращает случайно сгенерированный номер телефона с кодом страны
     * @see ../../tests/HelperGenerateTrait/HelperGeneratePhoneTest.php
     * 
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
     * Возвращает случайно сгенерированный UUID v4
     * @see ../../tests/HelperGenerateTrait/HelperGenerateUuid4Test.php
     * 
     * @return string
     */
    public static function generateUuid4(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }


    //?!? test
    /**
     * Возвращает случайно сгенерированный UUID v7
     * @see ../../tests/HelperGenerateTrait/HelperGenerateUuid7Test.php
     * 
     * @return string
     */
    public static function generateUuid7(): string
    {
        // Uuidv7: 48 бит Unix TimeStamp MS, 12 бит случайные, 2 -битные версии, 62 бита случайные
        $time = (int) (microtime(true) * 1000); // миллисекунды
        $timeHex = str_pad(dechex($time), 12, '0', STR_PAD_LEFT);

        $rand = bin2hex(random_bytes(10)); // 20 шестнадцатеричных частей = 80 бит

        // Формируем UUIDv7: xxxxxxxx-xxxx-7xxx-yxxx-xxxxxxxxxxxx
        $uuid = sprintf(
            '%08s-%04s-7%03s-%s%s-%s',
            substr($timeHex, 0, 8),
            substr($timeHex, 8, 4),
            substr($rand, 0, 3),
            dechex((hexdec(substr($rand, 3, 1)) & 0x3) | 0x8), // вариант 10xx
            substr($rand, 4, 3),
            substr($rand, 7)
        );

        return $uuid;
    }


    //?!? test
    /**
     * Возвращает случайно сгенерированное ФИО
     * @see ../../tests/HelperGenerateTrait/HelperGenerateNameTest.php
     *
     * @param Locale $locale
     * @param array|string|bool|null $surnames
     * @param array|string|bool|null $firstNames
     * @param array|string|bool|null $patronymics
     * @return string
     */
    public static function generateName(
        Locale $locale = Locale::Russian,
        array|string|bool|null $surnames = true,
        array|string|bool|null $firstNames = true,
        array|string|bool|null $patronymics = false,
    ): string {
        $surnamePrefixes = in_array($surnames, [null, false], true)
            ? match ($locale) {
                Locale::English => Consts::SURNAME_PREFIXES_EN,
                Locale::Russian => Consts::SURNAME_PREFIXES_RU,
            } : [];
        $surnameSuffixes = in_array($surnames, [null, false], true) ? match ($locale) {
            Locale::English => Consts::SURNAME_SUFFIXES_EN,
            Locale::Russian => Consts::SURNAME_SUFFIXES_RU,
        } : [];
        $firstNamePrefixes = in_array($firstNames, [null, false], true)
            ? match ($locale) {
                Locale::English => Consts::FIRSTNAME_PREFIXES_EN,
                Locale::Russian => Consts::FIRSTNAME_PREFIXES_RU,
            } : [];
        $firstNameSuffixes = in_array($firstNames, [null, false], true)
            ? match ($locale) {
                Locale::English => Consts::FIRSTNAME_PREFIXES_EN,
                Locale::Russian => Consts::FIRSTNAME_PREFIXES_RU,
            } : [];
        $patronymicPrefixes = in_array($patronymics, [null, false], true)
            ? match ($locale) {
                Locale::English => [],
                Locale::Russian => Consts::PATRONYNIC_PREFIXES_RU,
            } : [];
        $patronymicSuffixes = in_array($patronymics, [null, false], true)
            ? match ($locale) {
                Locale::English => [],
                Locale::Russian => Consts::PATRONYNIC_SUFFIXES_RU,
            } : [];

        $names = [];

        if ($surnamePrefixes) {
            $names[] = $surnamePrefixes[array_rand($surnamePrefixes)]
                . ($surnameSuffixes ? $surnameSuffixes[array_rand($surnameSuffixes)] : '');
        }
        if ($firstNamePrefixes) {
            $names[] = $firstNamePrefixes[array_rand($firstNamePrefixes)]
                . ($firstNameSuffixes ? $firstNameSuffixes[array_rand($firstNameSuffixes)] : '');
        }
        if ($patronymicPrefixes) {
            $names[] = $patronymicPrefixes[array_rand($patronymicPrefixes)]
                . ($patronymicSuffixes ? $patronymicSuffixes[array_rand($patronymicSuffixes)] : '');
        }

        return implode(' ', $names);
    }
}
