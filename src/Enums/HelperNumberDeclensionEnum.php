<?php

declare(strict_types=1);

namespace Atlcom\Enums;

/**
 * Падеж
 */
enum HelperNumberDeclensionEnum: int
{
    case Nominative = 0; // Именительный падеж
    case Genitive = 1; // Родительный падеж
    case Dative = 2; // Дательный падеж
    case Accusative = 3; // Винительный падеж
    case Instrumental = 4; // Творительный падеж
    case Prepositional = 5; // Предложный падеж
}
