<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы со стилями кода
 */
trait HelperCaseTrait
{
    //?!? readme
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
            ? static::stringLowerFirst(
                static::stringReplace(
                    // ucwords(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    static::stringUpperFirstAll(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    [' ' => ''],
                ),
            )
            : static::stringLowerFirst(
                static::stringReplace(
                    // ucwords(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    static::stringUpperFirstAll(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    [' ' => ''],
                ),
            );
    }


    //?!? test
    /**
     * Возвращает строку в стиле kebab-case
     * @see ./tests/HelperCaseTrait/HelperCaseKebabTest.php
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
     * @see ./tests/HelperCaseTrait/HelperCasePascalTest.php
     *
     * @param string $value
     * @return string
     */
    public static function casePascal(string $value): string
    {
        return (phpversion() >= '8.4')
            ? static::stringUpperFirst(
                static::stringReplace(
                    static::stringUpperFirstAll(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    [' ' => ''],
                ),
            )
            : static::stringUpperFirst(
                static::stringReplace(
                    static::stringUpperFirstAll(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                    [' ' => ''],
                ),
            );
    }


    //?!? test
    /**
     * Возвращает строку в стиле snake_case
     * @see ./tests/HelperCaseTrait/HelperCaseSnakeTest.php
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
