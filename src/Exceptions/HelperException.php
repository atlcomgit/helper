<?php

declare(strict_types=1);

namespace Atlcom\Exceptions;

use Exception;

class HelperException extends Exception
{
    public $code = 500;
}
