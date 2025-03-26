<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы со стилями кода
 */
trait HelperCaseTrait
{
    public static string $encoding = 'UTF-8';


    //?!? test
    /**
     * Возвращает строку в стиле camelCase
     * @see ./tests/HelperCaseTrait/HelperCaseCamelTest.php
     *
     * @param string $value
     * @return string
     */
    public static function caseCamel(string $value): string
    {
        return (phpversion() >= '8.4')
            ? mb_lcfirst(
                static::stringReplace(
                    // ucwords(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    mb_convert_case(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' ']), MB_CASE_TITLE, static::$encoding),
                    [' ' => ''],
                ),
                static::$encoding,
            )
            : lcfirst(
                static::stringReplace(
                    // ucwords(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    mb_convert_case(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' ']), MB_CASE_TITLE, static::$encoding),
                    [' ' => ''],
                ),
            );
    }


    //?!? test
    /**
     * Возвращает строку в стиле kebab-case
     * @see ./tests/HelperCaseTrait/HelperCaseCamelTest.php
     *
     * @param string $value
     * @return string
     */
    public static function caseKebab(string $value): string
    {
        return static::stringLower(
            ltrim(preg_replace('/(?<!^)[A-ZА-ЯЁ]/', '-$0', $value), '-'),
        );
    }


    //?!? test
    /**
     * Возвращает строку в стиле PascalCase
     * @see ./tests/HelperCaseTrait/HelperCaseCamelTest.php
     *
     * @param string $value
     * @return string
     */
    public static function casePascal(string $value): string
    {
        return (phpversion() >= '8.4')
            ? mb_ucfirst(
                static::stringReplace(
                    ucwords(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    [' ' => ''],
                ),
                static::$encoding,
            )
            : ucfirst(
                static::stringReplace(
                    ucwords(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    [' ' => ''],
                ),
            );
    }


    //?!? test
    /**
     * Возвращает строку в стиле snake_case
     * @see ./tests/HelperCaseTrait/HelperCaseCamelTest.php
     *
     * @param string $value
     * @return string
     */
    public static function caseSnake(string $value): string
    {
        return static::stringLower(
            ltrim(preg_replace('/(?<!^)[A-ZА-ЯЁ]/', '_$0', $value), '_'),
        );
    }
}
