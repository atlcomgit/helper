<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с json
 */
trait HelperJsonTrait
{
    /**
     * Возвращает флаг для формирования json строки
     * @see ../../tests/HelperJsonTrait/HelperJsonFlagsTest.php
     *
     * @param string|null $value
     * @param mixed ...$masks
     * @return int
     */
    public static function jsonFlags(): int
    {
        return
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE | JSON_INVALID_UTF8_IGNORE;
    }
}
