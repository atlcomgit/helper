<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperArrayTrait;

use Atlcom\Helper;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест метода трейта
 * @see \Atlcom\Traits\HelperArrayTrait
 */
final class HelperArrayTruncateStringValuesTest extends TestCase
{
    /**
     * Тест ограничения строковых значений массива
     * @see \Atlcom\Traits\HelperArrayTrait::arrayTruncateStringValues()
     *
     * @return void
     */
    #[Test]
    public function arrayTruncateStringValues(): void
    {
        $now = Carbon::now();

        $array = Helper::arrayTruncateStringValues(['short', 'veryLong'], 4);
        $this->assertSame(['sho…', 'ver…'], $array);

        $array = Helper::arrayTruncateStringValues(['short', 'veryLong'], 0);
        $this->assertSame(['', ''], $array);

        $array = Helper::arrayTruncateStringValues(['short', 'veryLong'], 1);
        $this->assertSame(['…', '…'], $array);

        $array = Helper::arrayTruncateStringValues(['short', 'veryLong'], null);
        $this->assertSame(['short', 'veryLong'], $array);

        $array = Helper::arrayTruncateStringValues([
            'nested' => [
                'deep' => 'знаковый',
                'skip' => 123,
                'object' => $now,
            ],
            'keep' => 'ok',
        ], 5);

        $this->assertSame([
            'nested' => [
                'deep' => 'знак…',
                'skip' => 123,
                'object' => $now,
            ],
            'keep' => 'ok',
        ], $array);

        $array = Helper::arrayTruncateStringValues(['mixed' => ['inner' => ['string' => 'abcdef']]], 0);
        $this->assertSame(['mixed' => ['inner' => ['string' => '']]], $array);

        $array = Helper::arrayTruncateStringValues(null, 10);
        $this->assertSame([], $array);

        $array = Helper::arrayTruncateStringValues(null, null);
        $this->assertSame([], $array);
    }
}
