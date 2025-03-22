<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с путями
 */
trait HelperPathTrait
{
    /**
     * Возвращает путь домашней папки
     * @see ./tests/HelperPathTrait/HelperPathRootTest.php
     *
     * @param string|null $value
     * @return string
     */
    public static function pathRoot(?string $value = null): string
    {
        return $value ?? static::stringReplace($_SERVER['DOCUMENT_ROOT'] ?: realpath('') ?: '', '/public', '');
    }


    /**
     * Возвращает имя класса без namespace
     * @see ./tests/HelperPathTrait/HelperPathClassNameTest.php
     *
     * @param string $value
     * @return string
     */
    public static function pathClassName(string $value): string
    {
        !static::stringEnds($value, '.php') ?: $value = static::stringDelete($value, -4);

        return basename(static::stringReplace($value, '\\', '/'));
    }
}
