<?php

declare(strict_types=1);

namespace Atlcom\Enums;

enum HelperRegexpEnum: string
{
    case Email = '/^([A-Za-z0-9_\.-]+)@([\dA-Za-z\.-]+)\.([A-Za-z\.]{2,6})$/';
    case Pattern = '/^\/.+\/[a-z]*$/i';
    case Phone = '/^[\+0-9\-\(\)\s]*$/';
    case Uuid = '/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/iD';
    case Ascii = '/[^\x09\x10\x13\x0A\x0D\x20-\x7E]/';
    case Unicode = '/[^\w$\x{0080}-\x{FFFF}]+/u';
}
