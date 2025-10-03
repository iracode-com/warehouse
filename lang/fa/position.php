<?php

return [
    // Navigation
    'navigation' => [
        'group' => 'اطلاعات پایه',
        'singular' => 'سمت',
        'plural' => 'سمت‌ها',
    ],

    // Form fields
    'fields' => [
        'name' => 'نام سمت',
        'description' => 'توضیحات',
        'status' => 'وضعیت',
    ],

    // Status options
    'status_options' => [
        '1' => 'فعال',
        '0' => 'غیرفعال',
    ],

    // Table columns
    'table' => [
        'name' => 'نام',
        'description' => 'توضیحات',
        'status' => 'وضعیت',
        'created_at' => 'تاریخ ایجاد',
    ],

    // Filters
    'filters' => [
        'status' => 'وضعیت',
        'all' => 'همه',
        'active' => 'فعال',
        'inactive' => 'غیرفعال',
    ],

    // Actions
    'actions' => [
        'create' => 'ایجاد سمت',
        'view' => 'مشاهده',
        'edit' => 'ویرایش',
        'delete' => 'حذف',
        'delete_selected' => 'حذف انتخاب شده‌ها',
    ],
];
