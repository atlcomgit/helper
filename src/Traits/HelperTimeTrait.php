<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Carbon\Carbon;

/**
 * Трейт для работы со временем
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


    /**
     * Возвращает массив названий периодов
     *
     * @return array
     */
    public static function timePluralNames(): array
    {
        return [
            static::$nameYears => static::$pluralYears,
            static::$nameMonths => static::$pluralMonth,
            static::$nameDays => static::$pluralDays,
            static::$nameHours => static::$pluralHours,
            static::$nameMinutes => static::$pluralMinutes,
            static::$nameSeconds => static::$pluralSeconds,
        ];
    }


    /**
     * Конвертирует период между датами в массив YDHM
     *
     * @param Carbon|string $dateFrom
     * @param Carbon|string $dateTo
     * @return array
     */
    public static function timePeriodBetweenDatesToArray(Carbon|string $dateFrom, Carbon|string $dateTo): array
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
     * Конвертирует период между датами в строку YDHM
     *
     * @param Carbon|string $dateFrom
     * @param Carbon|string $dateTo
     * @param bool $includeTime
     * @param bool $time24
     * @param mixed 
     * @return string
     */
    public static function timePeriodBetweenDatesToString(
        Carbon|string $dateFrom,
        Carbon|string $dateTo,
        bool $includeTime = false,
        bool $time24 = false,
    ): string {
        $period = (object)static::timePeriodBetweenDatesToArray($dateFrom, $dateTo);

        return trim(
            ($period->years
                ? static::plural($period->years, static::$pluralYears) . " "
                : ''
            ) .
            ($period->months
                ? static::plural($period->months, static::$pluralMonth) . " "
                : ''
            ) .
            static::plural($period->days, $time24 ? static::$pluralDays24 : static::$pluralDays)
            . " " .
            (($includeTime && $period->hours)
                ? static::plural($period->hours, static::$pluralHours) . " "
                : ''
            ) .
            (($includeTime && $period->minutes)
                ? static::plural($period->minutes, static::$pluralMinutes) . " "
                : ''
            ) .
            (($includeTime && $period->seconds)
                ? static::plural($period->seconds, static::$pluralSeconds) . " "
                : ''
            )
        );
    }


    /**
     * Конвертирует секунды в массив YDHM
     *
     * @param int|float $value
     * @return array
     */
    public static function timeSecondsToArray(int|float $value): array
    {
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

        $result[static::$nameSeconds] = $value;

        return $result;
    }


    /**
     * Конвертирует секунды в строку времени YDHM
     *
     * @param int|float $value
     * @param bool $withZero
     * @param array $pluralNames
     * @param mixed 
     * @return string
     */
    public static function timeSecondsToString(
        int|float $value,
        bool $withZero = false,
        array $pluralNames = [],
    ): string {
        $result = '';
        !$pluralNames ?: $pluralNames = static::timePluralNames();

        if ($value < 0) {
            return 'Отрицательное число';
        }

        if ($value < 1) {
            $value = (int)($value * 1000);

            return static::plural($value, static::$pluralMilliseconds);
        }

        if ($value < 5) {
            return round($value, 1) . ' секунды';
        }

        $timeArray = static::timeSecondsToArray((int)$value);

        foreach ($pluralNames as $periodName => $periodPluralNames) {
            $periodValue = $timeArray[$periodName] ?? 0;
            if ($withZero || $periodValue > 0) {
                $result .= static::plural($periodValue, $periodPluralNames) . " ";
            }
        }

        return trim($result);
    }
}
