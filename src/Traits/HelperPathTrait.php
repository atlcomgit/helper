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
     *
     * @return string
     */
    public static function basePath(): string
    {
        return str_replace('/public', '', $_SERVER['DOCUMENT_ROOT'] ?? '');
    }


    /**
     * Возвращает имя класса без namespace 
     *
     * @param string $value
     * @return string
     */
    public static function baseNameClass(string $value): string
    {
        return basename(str_replace('\\', '/', $value));
    }
}
