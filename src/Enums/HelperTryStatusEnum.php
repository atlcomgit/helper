<?php

declare(strict_types=1);

namespace Atlcom\Enums;

/**
 * Статус выполнения try
 */
enum HelperTryStatusEnum: string
{
    case Success = 'success';
    case Exception = 'exception';
}
