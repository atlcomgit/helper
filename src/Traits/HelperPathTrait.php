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
     * @return string
     */
    //?!? 
    public static function pathRoot(): string
    {
        return str_replace('/public', '', $_SERVER['DOCUMENT_ROOT'] ?? '');
    }


    /**
     * Возвращает имя класса без namespace
     * @see ./tests/HelperPathTrait/HelperPathClassNameTest.php
     *
     * @param string $value
     * @return string
     */
    //?!? 
    public static function pathClassName(string $value): string
    {
        return basename(str_replace('\\', '/', $value));
    }
}
