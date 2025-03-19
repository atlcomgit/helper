<?php

declare(strict_types=1);

namespace Atlcom\Traits;

trait HelperStringTrait
{
    /**
     * Проверяет значение на вхождение в интервал(ы)
     *
     * @param mixed $value
     * @param mixed ...$intervals
     * @return bool
     */
    public static function inInterval(mixed $value, mixed ...$intervals): bool
    {
        $items = [];

        foreach ($intervals as $interval) {
            switch (true) {
                case is_string($interval):
                    $subIntervals = explode(',', str_replace(['|', ';'], ',', $interval));
                    foreach ($subIntervals as $subInterval) {
                        $subInterval = trim($subInterval);

                        if (str_contains($subInterval, '..')) {
                            $subInterval = explode(',', str_replace(['...', '..'], ',', trim($subInterval)));
                            $min = $subInterval[0] ?? null;
                            $max = $subInterval[1] ?? null;
                            $items[] = [
                                ...(is_null($min) ? [] : ['min' => trim($min)]),
                                ...(is_null($max) ? [] : ['max' => trim($max)]),
                            ];
                        } else if ($subInterval !== '' && $subInterval !== 'null') {
                            $items[] = ['equal' => $subInterval];
                        }
                    }
                    break;

                case is_array($interval):
                    if (count($interval) === 1) {
                        if ($interval[0] ?? null) {
                            $items[] = ['equal' => $interval[0] ?: 0];
                        }
                    } else {
                        $items[] = ['min' => ($interval[0] ?? 0) ?: 0, 'max' => ($interval[1] ?? 0) ?: 0];
                    }
                    break;

                default:
                    if (!is_null($interval)) {
                        $items[] = ['equal' => $interval ?: 0];
                    }
            };
        }

        foreach ($items as $item) {
            if (isset($item['equal']) && $value == $item['equal']) {
                return true;
            } else if (isset($item['min']) && isset($item['max'])) {
                if ($value >= $item['min'] && $value <= $item['max']) {
                    return true;
                }
            } else if (isset($item['min']) && $value >= $item['min']) {
                return true;
            } else if (isset($item['max']) && $value <= $item['max']) {
                return true;
            }
        }

        return false;
    }
}
