<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Consts\HelperConsts;
use Atlcom\Enums\HelperTranslitDirectionEnum;

/**
 * Трейт для работы с транслитерацией
 * @mixin \Atlcom\Helper
 */
trait HelperTranslitTrait
{
    /**
     * Возвращает транслитерацию строки
     * @see ../../tests/HelperTranslitTrait/HelperTranslitStringTest.php
     *
     * @param string|null $value
     * @param HelperTranslitDirectionEnum $direction
     * @return string
     */
    public static function translitString(
        ?string $value,
        HelperTranslitDirectionEnum $direction = HelperTranslitDirectionEnum::Auto,
    ): string {
        $value = (string)$value;

        // Автоматическое определение направления
        switch ($direction) {
            // Авто
            case HelperTranslitDirectionEnum::Auto:
                $hasCyrillic = preg_match('/[\p{Cyrillic}]/u', $value);
                $direction = $hasCyrillic ? HelperTranslitDirectionEnum::Latin : HelperTranslitDirectionEnum::Russian;
                $value = static::translitString($value, $direction);
                break;

            // Русский -> Латинский
            case HelperTranslitDirectionEnum::Latin:
                $value = strtr($value, HelperConsts::TRANSLIT_NAMES);
                break;

            // Латинский -> Русский
            case HelperTranslitDirectionEnum::Russian:
                $translitNames = array_flip(HelperConsts::TRANSLIT_NAMES);
                // Сортируем ключи по длине в обратном порядке
                uksort($translitNames, static fn ($a, $b) => strlen($b) - strlen($a) ?: strcmp($a, $b));

                $value = static::stringReplace($value, $translitNames);
                break;
        }

        return $value;
    }


    /**
     * Возвращает slug из строки
     * @see ../../tests/HelperTranslitTrait/HelperTranslitToSlugTest.php
     *
     * @param string|null $value
     * @param string $slugSeparator
     * @return string
     */
    public static function translitToSlug(?string $value, string $slugSeparator = '_'): string
    {
        $value = (string)$value;
        $value = static::stringSegment($value);
        $value = static::translitString($value, HelperTranslitDirectionEnum::Latin);
        $value = static::stringReplace($value, ['\'', '"'], '');
        $value = static::stringReplace($value, '/[^0-9A-Za-z]/', $slugSeparator);
        $value = static::stringDeleteMultiples($value, $slugSeparator);
        $value = static::stringLower($value);

        return $value;
    }


    /**
     * Возвращает строку из slug
     * @see ../../tests/HelperTranslitTrait/HelperTranslitFromSlugTest.php
     *
     * @param string|null $value
     * @param string $slugSeparator
     * @return string
     */
    public static function translitFromSlug(?string $value, string $slugSeparator = '_'): string
    {
        $value = (string)$value;
        $value = static::stringSegment($value);
        $value = static::translitString($value, HelperTranslitDirectionEnum::Russian);
        $value = static::stringReplace($value, $slugSeparator, ' ');
        $value = static::stringDeleteMultiples($value, ' ');

        return $value;
    }
}
