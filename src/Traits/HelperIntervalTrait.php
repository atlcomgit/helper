<?php

declare(strict_types=1);

namespace Atlcom\Traits;

/**
 * Трейт для работы с интервалами
 */
trait HelperIntervalTrait
{
    public static string $nameCollectionItemEqual = 'equal';
    public static string $nameCollectionItemMin = 'min';
    public static string $nameCollectionItemMax = 'max';


    /**
     * Возвращает число со смещением по кругу в заданном интервале
     * @see ../../tests/HelperIntervalTrait/HelperIntervalAroundTest.php
     *
     * @param int $value
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function intervalAround(int $value, int $min, int $max): int
    {
        $result = $value;
        if ($min > $max) {
            $m = $max;
            $max = $min;
            $min = $m;
        }

        if ($value < $min || $value > $max) {
            $result = ($value > $max)
                ? (($value - $min) % ($max - $min + 1)) + $min
                : $max - (
                    abs($value - $min + 1)
                    - abs($max - $min + 1)
                    * floor(abs($value - $min + 1) / ($max - $min + 1))
                );
        }

        return (int)$result;
    }


    /**
     * Возвращает коллекцию интервалов
     * @see ../../tests/HelperIntervalTrait/HelperIntervalCollectionTest.php
     *
     * @param mixed ...$intervals
     * @return array
     */
    public static function intervalCollection(mixed ...$intervals): array
    {
        $collection = [];

        foreach ($intervals as $interval) {
            switch (true) {
                case is_string($interval):
                    $subIntervals = explode(',', str_replace(['|', ';'], ',', $interval));

                    foreach ($subIntervals as $subInterval) {
                        $subInterval = trim($subInterval);

                        if (str_contains($subInterval, '..')) {
                            $subInterval = explode(',', str_replace(['...', '..'], ',', trim($subInterval)));
                            $min = $subInterval[0] ?? null;
                            !($min === '') ?: $min = null;
                            $max = $subInterval[1] ?? null;
                            !($max === '') ?: $max = null;
                            $collection[] = [
                                ...(is_null($min) ? [] : [static::$nameCollectionItemMin => trim($min)]),
                                ...(is_null($max) ? [] : [static::$nameCollectionItemMax => trim($max)]),
                            ];

                        } else if ($subInterval !== '' && $subInterval !== 'null') {
                            $collection[] = [static::$nameCollectionItemEqual => $subInterval];
                        }
                    }
                    break;

                case is_array($interval):
                    if (count($interval) === 1) {
                        $item = $interval[0] ?? null;

                        if (is_array($item)) {
                            !($collectionSub = static::intervalCollection($item))
                                ?: $collection = [...$collection, ...$collectionSub];

                        } else if (str_contains((string)$item, ',') || str_contains((string)$item, '..')) {
                            !($collectionSub = static::intervalCollection($item))
                                ?: $collection = [...$collection, ...$collectionSub];

                        } else if ($item) {
                            $collection[] = [static::$nameCollectionItemEqual => $item];
                        }

                    } else {
                        $item1 = $interval[0] ?? null;

                        if (
                            !is_array($item1)
                            && (str_contains((string)$item1, ',') || str_contains((string)$item1, '..'))
                        ) {
                            !($collectionSub = static::intervalCollection(...$interval))
                                ?: $collection = [...$collection, ...$collectionSub];

                        } else {
                            $item2 = $interval[1] ?? null;
                            if (count($interval) === 2 && is_null($item1) && is_null($item2)) {
                                $collection[] = [];

                            } else if (count($interval) === 2 && !is_array($item1) && !is_array($item2)) {
                                $collection[] = [
                                    static::$nameCollectionItemMin => $item1,
                                    static::$nameCollectionItemMax => $item2,
                                ];

                            } else {
                                !($collectionSub = static::intervalCollection(...$interval))
                                    ?: $collection = [...$collection, ...$collectionSub];
                            }
                        }

                    }
                    break;

                default:
                    if (!is_null($interval)) {
                        $collection[] = [static::$nameCollectionItemEqual => $interval ?: 0];
                    }
            };
        }

        return $collection;
    }


    /**
     * Проверяет значение на вхождение в интервал(ы)
     * @see ../../tests/HelperIntervalTrait/HelperIntervalBetweenTest.php
     *
     * @param mixed $value
     * @param mixed ...$intervals
     * @return array
     */
    public static function intervalBetween(mixed $value, mixed ...$intervals): array
    {
        $collection = static::intervalCollection(...$intervals);
        $result = [];
        $value = (string)$value;

        foreach ($collection as $item) {
            if (
                isset($item[static::$nameCollectionItemEqual])
                && $value == (string)$item[static::$nameCollectionItemEqual]
            ) {
                $result[] = $item[static::$nameCollectionItemEqual];

            } else if (
                isset($item[static::$nameCollectionItemMin])
                && isset($item[static::$nameCollectionItemMax])
            ) {
                if (
                    $value >= (string)$item[static::$nameCollectionItemMin]
                    && $value <= (string)$item[static::$nameCollectionItemMax]
                ) {
                    $result[] = implode('..', array_values($item));
                }

            } else if (
                isset($item[static::$nameCollectionItemMin])
                && $value >= (string)$item[static::$nameCollectionItemMin]
            ) {
                $result[] = implode('..', array_values($item));

            } else if (
                isset($item[static::$nameCollectionItemMax])
                && $value <= (string)$item[static::$nameCollectionItemMax]
            ) {
                $result[] = implode('..', array_values($item));
            } else if (
                isset($item[static::$nameCollectionItemMin])
                && isset($item[static::$nameCollectionItemMax])
                && is_null($item[static::$nameCollectionItemMin])
                && is_null($item[static::$nameCollectionItemMax])
            ) {
                $result[] = '..';

            } else if (empty($item)) {
                $result[] = '..';
            }
        }

        return array_values($result);
    }


    /**
     * Проверяет пересечение интервалов
     * @see ../../tests/HelperIntervalTrait/HelperIntervalOverlapTest.php
     *
     * @param mixed ...$intervals
     * @return array
     */
    public static function intervalOverlap(mixed ...$intervals): array
    {
        $result = [];
        $collection = static::intervalCollection(...$intervals);

        array_walk(
            $collection,
            fn (&$item) => $item = match (true) {
                isset($item[static::$nameCollectionItemEqual]) => array_values($item),

                default => [
                    $item[static::$nameCollectionItemMin] ?? null,
                    $item[static::$nameCollectionItemMax] ?? null,
                ],
            }
        );

        while ($collection) {
            $item = array_shift($collection);
            $itemInterval = [count($item) === 1 ? $item[0] : "{$item[0]}..{$item[1]}"];

            foreach ($item as $itemValue) {
                $itemBetween = static::intervalBetween($itemValue, $collection);
                !$itemBetween ?: $result = [...$result, ...$itemInterval, ...$itemBetween];
            }
        }

        return array_values(array_unique($result));
    }
}
