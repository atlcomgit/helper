<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Enums\HelperStringBreakTypeEnum;

/**
 * Трейт для работы с мессенджером телеграм
 */
trait HelperTelegramTrait
{
    /**
     * Разбивает текст сообщения на части для отправки в телеграм
     * @see ./tests/HelperTelegramTrait/HelperTelegramBreakMessageTest.php
     *
     * @param int|float|string $value
     * @param bool $hasAttach
     * @return array
     */
    public static function telegramBreakMessage(
        int|float|string $value,
        int $firstPartLength,
        ?int $partLengthMax = null,
        ?int $partCountMax = null,
    ): array {
        return array_slice(
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
