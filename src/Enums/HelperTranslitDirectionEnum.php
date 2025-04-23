<?php

declare(strict_types=1);

namespace Atlcom\Enums;

/**
 * Направление транслитерации
 */
enum HelperTranslitDirectionEnum: string
{
    case Auto = 'auto';
    case Latin = 'latin';
    case Russian = 'russian';
}
