<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Internal\HelperInternal;

/**
 * Трейт для работы со скобками/тегами
 * @mixin \Atlcom\Helper
 */
trait HelperBracketTrait
{
    /**
     * Возвращает содержимое внутри блока скобок под индексом index
     * @see ../../tests/HelperBracketTrait/HelperBracketCopyTest.php
     * 
     * @param string|null $value
     * @param string $left
     * @param string $right
     * @param int $index
     * @return string
     */
    public static function bracketCopy(string|null $value, string $left, string $right, int $index): string
    {
        $value = (string)$value;
        $brackets = HelperInternal::internalBrackets($value, $left, $right);
        !($index < 0) ?: $index = count($brackets) + $index;

        if (isset($brackets[$index])) {
            $block = $brackets[$index];
            $blockStart = $block['start'] + static::stringLength($left);
            $blockLength = $block['end'] - $blockStart;

            $value = static::stringCopy($value, $blockStart, $blockLength);
        } else {
            $value = '';
        }

        return $value;
    }


    /**
     * Возвращает строку с удалением блока скобок под индексом index
     * @see ../../tests/HelperBracketTrait/HelperBracketDeleteTest.php
     *
     * @param string|null $value
     * @param string $left
     * @param string $right
     * @param int $index
     * @return string
     */
    public static function bracketDelete(string|null $value, string $left, string $right, int $index): string
    {
        $value = (string)$value;
        $brackets = HelperInternal::internalBrackets($value, $left, $right);
        !($index < 0) ?: $index = count($brackets) + $index;

        if (isset($brackets[$index])) {
            $block = $brackets[$index];
            $blockStart = $block['start'];
            $blockLength = $block['end'] + static::stringLength($right) - $blockStart;

            $value = static::stringDelete($value, $blockStart, $blockLength);
        }

        return $value;
    }


    /**
     * Возвращает строку с заменой блока скобок под индексом index на строку replace
     * @see ../../tests/HelperBracketTrait/HelperBracketReplaceTest.php
     *
     * @param string|null $value
     * @param string $left
     * @param string $right
     * @param int $index
     * @param int|float|string|bool|null $replace
     * @return string
     */
    public static function bracketReplace(
        string|null $value,
        string $left,
        string $right,
        int $index,
        int|float|string|bool|null $replace,
    ): string {
        $value = (string)$value;
        $replace = (string)$replace;
        $brackets = HelperInternal::internalBrackets($value, $left, $right);
        !($index < 0) ?: $index = count($brackets) + $index;

        if (isset($brackets[$index])) {
            $block = $brackets[$index];
            $blockStart = $block['start'];
            $blockLength = $block['end'] + static::stringLength($right) - $blockStart;

            $value = static::stringDelete($value, $blockStart, $blockLength);
            $value = static::stringPaste($value, $replace, $blockStart);
        }

        return $value;
    }


    /**
     * Возвращает строку с заменой содержимого внутри блока скобок под индексом index на строку change
     * @see ../../tests/HelperBracketTrait/HelperBracketChangeTest.php
     *
     * @param string|null $value
     * @param string $left
     * @param string $right
     * @param int $index
     * @param int|float|string|bool|null $change
     * @return string
     */
    public static function bracketChange(
        string|null $value,
        string $left,
        string $right,
        int $index,
        int|float|string|bool|null $change,
    ): string {
        $value = (string)$value;
        $change = (string)$change;
        $brackets = HelperInternal::internalBrackets($value, $left, $right);
        !($index < 0) ?: $index = count($brackets) + $index;

        if (isset($brackets[$index])) {
            $block = $brackets[$index];
            $blockStart = $block['start'] + static::stringLength($left);
            $blockLength = $block['end'] - $blockStart;

            $value = static::stringDelete($value, $blockStart, $blockLength);
            $value = static::stringPaste($value, $change, $blockStart);
        }

        return $value;
    }


    /**
     * Возвращает количество блоков скобок
     * @see ../../tests/HelperBracketTrait/HelperBracketCountTest.php
     *
     * @param string|null $value
     * @param string $left
     * @param string $right
     * @return int
     */
    public static function bracketCount(string|null $value, string $left, string $right): int
    {
        return count(HelperInternal::internalBrackets($value, $left, $right));
    }


    /**
     * Возвращает массив найденных строк с индексами блоков скобок
     * @see ../../tests/HelperBracketTrait/HelperBracketSearchTest.php
     *
     * @param string|null $value
     * @param string $left
     * @param string $right
     * @param mixed ...$searches
     * @return array
     */
    public static function bracketSearch(string|null $value, string $left, string $right, ...$searches): array
    {
        $result = [];
        $value = (string)$value;

        foreach ($searches as $search) {
            if (is_array($search) || is_object($search)) {
                $result = [
                    ...$result,
                    ...(static::bracketSearch($value, ...static::transformToArray($search)) ?: []),
                ];

            } else {
                $bracketsCount = static::bracketCount($value, $left, $right);

                for ($bracketIndex=0; $bracketIndex < $bracketsCount; $bracketIndex++) {
                    $bracketValue = static::bracketCopy($value, $left, $right, $bracketIndex);

                    if (
                        !is_null($search)
                        && $search !== ''
                        && (($searchString = (string)$search) || $searchString === '0')
                        && (
                            $searchString === '*'
                            || mb_strpos($bracketValue, $searchString) !== false
                            || (static::regexpValidatePattern($searchString) && preg_match($searchString, $bracketValue))
                            || (mb_strpos($searchString, '*') !== false && fnmatch($searchString, $bracketValue))
                        )
                    ) {
                        $result[$search][] = $bracketIndex;
                    }
                }
            }
        }

        return $result;
    }
}
