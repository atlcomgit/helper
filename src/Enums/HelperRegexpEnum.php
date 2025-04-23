<?php

declare(strict_types=1);

namespace Atlcom\Enums;

enum HelperRegexpEnum: string
{
    case Email = '/^([A-Za-z0-9\_\.\-]+)@([0-9A-Za-z\_\.\-]+)\.([A-Za-z\.]{2,6})$/';
    case Pattern = '/^\/.+\/[a-z]*$/i';
    case Phone = '/^[\+]{0,1}[0-9\-\(\)\s]*$/';
    case Uuid = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/iD';
    case Ascii = '/[^\x09\x10\x13\x0A\x0D\x20-\x7E]/';
    case Unicode = '/[\x{0080}-\x{FFFF}]+/u';
    case Date = '/[0-9]{2}[\.\-\/][0-9]{2}[\.\-\/][0-9]{4}|[0-9]{4}[\.\-\/][0-9]{2}[\.\-\/][0-9]{2}/u';
    case Url = '/^(https?|ftp):\/\/([a-z0-9\-._~%!$&\'()*+,;=:]+(:[a-z0-9\-._~%!$&\'()*+,;=]*)?@)?((([a-z0-9\-]+(\.[a-z0-9\-]+)+)|(\d{1,3}(\.\d{1,3}){3})))(:\d{1,5})?([\/][^\s\?#]*)?(\?[^\s#]*)?(#[a-z0-9\-]*)?$/i';
}
