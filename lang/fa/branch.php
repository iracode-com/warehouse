<?php

return [
    'name' => 'نام شعبه',
    'branch_type' => 'نوع شعبه',
    'two_digit_code' => 'کد دو رقمی',
    'date_establishment' => 'تاریخ تأسیس',
    'phone' => 'تلفن',
    'fax' => 'فکس',
    'address' => 'آدرس',
    'description' => 'توضیحات',
    'postal_code' => 'کد پستی',
    'coding' => 'کدینگ',
    'vhf_address' => 'کد آدرس VHF',
    'hf_address' => 'کد آدرس HF',
    'vhf_channel' => 'کانال VHF',
    'lat' => 'عرض جغرافیایی (اعشاری)',
    'lon' => 'طول جغرافیایی (اعشاری)',
    'lat_deg' => 'عرض جغرافیایی (درجه)',
    'lat_min' => 'عرض جغرافیایی (دقیقه)',
    'lat_sec' => 'عرض جغرافیایی (ثانیه)',
    'lon_deg' => 'طول جغرافیایی (درجه)',
    'lon_min' => 'طول جغرافیایی (دقیقه)',
    'lon_sec' => 'طول جغرافیایی (ثانیه)',
    'height' => 'ارتفاع',
    'closed_thursday' => 'تعطیلی پنجشنبه؟',
    'closure_date' => 'تاریخ تعطیلی',
    'closure_document' => 'سند تعطیلی',
    'date_closed_thursday' => 'تاریخ شروع تعطیلی پنجشنبه',
    'date_closed_thursday_end' => 'تاریخ پایان تعطیلی پنجشنبه',
    'img_header' => 'تصویر هدر شعبه',
    'img_building' => 'تصویر ساختمان شعبه',

    // Branch types
    'branch_types' => [
        'county' => 'شهرستان',
        'headquarters' => 'ستاد',
        'branch' => 'شعبه',
        'independent_office' => 'دفتر مستقل',
        'dependent_office' => 'دفتر وابسته',
        'urban_area' => 'منطقه شهری',
    ],

    // Sections
    'sections' => [
        'basic_info' => 'اطلاعات پایه شعبه',
        'basic_info_desc' => 'اطلاعات اصلی و تماس های شعبه',
        'contact_info' => 'اطلاعات تماس',
        'contact_info_desc' => 'شماره تلفن ها و آدرس شعبه',
        'communication_info' => 'اطلاعات ارتباطی',
        'communication_info_desc' => 'اطلاعات VHF و HF',
        'geographic_info' => 'اطلاعات جغرافیایی',
        'geographic_info_desc' => 'مختصات جغرافیایی شعبه',
        'closure_info' => 'اطلاعات تعطیلی',
        'closure_info_desc' => 'اطلاعات تعطیلی پنجشنبه',
        'images' => 'تصاویر',
        'images_desc' => 'تصاویر شعبه',
    ],

    // Table
    'table' => [
        'name' => 'نام شعبه',
        'branch_type' => 'نوع شعبه',
        'phone' => 'تلفن',
        'address' => 'آدرس',
        'closed_thursday' => 'تعطیلی پنجشنبه',
        'created_at' => 'تاریخ ایجاد',
    ],

    // Navigation
    'navigation' => [
        'singular' => 'شعبه',
        'plural' => 'شعبه ها',
    ],

    // Actions
    'actions' => [
        'create' => 'ایجاد شعبه جدید',
        'view' => 'مشاهده',
        'edit' => 'ویرایش',
        'delete' => 'حذف',
        'delete_selected' => 'حذف انتخاب شده ها',
    ],

    // Filters
    'filters' => [
        'branch_type' => 'نوع شعبه',
        'closed_thursday' => 'تعطیلی پنجشنبه',
        'all' => 'همه',
        'closed' => 'تعطیل',
        'open' => 'باز',
    ],
];