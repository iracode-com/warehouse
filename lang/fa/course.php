<?php

return [
    // Navigation
    'navigation' => [
        'singular' => 'دوره',
        'plural' => 'دوره‌ها',
    ],

    // Form sections
    'sections' => [
        'basic_info' => 'اطلاعات پایه',
        'basic_info_desc' => 'اطلاعات اصلی دوره',
    ],

    // Form fields
    'fields' => [
        'name' => 'نام دوره',
        'description' => 'توضیحات',
        'duration_hours' => 'مدت زمان (ساعت)',
        'instructor' => 'مدرس',
        'institution' => 'موسسه',
        'completion_date' => 'تاریخ تکمیل',
        'certificate_number' => 'شماره گواهینامه',
        'status' => 'وضعیت فعال',
    ],

    // Table columns
    'table' => [
        'name' => 'نام',
        'description' => 'توضیحات',
        'duration_hours' => 'مدت زمان',
        'instructor' => 'مدرس',
        'institution' => 'موسسه',
        'completion_date' => 'تاریخ تکمیل',
        'status' => 'وضعیت',
        'created_at' => 'تاریخ ایجاد',
    ],

    // Filters
    'filters' => [
        'status' => 'وضعیت فعال',
        'active' => 'فعال',
        'inactive' => 'غیرفعال',
    ],
];
