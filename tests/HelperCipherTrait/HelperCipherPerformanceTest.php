<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCipherTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Тест производительности шифрования
 * @see \Atlcom\Traits\HelperCipherTrait
 */
final class HelperCipherPerformanceTest extends TestCase
{
    /**
     * Сравнивает время шифрования и дешифрования старым и новым алгоритмами
     * @see \Atlcom\Traits\HelperCipherTrait::cipherEncode()
     *
     * @return void
     */
    #[Test]
    public function cipherPerformance(): void
    {
        $values = [
            null,
            'Строка данных для проверки производительности',
            str_repeat('binary', 16),
            ['key' => 'value', 'nested' => ['flag' => true, 'count' => 42]],
        ];
        $iterations = 2;
        $cipherPassword = 'CipherPerf#1';
        $cryptPassword = 'CryptPerf#1';

        $legacyDuration = static::measure(static function () use ($values, $iterations, $cryptPassword) {
            for ($index = 0; $index < $iterations; $index++) {
                foreach ($values as $value) {
                    $encoded = Helper::cryptEncode($value, $cryptPassword, true);
                    Helper::cryptDecode($encoded, $cryptPassword);
                }
            }
        });

        $cipherDuration = static::measure(static function () use ($values, $iterations, $cipherPassword) {
            for ($index = 0; $index < $iterations; $index++) {
                foreach ($values as $value) {
                    $encoded = Helper::cipherEncode($value, $cipherPassword);
                    Helper::cipherDecode($encoded, $cipherPassword);
                }
            }
        });

        $this->assertGreaterThan(0.0, $legacyDuration, 'legacy duration empty');
        $this->assertGreaterThan(0.0, $cipherDuration, 'cipher duration empty');
        $this->assertGreaterThanOrEqual($legacyDuration, $cipherDuration);
        $this->assertLessThan(5.0, $cipherDuration, 'Cipher encode/decode должен укладываться в 5 секунд.');
    }


    /**
     * Высчитывает время выполнения переданного колбэка
     *
     * @param callable $callback
     * @return float
     */
    private static function measure(callable $callback): float
    {
        $start = microtime(true);
        $callback();

        return microtime(true) - $start;
    }
}
