<?php

return [
    // Status options
    'status' => [
        'active' => 'فعال',
        'inactive' => 'غیرفعال',
        'archived' => 'آرشیو شده',
    ],

    // Yes/No options
    'yes_no' => [
        'yes' => 'دارد',
        'no' => 'ندارد',
    ],

    // System status options
    'system_status' => [
        'healthy' => 'سالم',
        'defective' => 'معیوب',
        'installing' => 'در حال نصب',
    ],

    // Access type options
    'access_type' => [
        'pedestrian' => 'پیاده',
        'forklift' => 'لیفتراک',
        'crane' => 'جرثقیل',
        'mixed' => 'مختلط',
    ],

    // Zone type options
    'zone_type' => [
        'cold_storage' => 'ذخیره‌سازی سرد',
        'hot_storage' => 'ذخیره‌سازی گرم',
        'general' => 'عمومی',
        'hazardous_materials' => 'مواد خطرناک',
        'auto_parts' => 'لوازم یدکی خودرو',
        'emergency_supplies' => 'تجهیزات امدادی',
        'temporary' => 'موقت',
    ],

    // Occupancy status options
    'occupancy_status' => [
        'empty' => 'خالی',
        'partial' => 'نیمه‌پر',
        'full' => 'پر',
    ],

    // Product type options
    'product_type' => [
        'general' => 'عمومی',
        'hazardous' => 'مواد خطرناک',
        'auto_parts' => 'قطعات یدکی',
        'emergency_supplies' => 'تجهیزات امدادی',
        'fragile' => 'شکننده',
        'heavy_duty' => 'سنگین',
        'temperature_sensitive' => 'حساس به دما',
    ],

    // Standard status options
    'standard_status' => [
        'standard' => 'استاندارد',
        'deficit' => 'کسری',
        'surplus' => 'مازاد',
    ],

    // Door system options
    'door_system' => [
        'electric' => 'برقی (ریموت)',
        'manual' => 'دستی',
        'both' => 'هر دو',
    ],

    // Hierarchical level options
    'hierarchical_level' => [
        1 => 'دسته اصلی',
        2 => 'زیر دسته',
        3 => 'زیر زیر دسته',
    ],

    // Message sending options
    'message_sending' => [
        'mobile' => 'شماره همراه',
        'telegram' => 'تلگرام',
        'bale' => 'بله',
        'whatsapp' => 'واتساپ',
    ],

    // Warehouse management levels
    'warehouse_management' => [
        'novice' => 'نوین',
        'basic' => 'مقدماتی',
        'advanced' => 'پیشرفته',
    ],

    // Software levels
    'software_level' => [
        'basic' => 'مقدماتی',
        'intermediate' => 'متوسط',
        'advanced' => 'پیشرفته',
    ],
];
