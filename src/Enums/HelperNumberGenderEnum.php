<?php

declare(strict_types=1);

namespace Atlcom\Enums;

/**
 * Род
 */
enum HelperNumberGenderEnum: int
{
    case Male = 0; // Мужской род
    case Female = 1; // Женский род
    case Neuter = 2; // Средний род
    case Common = 3; // Общий род

}
