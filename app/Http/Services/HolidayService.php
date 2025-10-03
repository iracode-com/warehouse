<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;

class HolidayService
{
    public function getHolidays($year, $month, $day)
    {
        $response = Http::get("https://holidayapi.ir/jalali/{$year}/{$month}/{$day}");

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
