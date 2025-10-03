<?php

namespace App\Http\Services;

class StringService
{
    public static function pascalCaseToSpaces($string)
    {
        return preg_replace('/(?<!^)([A-Z])/', ' \1', $string);
    }
    public static function convertPersianNumbersToEnglish($str)
    {
        $persianDigits = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $englishDigits = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');

        return str_replace($persianDigits, $englishDigits, $str);
    }
    public static function removeArabicLettersFromPersian($str)
    {
        $arabic_letters = array('ي', 'ك');
        $persian_letters = array('ی', 'ک');

        return str_replace($arabic_letters, $persian_letters, $str);
    }
}
