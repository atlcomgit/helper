<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с переменными
 * @mixin \Atlcom\Helper
 */
trait HelperVarTrait
{
    /**
     * Меняет местами значения value1 и value2
     * @see ../../tests/HelperVarTrait/HelperVarSwapTest.php
     *
     * @param int|float|string|null $value1
     * @param int|float|string|null $value2
     * @return void
     */
    public static function varSwap(int|float|string|null &$value1, int|float|string|null &$value2): void
    {
        $temp = $value1;
        $value1 = $value2;
        $value2 = $temp;
    }
}
