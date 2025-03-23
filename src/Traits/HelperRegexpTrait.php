<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с regexp
 */
trait HelperRegexpTrait
{
    //?!? regexpValidatePattern
    public static function regexpValidatePattern(string $value): bool
    {
        return (bool)preg_match("/^\/.+\/[a-z]*$/i", $value);
    }

    //?!? regexpValidateEmail
    //?!? regexpValidatePhone
}
