<?php

return [
    // Navigation
    'navigation' => [
        'group' => 'مدیریت کالا',
        'singular' => 'قلم انبار',
        'plural' => 'اقلام انبار',
    ],

    // Form sections
    'sections' => [
        'product_profile_selection' => 'انتخاب شناسنامه کالا',
        'product_profile_selection_desc' => 'ابتدا شناسنامه کالا را انتخاب کنید',
        'item_info' => 'اطلاعات قلم انبار',
        'item_info_desc' => 'اطلاعات مربوط به این قلم خاص در انبار',
        'inventory_pricing' => 'موجودی و قیمت',
        'inventory_pricing_desc' => 'اطلاعات موجودی و قیمت‌گذاری',
        'dates' => 'تاریخ‌ها',
        'dates_desc' => 'تاریخ‌های مهم مربوط به این قلم',
        'location' => 'مکان‌یابی',
        'location_desc' => 'موقعیت نگهداری این قلم در انبار',
        'settings' => 'تنظیمات',
        'settings_desc' => 'تنظیمات کلی این قلم',
    ],

    // Form fields
    'fields' => [
        'product_profile_id' => 'شناسنامه کالا',
        'product_profile_id_helper' => 'شناسنامه کالا که این قلم بر اساس آن تعریف می‌شود',
        'serial_number' => 'شماره سریال',
        'serial_number_helper' => 'شماره سریال این قلم خاص',
        'status' => 'وضعیت قلم',
        'status_helper' => 'وضعیت این قلم در انبار',
        'current_stock' => 'موجودی فعلی',
        'current_stock_helper' => 'تعداد موجودی فعلی',
        'min_stock' => 'حداقل موجودی',
        'min_stock_helper' => 'حداقل موجودی مورد نیاز',
        'max_stock' => 'حداکثر موجودی',
        'max_stock_helper' => 'حداکثر موجودی مجاز',
        'unit_cost' => 'هزینه واحد',
        'unit_cost_helper' => 'هزینه خرید هر واحد',
        'selling_price' => 'قیمت فروش',
        'selling_price_helper' => 'قیمت فروش هر واحد',
        'manufacture_date' => 'تاریخ تولید',
        'manufacture_date_helper' => 'تاریخ تولید این قلم',
        'expiry_date' => 'تاریخ انقضا',
        'expiry_date_helper' => 'تاریخ انقضای این قلم',
        'purchase_date' => 'تاریخ خرید',
        'purchase_date_helper' => 'تاریخ خرید این قلم',
        'warehouse_id' => 'انبار',
        'warehouse_id_helper' => 'انبار نگهداری این قلم',
        'zone_id' => 'منطقه',
        'zone_id_helper' => 'منطقه نگهداری این قلم',
        'rack_id' => 'قفسه',
        'rack_id_helper' => 'قفسه نگهداری این قلم',
        'shelf_level_id' => 'طبقه',
        'shelf_level_id_helper' => 'طبقه نگهداری این قلم',
        'pallet_id' => 'پالت',
        'pallet_id_helper' => 'پالت نگهداری این قلم',
        'is_active' => 'فعال',
        'is_active_helper' => 'وضعیت فعال/غیرفعال این قلم',
    ],

    // Status options
    'status_options' => [
        'active' => 'فعال',
        'inactive' => 'غیرفعال',
        'discontinued' => 'متوقف شده',
        'recalled' => 'فراخوانی شده',
    ],

    // Table columns
    'table' => [
        'sku' => 'کد کالا',
        'name' => 'نام کالا',
        'serial_number' => 'شماره سریال',
        'current_stock' => 'موجودی فعلی',
        'unit_cost' => 'هزینه واحد',
        'selling_price' => 'قیمت فروش',
        'warehouse' => 'انبار',
        'status' => 'وضعیت',
        'is_active' => 'فعال',
        'created_at' => 'تاریخ ایجاد',
    ],

    // Filters
    'filters' => [
        'product_profile' => 'شناسنامه کالا',
        'warehouse' => 'انبار',
        'status' => 'وضعیت',
        'is_active' => 'وضعیت فعالیت',
        'all' => 'همه',
        'active' => 'فعال',
        'inactive' => 'غیرفعال',
    ],

    // Actions
    'actions' => [
        'create' => 'ایجاد قلم انبار',
        'view' => 'مشاهده',
        'edit' => 'ویرایش',
        'delete' => 'حذف',
        'delete_selected' => 'حذف انتخاب شده‌ها',
    ],
];
