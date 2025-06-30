<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Internal\HelperTry;

/**
 * Трейт для работы с try
 * @mixin \Atlcom\Helper
 * // @mixin \Atlcom\Internal\HelperTry
 */
trait HelperTryTrait
{
    /**
     * Возвращает объект для работы с цепочкой try catch finally
     * @see ../../tests/HelperTryTrait/HelperTryTest.php
     *
     * @param callable $callable
     * @return HelperTry
     */
    public static function try(callable $callable): HelperTry
    {
        return (new HelperTry())->try($callable);
    }
}
