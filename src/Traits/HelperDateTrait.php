<?php

declare(strict_types=1);

namespace Atlcom\Traits;

use Atlcom\Consts\HelperConsts as Consts;
use Atlcom\Enums\HelperNumberDeclensionEnum as Declension;
use Atlcom\Enums\HelperNumberEnumerationEnum as Enumeration;
use Atlcom\Enums\HelperNumberGenderEnum as Gender;
use Carbon\Carbon;

/**
 * Трейт для работы с датами
 */
trait HelperDateTrait
{
    /**
     * Возвращает объект Carbon с распознанной датой или null
     * @see ../../tests/HelperDateTrait/HelperDateFromStringTest.php
     *
     * @param string|null $value
     * @return Carbon|null
     */
    public static function dateFromString(?string $value): ?Carbon
    {
        $value = ' ' . static::stringReplace(static::stringLower($value), [',' => ' , ']) . ' ';
        $date = Carbon::today()->setTime(0, 0, 0, 0);

        $keywords = [
            $today = 'сегодня',
            $tomorrow = 'завтра',
            $yesterday = 'вчера',
            $afterTomorrow = 'послезавтра',
            $beforeYesterday = 'позавчера',

            $monday = 'понедельник',
            $tuesday = 'вторник',
            $wednesday = 'сред',
            $thursday = 'четверг',
            $friday = 'пятниц',
            $saturday = 'суббот',
            $sunday = 'воскресен',

            $january = '/январ(ь|я|е|ю)/ui',
            $february = '/феврал(ь|я|е|ю)/ui',
            $march = '/март(а|е|у)/ui',
            $april = '/апрел(ь|я|е|ю)/ui',
            $may = '/ма(й|я|е|ю)/ui',
            $june = '/июн(ь|я|е|ю)/ui',
            $july = '/июл(ь|я|е|ю)/ui',
            $august = '/август(а|е|у)/ui',
            $september = '/сентябр(ь|я|е|ю)/ui',
            $october = '/октябр(ь|я|е|ю)/ui',
            $november = '/ноябр(ь|я|е|ю)/ui',
            $december = '/декабр(ь|я|е|ю)/ui',
            $year = '/\d{4}/u',

            $currentDay = "/это(т|й|м)\s*($monday|$tuesday|$wednesday|$thursday|$friday|$saturday|$sunday)/u",
            $prevDay = "/прошл(ой|ый|ую|ом|ое)\s*($monday|$tuesday|$wednesday|$thursday|$friday|$saturday|$sunday)/u",
            $prevDay2 = "/позапрошл(ой|ый|ую|ом|ое)\s*($monday|$tuesday|$wednesday|$thursday|$friday|$saturday|$sunday)/u",
            $nextDay = "/следующ(ий|ие|их|ем|ей|ую)\s*($monday|$tuesday|$wednesday|$thursday|$friday|$saturday|$sunday)/u",
            $nextDay2 = '/следующ(ий|ие|их)\s*(\d*)\s*(день|дня|дней)/u',
            $afterDay = '/через\s*(\d*)\s*(день|дня|дней)/u',
            $beforeDay = '/прошл(ый|ую|ом)\s*(\d*)\s*(день|дня|дней)/u',
            $plusDay = '/плюс\s*(\d*)\s*(день|дня|дней)/u',
            $minusDay = '/минус\s*(\d*)\s*(день|дня|дней)/u',

            $nextWeek = '/следующ(ей|ую)\s*недел(ю|и|ь|е)/u',
            $prevWeek = '/прошл(ой|ую|ый|ое)\s*недел(ю|и|ь|е)/u',
            $prevWeek2 = '/позапрошл(ой|ую|ый|ое)\s*недел(ю|и|ь|е)/u',
            $afterWeek = '/через\s*(\d*)\s*недел(ю|и|ь|е)/u',
            $beforeWeek = '/(\d*)\s*недел(ю|и|ь|е)\s*назад/u',
            $plusWeek = '/плюс\s*(\d*)\s*недел(ю|и|ь|е)/u',
            $minusWeek = '/минус\s*(\d*)\s*недел(ю|и|ь|е)/u',

            $nextStartWeek = '/начал(о|е)\s*следующ(ей|ую)\s*недел(ю|и|ь|е)/u',
            $nextMiddleWeek = '/середин(а|е|у)\s*следующ(ей|ую)\s*недел(ю|и|ь|е)/u',
            $nextEndWeek = '/кон(ец|це|цу)\s*следующ(ей|ую)\s*недел(ю|и|ь|е)/u',

            $prevStartWeek = '/начал(о|е)\s*прошл(ой|ую|ый|ое)\s*недел(ю|и|ь|е)/u',
            $prevMiddleWeek = '/середин(а|е|у)\s*прошл(ой|ую|ый|ое)\s*недел(ю|и|ь|е)/u',
            $prevEndWeek = '/кон(ец|це|цу)\s*прошл(ой|ую|ый|ое)\s*недел(ю|и|ь|е)/u',

            $nextMonth = '/следующ(ем|ий|его)\s*месяц/u',
            $prevMonth = '/прошл(ый|ом|ые|ого)\s*месяц/u',
            $prevMonth2 = '/позапрошл(ый|ом|ые|ого)\s*месяц/u',
            $afterMonth = '/через\s*(\d*)\s*месяц/u',
            $beforeMonth = '/(\d*)\s*месяц\s*назад/u',
            $plusMonth = '/плюс\s*(\d*)\s*месяц/u',
            $minusMonth = '/минус\s*(\d*)\s*месяц/u',

            $currStartMonth = '/начал(о|е)\s*месяц/u',
            $currMiddleMonth = '/середин(а|е|у)\s*месяц/u',
            $currEndMonth = '/кон(ец|це|цу)\s*месяц/u',

            $nextStartMonth = '/начал(о|е)\s*следующ(его|ий|ем)\s*месяц/u',
            $nextMiddleMonth = '/середин(а|е|у)\s*следующ(его|ий|ем)\s*месяц/u',
            $nextEndMonth = '/кон(ец|це|цу)\s*следующ(его|ий|ем)\s*месяц/u',

            $prevStartMonth = '/начал(о|е)\s*прошл(ого|ый|ем)\s*месяц/u',
            $prevMiddleMonth = '/середин(а|е|у)\s*прошл(ого|ый|ем)\s*месяц/u',
            $prevEndMonth = '/кон(ец|це|цу)\s*прошл(ого|ый|ем)\s*месяц/u',

            $nextYear = '/следующ(ем|ий|его)\s*(год|года|лет)/u',
            $prevYear = '/прошл(ый|ом|ые|ого)\s*(год|года|лет)/u',
            $prevYear2 = '/позапрошл(ый|ом|ые|ого)\s*(год|года|лет)/u',
            $afterYear = '/через\s*(\d*)\s*(год|года|лет)/u',
            $beforeYear = '/(\d*)\s*(год|года|лет)\s*назад/u',
            $plusYear = '/плюс\s*(\d*)\s*(год|года|лет)/u',
            $minusYear = '/минус\s*(\d*)\s*(год|года|лет)/u',

            $currStartYear = '/начал(о|е)\s*(год|года|лет)/u',
            $currMiddleYear = '/середин(а|е|у)\s*(год|года|лет)/u',
            $currEndYear = '/кон(ец|це|цу)\s*(год|года|лет)/u',

            $nextStartYear = '/начал(о|е)\s*следующ(его|ий|ем)\s*(год|года|лет)/u',
            $nextMiddleYear = '/середин(а|е|у)\s*следующ(его|ий|ем)\s*(год|года|лет)/u',
            $nextEndYear = '/кон(ец|це|цу)\s*следующ(его|ий|ем)\s*(год|года|лет)/u',

            $prevStartYear = '/начал(о|е)\s*прошл(ого|ый|ем)\s*(год|года|лет)/u',
            $prevMiddleYear = '/середин(а|е|у)\s*прошл(ого|ый|ем)\s*(год|года|лет)/u',
            $prevEndYear = '/кон(ец|це|цу)\s*прошл(ого|ый|ем)\s*(год|года|лет)/u',

            $numberDay = "/[\s,]{0,}\d{2}[\s,]{0,}/u",
            $numberDay2 = '/(\d*)\s*числ/u',
            $numberDate = '/\d{2}[-.\/]\d{2}[-.\/]\d{4}|\d{4}[-.\/]\d{2}[-.]\d{2}/u',
        ];

        $searched = static::stringSearchAll($value, $keywords);

        match (true) {
            in_array($beforeYesterday, $searched) => $changes['add_days'] = -2,
            in_array($afterTomorrow, $searched) => $changes['add_days'] = 2,
            in_array($tomorrow, $searched) => $changes['add_days'] = 1,
            in_array($yesterday, $searched) => $changes['add_days'] = -1,
            in_array($today, $searched) => $changes['today'] = 1,

            default => null,
        };

        !in_array($monday, $searched) ?: $changes['day_week'] = 1;
        !in_array($tuesday, $searched) ?: $changes['day_week'] = 2;
        !in_array($wednesday, $searched) ?: $changes['day_week'] = 3;
        !in_array($thursday, $searched) ?: $changes['day_week'] = 4;
        !in_array($friday, $searched) ?: $changes['day_week'] = 5;
        !in_array($saturday, $searched) ?: $changes['day_week'] = 6;
        !in_array($sunday, $searched) ?: $changes['day_week'] = 7;

        !in_array($january, $searched) ?: $changes['number_month'] = 1;
        !in_array($february, $searched) ?: $changes['number_month'] = 2;
        !in_array($march, $searched) ?: $changes['number_month'] = 3;
        !in_array($april, $searched) ?: $changes['number_month'] = 4;
        !in_array($may, $searched) ?: $changes['number_month'] = 5;
        !in_array($june, $searched) ?: $changes['number_month'] = 6;
        !in_array($july, $searched) ?: $changes['number_month'] = 7;
        !in_array($august, $searched) ?: $changes['number_month'] = 8;
        !in_array($september, $searched) ?: $changes['number_month'] = 9;
        !in_array($october, $searched) ?: $changes['number_month'] = 10;
        !in_array($november, $searched) ?: $changes['number_month'] = 11;
        !in_array($december, $searched) ?: $changes['number_month'] = 12;

        if (in_array($year, $searched)) {
            preg_match($year, $value, $matches);
            $changes['number_year'] = (int)($matches[1] ?? 0);
        }

        !in_array($currentDay, $searched) ?: $changes['add_weeks'] = 0;
        !in_array($prevDay, $searched) ?: $changes['add_weeks'] = -1;
        !in_array($prevDay2, $searched) ?: $changes['add_weeks'] = -2;
        !in_array($nextDay, $searched) ?: $changes['add_weeks'] = 1;

        // День

        if (in_array($nextDay, $searched)) {
            preg_match($nextDay, $value, $matches);
            $changes['add_days'] = (int)($matches[2] ?? 0) ?: 1;
        }

        if (in_array($nextDay2, $searched)) {
            preg_match($nextDay2, $value, $matches);
            $changes['add_days'] = (int)($matches[2] ?? 0) ?: 1;
        }

        if (in_array($afterDay, $searched)) {
            preg_match($afterDay, $value, $matches);
            $changes['add_days'] = (int)(($matches[1] ?? 0) ?: 1) + 1;
        }

        if (in_array($beforeDay, $searched)) {
            preg_match($beforeDay, $value, $matches);
            $changes['add_days'] = -((int)(($matches[1] ?? 0) ?: 1));
        }

        if (in_array($plusDay, $searched)) {
            preg_match($afterDay, $value, $matches);
            $changes['add_days'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($minusDay, $searched)) {
            preg_match($beforeDay, $value, $matches);
            $changes['add_days'] = -((int)($matches[1] ?? 0) ?: 1);
        }

        // Неделя

        if (in_array($nextWeek, $searched)) {
            preg_match($nextWeek, $value, $matches);
            $changes['add_weeks'] = (int)($matches[1]) ?: 1;
        }

        if (in_array($prevWeek, $searched)) {
            preg_match($prevWeek, $value, $matches);
            $changes['add_weeks'] = -((int)($matches[1] ?? 0) ?: 1);
        }

        !in_array($prevWeek2, $searched) ?: $changes['add_weeks'] = -2;

        if (in_array($afterWeek, $searched)) {
            preg_match($afterWeek, $value, $matches);
            $changes['add_weeks'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($beforeWeek, $searched)) {
            preg_match($beforeWeek, $value, $matches);
            $changes['add_weeks'] = -((int)($matches[1] ?? 0) ?: 1);
        }

        if (in_array($plusWeek, $searched)) {
            preg_match($plusWeek, $value, $matches);
            $changes['add_weeks'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($minusWeek, $searched)) {
            preg_match($beforeWeek, $value, $matches);
            $changes['add_weeks'] = -((int)($matches[1] ?? 0) ?: 1);
        }

        // Неделя следующая

        !in_array($nextStartWeek, $searched) ?: $changes['day_week'] = 1;
        !in_array($nextMiddleWeek, $searched) ?: $changes['day_week'] = 4;
        !in_array($nextEndWeek, $searched) ?: $changes['day_week'] = 7;

        // Недел прошлые

        !in_array($prevStartWeek, $searched) ?: $changes['day_week'] = 1;
        !in_array($prevMiddleWeek, $searched) ?: $changes['day_week'] = 4;
        !in_array($prevEndWeek, $searched) ?: $changes['day_week'] = 7;

        // Месяц

        if (in_array($nextMonth, $searched)) {
            preg_match($nextMonth, $value, $matches);
            $changes['add_months'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($prevMonth, $searched)) {
            preg_match($prevMonth, $value, $matches);
            $changes['add_months'] = -((int)($matches[1] ?? 0) ?: 1);
        }

        !in_array($prevMonth2, $searched) ?: $changes['add_months'] = -2;

        if (in_array($afterMonth, $searched)) {
            preg_match($afterMonth, $value, $matches);
            $changes['add_months'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($beforeMonth, $searched)) {
            preg_match($beforeMonth, $value, $matches);
            $changes['add_months'] = -((int)($matches[1] ?? 0) ?: 1);
        }

        if (in_array($plusMonth, $searched)) {
            preg_match($plusMonth, $value, $matches);
            $changes['add_months'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($minusMonth, $searched)) {
            preg_match($beforeMonth, $value, $matches);
            $changes['add_months'] = -((int)($matches[1] ?? 0) ?: 1);
        }

        // Месяц текущий

        !in_array($currStartMonth, $searched) ?: $changes['day_month'] = 'first';
        !in_array($currMiddleMonth, $searched) ?: $changes['day_month'] = 'middle';
        !in_array($currEndMonth, $searched) ?: $changes['day_month'] = 'last';

        // Месяц следующий

        !(in_array($nextStartMonth, $searched)) ?: $changes['add_months'] = 1;
        !(in_array($nextMiddleMonth, $searched)) ?: $changes['add_months'] = 1;
        !(in_array($nextEndMonth, $searched)) ?: $changes['add_months'] = 1;

        !in_array($nextStartMonth, $searched) ?: $changes['day_month'] = 'first';
        !in_array($nextMiddleMonth, $searched) ?: $changes['day_month'] = 'middle';
        !in_array($nextEndMonth, $searched) ?: $changes['day_month'] = 'last';

        // Месяц прошлый

        !(in_array($prevStartMonth, $searched)) ?: $changes['add_months'] = -1;
        !(in_array($prevStartMonth, $searched)) ?: $changes['add_months'] = -1;
        !(in_array($prevEndMonth, $searched)) ?: $changes['add_months'] = -1;

        !in_array($prevStartMonth, $searched) ?: $changes['day_month'] = 'first';
        !in_array($prevMiddleMonth, $searched) ?: $changes['day_month'] = 'middle';
        !in_array($prevEndMonth, $searched) ?: $changes['day_month'] = 'last';

        // Год

        if (in_array($nextYear, $searched)) {
            preg_match($nextYear, $value, $matches);
            $changes['add_years'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($prevYear, $searched)) {
            preg_match($prevYear, $value, $matches);
            $changes['add_years'] = -((int)($matches[1] ?? 0) ?: 1);
        }

        !in_array($prevYear2, $searched) ?: $changes['add_years'] = -2;

        if (in_array($afterYear, $searched)) {
            preg_match($afterYear, $value, $matches);
            $changes['add_years'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($beforeYear, $searched)) {
            preg_match($beforeYear, $value, $matches);
            $changes['add_years'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($plusYear, $searched)) {
            preg_match($plusYear, $value, $matches);
            $changes['add_years'] = (int)($matches[1] ?? 0) ?: 1;
        }

        if (in_array($minusYear, $searched)) {
            preg_match($beforeYear, $value, $matches);
            $changes['add_years'] = (int)($matches[1] ?? 0) ?: 1;
        }

        // Год текущий

        !in_array($currStartYear, $searched) ?: $changes['day_year'] = 'first';
        !in_array($currMiddleYear, $searched) ?: $changes['day_year'] = 'middle';
        !in_array($currEndYear, $searched) ?: $changes['day_year'] = 'last';

        // Год следующий

        !(in_array($nextStartYear, $searched)) ?: $changes['add_years'] = 1;
        !(in_array($nextMiddleYear, $searched)) ?: $changes['add_years'] = 1;
        !(in_array($nextEndYear, $searched)) ?: $changes['add_years'] = 1;

        !in_array($nextStartYear, $searched) ?: $changes['day_year'] = 'first';
        !in_array($nextMiddleYear, $searched) ?: $changes['day_year'] = 'middle';
        !in_array($nextEndYear, $searched) ?: $changes['day_year'] = 'last';

        // Год прошлый

        !(in_array($prevStartYear, $searched)) ?: $changes['add_years'] = -1;
        !(in_array($prevStartYear, $searched)) ?: $changes['add_years'] = -1;
        !(in_array($prevEndYear, $searched)) ?: $changes['add_years'] = -1;

        !in_array($prevStartYear, $searched) ?: $changes['day_year'] = 'first';
        !in_array($prevMiddleYear, $searched) ?: $changes['day_year'] = 'middle';
        !in_array($prevEndYear, $searched) ?: $changes['day_year'] = 'last';

        // Число

        if (
            in_array($numberDay, $searched) && (
                in_array($january, $searched)
                || in_array($february, $searched)
                || in_array($april, $searched)
                || in_array($may, $searched)
                || in_array($june, $searched)
                || in_array($july, $searched)
                || in_array($august, $searched)
                || in_array($september, $searched)
                || in_array($october, $searched)
                || in_array($november, $searched)
                || in_array($december, $searched)
            )
        ) {
            preg_match($numberDay, $value, $matches);
            $changes['number_day'] = (int)trim($matches[0] ?? 0, ', ');
        }

        if (in_array($numberDay2, $searched)) {
            preg_match($numberDay2, $value, $matches);
            $changes['number_day'] = (int)($matches[0] ?? 0);
        }

        if (in_array($numberDate, $searched)) {
            preg_match($numberDate, $value, $matches);
            $changes['number_date'] = $matches[0] ?? '';
        }

        // Вычисление даты

        empty($changes['add_days']) ?: $date->addDays($changes['add_days']);
        empty($changes['add_weeks']) ?: $date->addWeeks($changes['add_weeks']);
        empty($changes['add_months']) ?: $date->addMonths($changes['add_months']);
        empty($changes['add_years']) ?: $date->addYears($changes['add_years']);
        empty($changes['day_week']) ?: $date->weekday($changes['day_week']);

        empty($changes['day_month']) ?: match ($changes['day_month']) {
            'first' => $date->day(1),
            'middle' => $date->day((int)($date->daysInMonth() / 2)),
            'last' => $date->daysInMonth(),
        };

        empty($changes['day_year']) ?: match ($changes['day_year']) {
            'first' => $date->month(1)->day(1),
            'middle' => $date->month(6)->day((int)($date->daysInMonth() / 2)),
            'last' => $date->month(12)->day($date->daysInMonth()),
        };

        empty($changes['number_year']) ?: $date->year($changes['number_year']);
        empty($changes['number_month']) ?: $date->month($changes['number_month']);
        empty($changes['number_day']) ?: $date->day($changes['number_day']);
        empty($changes['number_date']) ?: $date = $date->parse($changes['number_date']);

        return ($searched && array_filter($changes)) ? $date : null;
    }


    /**
     * Возвращает дату прописью на русском языке с учетом склонения по падежу
     * @see ../../tests/HelperDateTrait/HelperDateToStringTest.php
     *
     * @param Carbon|string|int|null $value
     * @param Declension $declension
     * @return string
     */
    public static function dateToString(
        Carbon|string|int|null $value,
        Declension $declension = Declension::Nominative,
    ): string {
        $value = match (true) {
            $value instanceof Carbon => $value->format('d.m.Y'),
            is_string($value) => static::dateFromString($value)?->format('d.m.Y'),
            is_integer($value) => Carbon::parse($value)?->format('d.m.Y'),
        };
        $value = static::stringReplace($value, '/[^0-9.]/u', '');
        $result = '';

        if (!$value) {
            return $result;
        }

        $dd = (int)static::stringSplit($value, '.', 0);
        !(static::intervalBetween($dd, [1, 31])) ?:
            $result = static::numberToString($dd, $declension, Gender::Neuter, Enumeration::Ordinal);

        $mm = (int)static::stringSplit($value, '.', 1);
        if (static::intervalBetween($mm, [1, 12])) {
            $result .= ($result !== '' ? ' ' : '')
                . Consts::DATE_MONTH_NAMES[$mm - 1]
                . ($result === ''
                    ? ($declension->value === 0
                        ? (
                            $mm === 3
                            ? Consts::DATE_MONTH_CASE_3[6]
                            : ($mm === 8 ? Consts::DATE_MONTH_CASE_8[6] : Consts::DATE_MONTH_CASE[6]))
                        : (
                            $mm === 3
                            ? Consts::DATE_MONTH_CASE_3[$declension->value]
                            : (
                                $mm === 8
                                ? Consts::DATE_MONTH_CASE_8[$declension->value]
                                : Consts::DATE_MONTH_CASE[$declension->value]
                            )
                        )
                    )

                    : (
                        $mm === 3
                        ? Consts::DATE_MONTH_CASE_3[$declension->value]
                        : (
                            $mm === 8
                            ? Consts::DATE_MONTH_CASE_8[$declension->value]
                            : Consts::DATE_MONTH_CASE[$declension->value]
                        )
                    )
                );
        }

        $yyyy = (int)static::stringSplit($value, '.', 2);
        !$yyyy ?: $result .= ($result !== '' ? ' ' : '')
            . ($result === ''
                ? static::numberToString($yyyy, $declension, Gender::Male, Enumeration::Ordinal)
                . ' год'
                . ($declension->value === 0 ? '' : Consts::DATE_MONTH_CASE_YEAR[$declension->value])

                : static::numberToString($yyyy, $declension, Gender::Male, Enumeration::Ordinal)
                . ' год'
                . ($declension->value === 0 ? '' : Consts::DATE_MONTH_CASE_YEAR[$declension->value])
            );

        return $result;
    }


    /**
     * Возвращает название дня недели переданного значения
     * @see ../../tests/HelperDateTrait/HelperDateDayNameTest.php
     *
     * @param Carbon|int|string $value
     * @return string
     */
    public static function dateDayName(Carbon|int|string $value): string
    {
        $value = match (true) {
            $value instanceof Carbon => $value,
            is_string($value) => static::dateFromString($value),
            is_integer($value) => Carbon::parse($value),
        };

        if (!$value instanceof Carbon) {
            return '';
        }

        return Consts::DATE_DAY_NAME[$value->dayOfWeek() - 1] ?? '';
    }
}
