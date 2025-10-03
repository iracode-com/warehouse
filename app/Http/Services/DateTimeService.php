<?php

namespace App\Http\Services;

use Morilog\Jalali\CalendarUtils;

class DateTimeService
{
    public static function convertGeophysicDatetimeToGregorian($datetime)
    {
        $jalali_datetime = StringService::convertPersianNumbersToEnglish($datetime);
        $jalali_date = explode(' ', $jalali_datetime)[0];
        $jalali_time = explode(' ', $jalali_datetime)[1];
        $year = explode('/', $jalali_date)[0];
        $month = explode('/', $jalali_date)[1];
        $day = explode('/', $jalali_date)[2];
        if (CalendarUtils::checkDate($year, $month, $day, true)) {
            return implode('/', CalendarUtils::toGregorian($year, $month, $day)) . ' ' . $jalali_time;
        }
    }
}
