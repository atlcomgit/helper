<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Enums\HelperStringBreakTypeEnum;

/**
 * Трейт для работы с мессенджером телеграм
 * @mixin \Atlcom\Helper
 */
trait HelperTelegramTrait
{
    /**
     * Разбивает текст сообщения на части для отправки в телеграм
     * @see ../../tests/HelperTelegramTrait/HelperTelegramBreakMessageTest.php
     *
     * @param int|float|string|null $value
     * @param int $firstPartLength
     * @param int|null $partLengthMax
     * @param int|null $partCountMax
     * @return array
     */
    public static function telegramBreakMessage(
        int|float|string|null $value,
        int $firstPartLength,
        ?int $partLengthMax = null,
        ?int $partCountMax = null,
    ): array {
        return is_null($value)
            ? []
            : array_slice(
                static::stringBreakByLength(
                    value: static::stringReplace(
                        htmlspecialchars(
                            trim(static::stringReplace($value, ['\n' => PHP_EOL]), '"'),
                        ),
                        ['&quot;' => '"'],
                    ),
                    breakType: HelperStringBreakTypeEnum::Line,
                    partLengthMax: $partLengthMax,
                    firstPartLength: $firstPartLength,
                ),
                0,
                $partCountMax ?? 10,
            );
    }
}
