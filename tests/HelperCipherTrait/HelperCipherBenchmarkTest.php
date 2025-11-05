<?php

declare(strict_types=1);

namespace Atlcom\Tests\HelperCipherTrait;

use Atlcom\Helper;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * Бенчмарк шифрования и дешифрования
 * @see \Atlcom\Traits\HelperCipherTrait
 */
final class HelperCipherBenchmarkTest extends TestCase
{
    /**
     * Сравнивает время и память crypt* и cipher*
     * @see \Atlcom\Traits\HelperCipherTrait::cipherEncode()
     *
     * @return void
     */
    #[Test]
    public function comparePerformance(): void
    {
        $values = [
            null,
            'Производительность нового шифрования',
            str_repeat('payload', 32),
            ['key' => 'value', 'nested' => ['flag' => true, 'count' => 42]],
        ];
        $iterations = 5;
        $password = 'PerfBench#1';

        $legacyLengths = [];
        $cipherLengths = [];

        $legacyMetrics = static::collectMetrics(static function () use ($values, $iterations, $password, &$legacyLengths) {
            for ($index = 0; $index < $iterations; $index++) {
                foreach ($values as $value) {
                    $encoded = Helper::cryptEncode($value, $password, true);
                    $legacyLengths[] = strlen($encoded);
                    Helper::cryptDecode($encoded, $password);
                }
            }
        });

        $cipherMetrics = static::collectMetrics(static function () use ($values, $iterations, $password, &$cipherLengths) {
            for ($index = 0; $index < $iterations; $index++) {
                foreach ($values as $value) {
                    $encoded = Helper::cipherEncode($value, $password);
                    $cipherLengths[] = strlen($encoded);
                    Helper::cipherDecode($encoded, $password);
                }
            }
        });

        $this->assertGreaterThan(0.0, $legacyMetrics['time']);
        $this->assertGreaterThan(0.0, $cipherMetrics['time']);
        $this->assertGreaterThanOrEqual(0, $legacyMetrics['memory']);
        $this->assertGreaterThanOrEqual(0, $cipherMetrics['memory']);
        $this->assertLessThan(5.0, $cipherMetrics['time']);
        $this->assertLessThan(5.0, $legacyMetrics['time']);
        $this->assertLessThan(8 * 1024 * 1024, $cipherMetrics['memory']);
        $this->assertLessThan(8 * 1024 * 1024, $legacyMetrics['memory']);

        $legacyLengthAvg = static::average($legacyLengths);
        $legacyLengthMin = $legacyLengths !== [] ? min($legacyLengths) : 0;
        $legacyLengthMax = $legacyLengths !== [] ? max($legacyLengths) : 0;
        $cipherLengthAvg = static::average($cipherLengths);
        $cipherLengthMin = $cipherLengths !== [] ? min($cipherLengths) : 0;
        $cipherLengthMax = $cipherLengths !== [] ? max($cipherLengths) : 0;

        fwrite(
            STDOUT,
            sprintf(
                "\ncryptEncode:  time=%.4fs, memory=%d bytes, length avg=%.2f (min=%d, max=%d)\n",
                $legacyMetrics['time'],
                $legacyMetrics['memory'],
                $legacyLengthAvg,
                $legacyLengthMin,
                $legacyLengthMax,
            ),
        );

        fwrite(
            STDOUT,
            sprintf(
                "cipherEncode: time=%.4fs, memory=%d bytes, length avg=%.2f (min=%d, max=%d)\n",
                $cipherMetrics['time'],
                $cipherMetrics['memory'],
                $cipherLengthAvg,
                $cipherLengthMin,
                $cipherLengthMax,
            ),
        );
    }


    /**
     * Собирает метрики времени и памяти
     *
     * @param callable $callback
     * @return array{time: float, memory: int}
     */
    private static function collectMetrics(callable $callback): array
    {
        gc_collect_cycles();
        $beforeUsage = memory_get_usage(true);
        $beforePeak = memory_get_peak_usage(true);
        $start = microtime(true);

        $callback();

        $time = microtime(true) - $start;
        $afterUsage = memory_get_usage(true);
        $afterPeak = memory_get_peak_usage(true);
        $memory = max(0, max($afterUsage - $beforeUsage, $afterPeak - $beforePeak));

        return [
            'time' => $time,
            'memory' => $memory,
        ];
    }


    /**
     * Возвращает среднее арифметическое массива чисел
     *
     * @param array<int, int> $values
     * @return float
     */
    private static function average(array $values): float
    {
        if ($values === []) {
            return 0.0;
        }

        return array_sum($values) / count($values);
    }
}
