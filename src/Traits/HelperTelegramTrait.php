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
    public static function telegramBreakMessage(int|float|string $value, bool $hasAttach = false): array
    {
        return static::stringBreakByLength(
            value: htmlspecialchars(trim(static::stringReplace($value, '\n', PHP_EOL), '"')),
            breakType: HelperStringBreakTypeEnum::Line,
            partLengthMax: 4000,
            firstPartLength: $hasAttach ? 1000 : null,
        );
    }
}
