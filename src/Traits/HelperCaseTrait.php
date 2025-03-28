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
        return static::stringLowerFirst(
            static::stringReplace(
                static::stringUpperFirstAll(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                [' ' => ''],
            ),
        );
    }


    //?!? readme
    /**
     * Возвращает строку в стиле kebab-case
     * @see ./tests/HelperCaseTrait/HelperCaseKebabTest.php
     *
     * @param string $value
     * @return string
     */
    public static function caseKebab(string $value): string
    {
        return ltrim(
            preg_replace('/(?<=\w)(?=[A-ZА-ЯЁ])/u', '-', static::stringReplace($value, [' ' => '-', '_' => '-'])),
            '-',
        );
        ;
    }


    //?!? readme
    /**
     * Возвращает строку в стиле PascalCase
     * @see ./tests/HelperCaseTrait/HelperCasePascalTest.php
     *
     * @param string $value
     * @return string
     */
    public static function casePascal(string $value): string
    {
        return static::stringUpperFirst(
            static::stringReplace(
                static::stringUpperFirstAll(static::stringReplace(ltrim($value), ['_' => ' ', '-' => ' '])),
                [' ' => ''],
            ),
        );
    }


    //?!? readme
    /**
     * Возвращает строку в стиле snake_case
     * @see ./tests/HelperCaseTrait/HelperCaseSnakeTest.php
     *
     * @param string $value
     * @return string
     */
    public static function caseSnake(string $value): string
    {
        return static::stringReplace(
            preg_replace('/(?<=\w)(?=[A-ZА-ЯЁ])/u', '-', static::stringReplace($value, [' ' => '-', '_' => '-'])),
            ['-' => '_'],
        );
    }
}
