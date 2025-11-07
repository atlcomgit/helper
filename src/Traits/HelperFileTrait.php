<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с файлами
 * @mixin \Atlcom\Helper
 */
trait HelperFileTrait
{
    /**
     * Возвращает путь из строки
     * @see ../../tests/HelperFileTrait/FilePathTest.php
     *
     * @param string|null $value
     * @return string
     */
    public static function filePath(?string $value): string
    {
        if (!$value) {
            return '';
        }

        $path = pathinfo($value, PATHINFO_DIRNAME);

        return $path === '.' ? '' : $path;
    }


    /**
     * Возвращает имя файла с расширением
     * @see ../../tests/HelperFileTrait/FileNameTest.php
     *
     * @param string|null $value
     * @return string
     */
    public static function fileName(?string $value): string
    {
        if (!$value) {
            return '';
        }

        return pathinfo($value, PATHINFO_BASENAME);
    }


    /**
     * Возвращает только расширение файла без точки
     * @see ../../tests/HelperFileTrait/FileExtensionTest.php
     *
     * @param string|null $value
     * @return string
     */
    public static function fileExtension(?string $value): string
    {
        if (!$value) {
            return '';
        }

        return pathinfo($value, PATHINFO_EXTENSION);
    }


    /**
     * Меняет путь к файлу
     * @see ../../tests/HelperFileTrait/FileChangePathTest.php
     *
     * @param string|null $value
     * @param string|null $newPath
     * @return string
     */
    public static function fileChangePath(?string $value, ?string $newPath): string
    {
        if (!$value) {
            return '';
        }

        $fileName = static::fileName($value);

        if (!$newPath) {
            return $fileName;
        }

        $newPath = rtrim($newPath, '/\\');

        return $newPath . '/' . $fileName;
    }


    /**
     * Меняет имя файла с расширением
     * @see ../../tests/HelperFileTrait/FileChangeNameTest.php
     *
     * @param string|null $value
     * @param string|null $newName
     * @return string
     */
    public static function fileChangeName(?string $value, ?string $newName): string
    {
        if (!$value) {
            return '';
        }

        if (!$newName) {
            return $value;
        }

        $path = static::filePath($value);

        return $path ? $path . '/' . $newName : $newName;
    }


    /**
     * Меняет расширение файла без точки
     * @see ../../tests/HelperFileTrait/FileChangeExtensionTest.php
     *
     * @param string|null $value
     * @param string|null $newExtension
     * @return string
     */
    public static function fileChangeExtension(?string $value, ?string $newExtension): string
    {
        if (!$value) {
            return '';
        }

        $pathInfo = pathinfo($value);
        $path = $pathInfo['dirname'] ?? '';
        $filename = $pathInfo['filename'] ?? '';

        if (!$filename) {
            return $value;
        }

        $result = $path && $path !== '.' ? $path . '/' . $filename : $filename;

        if ($newExtension) {
            $result .= '.' . ltrim($newExtension, '.');
        }

        return $result;
    }
}
