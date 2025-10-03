<?php

return [
    // Navigation
    'navigation' => [
        'group' => 'مدیریت کالا',
        'singular' => 'دسته‌بندی',
        'plural' => 'دسته‌بندی‌ها',
    ],

    // Form sections
    'sections' => [
        'basic_info' => 'اطلاعات پایه',
        'basic_info_desc' => 'اطلاعات اصلی دسته‌بندی',
        'additional_info' => 'اطلاعات تکمیلی',
        'additional_info_desc' => 'توضیحات و تنظیمات اضافی',
    ],

    // Form fields
    'fields' => [
        'name' => 'نام دسته‌بندی',
        'description' => 'توضیحات',
        'parent_id' => 'دسته‌بندی والد',
        'is_active' => 'فعال',
        'sort_order' => 'ترتیب نمایش',
    ],

    // Table columns
    'table' => [
        'name' => 'نام',
        'description' => 'توضیحات',
        'parent' => 'والد',
        'is_active' => 'فعال',
        'sort_order' => 'ترتیب',
        'created_at' => 'تاریخ ایجاد',
    ],

    // Filters
    'filters' => [
        'parent' => 'دسته‌بندی والد',
        'is_active' => 'وضعیت فعال',
        'all' => 'همه',
        'active' => 'فعال',
        'inactive' => 'غیرفعال',
    ],

    // Actions
    'actions' => [
        'create' => 'ایجاد دسته‌بندی',
        'view' => 'مشاهده',
        'edit' => 'ویرایش',
        'delete' => 'حذف',
        'delete_selected' => 'حذف انتخاب شده‌ها',
    ],
];
