<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Carbon\Carbon;

/**
 * Трейт для работы со временем
 * @mixin \Atlcom\Helper
 */
trait HelperTimeTrait
{
    public static array $pluralYears = ['лет', 'год', 'года'];
    public static array $pluralMonth = ['месяцев', 'месяц', 'месяца'];
    public static array $pluralDays = ['дней', 'день', 'дня'];
    public static array $pluralDays24 = ['суток', 'сутки', 'суток'];
    public static array $pluralHours = ['часов', 'час', 'часа'];
    public static array $pluralMinutes = ['минут', 'минута', 'минуты'];
    public static array $pluralSeconds = ['секунд', 'секунда', 'секунды'];
    public static array $pluralMilliseconds = ['миллисекунд', 'миллисекунда', 'миллисекунды'];

    public static string $nameYears = 'years';
    public static string $nameMonths = 'months';
    public static string $nameDays = 'days';
    public static string $nameHours = 'hours';
    public static string $nameMinutes = 'minutes';
    public static string $nameSeconds = 'seconds';
    public static string $nameMilliSeconds = 'milliseconds';


    /**
     * Возвращает массив периодов между датами
     * @see ../../tests/HelperTimeTrait/HelperTimeBetweenDatesToArrayTest.php
     *
     * @param Carbon|string|null $dateFrom
     * @param Carbon|string|null $dateTo
     * @return array
     */
    public static function timeBetweenDatesToArray(Carbon|string|null $dateFrom, Carbon|string|null $dateTo): array
    {
        $result = [
            static::$nameYears => $years = 0,
            static::$nameMonths => $months = 0,
            static::$nameDays => $days = 0,
            static::$nameHours => $hours = 0,
            static::$nameMinutes => $minutes = 0,
            static::$nameSeconds => $seconds = 0,
        ];

        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        if ($dateFrom > $dateTo) {
            [$dateFrom, $dateTo] = [$dateTo, $dateFrom];
        }

        $dateFromYear = $dateFrom->year;
        $dateFromMonth = $dateFrom->month;
        $dateFromDay = $dateFrom->day;
        $dateFromTime = $dateFrom->format('H:i:s');
        $dateFromHour = $dateFrom->hour;
        $dateFromMinute = $dateFrom->minute;
        $dateFromSecond = $dateFrom->second;
        $dateFromDaysInMonth = $dateFrom->daysInMonth;

        $dateToYear = $dateTo->year;
        $dateToMonth = $dateTo->month;
        $dateToDay = $dateTo->day;
        $dateToDayTime = $dateTo->format('H:i:s');
        $dateToHour = $dateTo->hour;
        $dateToMinute = $dateTo->minute;
        $dateToSecond = $dateTo->second;

        $isEqualMonthDay = $dateFromMonth == $dateToMonth && $dateFromDay == $dateToDay;

        $months = ($dateToYear * 12 + $dateToMonth)
            - ($dateFromYear * 12 + $dateFromMonth)
            - (int)($dateFromDay > $dateToDay || ($isEqualMonthDay && $dateFromTime > $dateToDayTime));
        $years = intval($months / 12);
        $months -= $years * 12;
        $days = $isEqualMonthDay
            ? (($dateFromTime > $dateToDayTime) ? $dateFromDaysInMonth - 1 : 0)
            : (((
                $dateFromDay < $dateToDay)
                ? ($dateToDay - $dateFromDay)
                : (($dateFromDaysInMonth - $dateFromDay) + $dateToDay)
            ) - (int)($dateFromTime > $dateToDayTime));
        $seconds = abs($dateToHour * 3600 + $dateToMinute * 60 + $dateToSecond)
            - ($dateFromHour * 3600 + $dateFromMinute * 60 + $dateFromSecond)
            + ($dateFromTime > $dateToDayTime ? 86400 : 0);
        $hours = intval($seconds / 3600);
        $minutes = intval(($seconds - $hours * 3600) / 60);
        $seconds -= $hours * 3600 + $minutes * 60;

        return compact(array_keys($result));
    }


    /**
     * Возвращает период между датами в виде строки YDHM
     * @see ../../tests/HelperTimeTrait/HelperTimeBetweenDatesToStringTest.php
     *
     * @param Carbon|string|null $dateFrom
     * @param Carbon|string|null $dateTo
     * @param bool $withTime
     * @param bool $time24
     * @param mixed 
     * @return string
     */
    public static function timeBetweenDatesToString(
        Carbon|string|null $dateFrom,
        Carbon|string|null $dateTo,
        bool $withTime = false,
        bool $time24 = false,
    ): string {
        $period = (object)static::timeBetweenDatesToArray($dateFrom, $dateTo);

        return trim(
            ($period->years
                ? static::stringPlural($period->years, static::$pluralYears) . " "
                : ''
            ) .
            ($period->months
                ? static::stringPlural($period->months, static::$pluralMonth) . " "
                : ''
            ) .
            static::stringPlural($period->days, $time24 ? static::$pluralDays24 : static::$pluralDays)
            . " " .
            (($withTime && $period->hours)
                ? static::stringPlural($period->hours, static::$pluralHours) . " "
                : ''
            ) .
            (($withTime && $period->minutes)
                ? static::stringPlural($period->minutes, static::$pluralMinutes) . " "
                : ''
            ) .
            (($withTime && $period->seconds)
                ? static::stringPlural($period->seconds, static::$pluralSeconds) . " "
                : ''
            )
        );
    }


    /**
     * Возвращает массив периодов из количества секунд
     * @see ../../tests/HelperTimeTrait/HelperTimeSecondsToArrayTest.php
     *
     * @param int|float|string|null $value
     * @return array
     */
    public static function timeSecondsToArray(int|float|string|null $value): array
    {
        $value = filter_var($value, FILTER_VALIDATE_FLOAT);
        $result = [];
        $countZero = false;
        $periods = [
            static::$nameYears => 31536000,
            static::$nameDays => 86400,
            static::$nameHours => 3600,
            static::$nameMinutes => 60,
        ];

        foreach ($periods as $periodName => $periodValue) {
            $periodSeconds = (int)floor($value / $periodValue);
            if (($periodSeconds > 0) || ($periodSeconds == 0 && $countZero)) {
                $result[$periodName] = $periodSeconds;
                $value -= (int)($periodSeconds * $periodValue);
                $countZero = true;

            } else {
                $result[$periodName] = 0;
            }

        }

        $result[static::$nameSeconds] = (int)$value;
        $result[static::$nameMilliSeconds] = (int)(($value - (int)$value) * 1000);

        return $result;
    }


    /**
     * Возвращает период из количества секунд в виде строки YDHM
     * @see ../../tests/HelperTimeTrait/HelperTimeSecondsToStringTest.php
     *
     * @param int|float|string|null $value
     * @param bool $withZero
     * @param bool $withMilliseconds
     * @param array $pluralNames
     * @param mixed 
     * @return string
     */
    public static function timeSecondsToString(
        int|float|string|null $value,
        bool $withZero = false,
        bool $withMilliseconds = false,
        array $pluralNames = [],
    ): string {
        $result = '';
        $pluralNames ?: $pluralNames = [
            static::$nameYears => static::$pluralYears,
            static::$nameMonths => static::$pluralMonth,
            static::$nameDays => static::$pluralDays,
            static::$nameHours => static::$pluralHours,
            static::$nameMinutes => static::$pluralMinutes,
            static::$nameSeconds => static::$pluralSeconds,
        ];

        if (is_null($value)) {
            return '';
        }

        $value = filter_var($value, FILTER_VALIDATE_FLOAT) ?: 0;

        if ($value < 0) {
            return 'Отрицательное число';
        }

        if ($withMilliseconds && $value < 1) {
            $value = (int)($value * 1000);

            return static::stringPlural($value, static::$pluralMilliseconds);
        }

        if ($value < 5) {
            $milliseconds = (int)(($value - (int)$value) * 1000);

            return $withMilliseconds
                ? static::stringPlural((int)$value, static::$pluralSeconds) . (
                    $withZero
                    ? ' ' . static::stringPlural($milliseconds, static::$pluralMilliseconds)
                    : ($milliseconds ? ' ' . static::stringPlural($milliseconds, static::$pluralMilliseconds) : '')
                )
                : (
                    static::stringSplit($value, '.', 1)
                    ? round($value, 1) . ' ' . static::stringPlural(2, static::$pluralSeconds, false)
                    : static::stringPlural(round($value, 1), static::$pluralSeconds)
                );
        }

        $timeArray = static::timeSecondsToArray((int)$value);
        $canZero = false;

        foreach ($pluralNames as $periodName => $periodPluralNames) {
            $periodValue = $timeArray[$periodName] ?? 0;
            if (
                $withZero && ($canZero || $periodValue === static::$nameSeconds)
                || $periodValue > 0
            ) {
                $result .= static::stringPlural($periodValue, $periodPluralNames) . " ";
                $canZero = true;
            }
        }

        if ($withMilliseconds) {
            $milliseconds = (int)(($value - (int)$value) * 1000);
            $result .= $withZero
                ? static::stringPlural($milliseconds, static::$pluralMilliseconds)
                : ($milliseconds ? static::stringPlural($milliseconds, static::$pluralMilliseconds) : '');
        }

        return trim($result);
    }
}
