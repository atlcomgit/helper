<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Internal\HelperInternal;

/**
 * Трейт для работы с url
 */
trait HelperUrlTrait
{
    /**
     * Возвращает массив частей адреса url
     * @see ../../tests/HelperUrlTrait/HelperUrlParseTest.php
     *
     * @param string|null $value
     * @return array
     */
    public static function urlParse(?string $value): array
    {
        $value = (string)$value;
        $parsed = parse_url($value) ?: [];
        $scheme = $parsed['scheme'] ?? null;
        $host = $parsed['host'] ?? null;
        $path = $parsed['path'] ?? null;

        if (!$scheme) {
            $parsed = parse_url("http://$value") ?: [];
            $host = $parsed['host'] ?? null;
            $path = $parsed['path'] ?? null;

            if (!$host) {
                $parsed = parse_url($value = "http://domain/" . ltrim($value, '/')) ?: [];
                $path = $parsed['path'] ?? null;
                !($path === '/') ?: $path = null;
            }
        }

        !$host ?: $host = static::urlDomainDecode($host);
        !$path ?: $path = '/' . trim($path, '/');

        $query = [];
        !isset($parsed['query']) ?: parse_str($parsed['query'], $query);
        $query = array_map(
            static fn ($value) => match (true) {
                is_numeric($value) => static::stringSearchAny($value, '.') ? (float)$value : (int)$value,
                $value === 'true' => true,
                $value === 'false' => false,

                default => (string)$value,
            },
            $query,
        );

        return ['scheme' => $scheme, 'host' => $host, 'path' => $path, 'query' => $query];
    }


    /**
     * Возвращает строку адреса url
     * @see ../../tests/HelperUrlTrait/HelperUrlCreateTest.php
     *
     * @param string|null $scheme
     * @param string|null $host
     * @param string|null $path
     * @param string|array|null|null $query
     * @return string
     */
    public static function urlCreate(
        ?string $scheme = null,
        ?string $host = null,
        ?string $path = null,
        string|array|null $query = null,
    ): string {
        return rtrim(
            static::stringPadSuffix(trim($scheme, ':/'), '://')
            . static::stringPadSuffix(
                static::urlDomainEncode(trim(static::stringReplace($host, ['/' => '']))),
                '/',
            )
            . trim($path, '/'),
            '/',
        )
            . static::stringPadPrefix(
                match (true) {
                    is_array($query) || is_object($query) => http_build_query(static::transformToArray($query)),

                    default => $query,
                },
                '?',
            );
    }


    /**
     * Возвращает кодированный русский домен в виде xn--
     * @see ../../tests/HelperUrlTrait/HelperUrlDomainEncodeTest.php
     *
     * @param string|null $value
     * @return string
     */
    public static function urlDomainEncode(?string $value): string
    {
        $result = [];
        $value = (string)$value;
        $parts = explode('.', static::stringLower($value));

        foreach ($parts as $part) {
            $result[] = preg_match('/[^\x20-\x7E]/', $part)
                ? HelperInternal::internalPunycodeEncode($part)
                : $part;
        }

        return implode('.', $result);
    }


    /**
     * Возвращает декодированный русский домен вида xn--
     * @see ../../tests/HelperUrlTrait/HelperUrlDomainDecodeTest.php
     *
     * @param string|null $value
     * @return string
     */
    public static function urlDomainDecode(?string $value): string
    {
        $result = [];
        $value = (string)$value;
        $parts = explode('.', $value);

        foreach ($parts as $part) {
            $result[] = (static::stringStarts($part, 'xn--'))
                ? HelperInternal::internalPunycodeDecode(substr($part, 4))
                : $part;
        }

        return implode('.', $result);
    }
}
