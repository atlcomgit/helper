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
     * @param string $message
     * @param bool $hasAttach
     * @return array
     */
    //?!? 
    public function telegramBreakMessage(string $message, bool $hasAttach = false): array
    {
        return static::stringBreakByLength(
            message: htmlspecialchars(trim(str_replace('\n', PHP_EOL, $message), '"')),
            breakType: HelperStringBreakTypeEnum::Line,
            partLengthMax: 4000,
            firstPartLength: $hasAttach ? 1000 : null,
        );
    }
}
