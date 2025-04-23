<?php

declare(strict_types=1);

namespace Atlcom\Enums;

/**
 * Разделение текста
 */
enum HelperStringBreakTypeEnum: string
{
    case Word = 'word';
    case Line = 'line';
}
