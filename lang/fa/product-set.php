<?php

return [
    // Model name
    'model_label' => 'ست/سبد کالا',
    'model_label_plural' => 'ست‌ها و سبدها',

    // Navigation
    'navigation_label' => 'ست‌ها و سبدها',
    'navigation_group' => 'مدیریت کالا',

    // Form sections
    'basic_info' => 'اطلاعات اولیه',
    'basic_info_desc' => 'اطلاعات پایه ست/سبد کالا',
    'items_info' => 'اقلام ست',
    'items_info_desc' => 'کالاهای تشکیل دهنده ست/سبد',
    'build_info' => 'ساخت ست از انبار',
    'build_info_desc' => 'ساخت ست از موجودی انبار',

    // Fields
    'name' => 'نام ست/سبد',
    'code' => 'کد ست/سبد',
    'description' => 'توضیحات',
    'set_type' => 'نوع',
    'is_active' => 'وضعیت فعال',
    'total_quantity' => 'تعداد کل',
    'unit' => 'واحد',

    // Set item fields
    'item_product' => 'کالا',
    'item_quantity' => 'تعداد',
    'item_coefficient' => 'ضریب',
    'item_unit' => 'واحد',
    'item_notes' => 'یادداشت',
    'effective_quantity' => 'تعداد مؤثر',
    'expiry_date' => 'تاریخ انقضاء',
    'production_date' => 'تاریخ تولید',
    'batch_number' => 'شماره بچ',

    // Set types
    'set_types' => [
        'set' => 'ست',
        'basket' => 'سبد',
    ],

    // Actions
    'add_item' => 'افزودن کالا',
    'remove_item' => 'حذف کالا',
    'build_set' => 'ساخت ست',
    'check_availability' => 'بررسی موجودی',
    'view_items' => 'مشاهده اقلام',

    // Build set fields
    'warehouse_id' => 'انبار',
    'quantity_to_build' => 'تعداد ست برای ساخت',
    'available_quantity' => 'موجودی قابل دسترس',
    'required_quantity' => 'موجودی مورد نیاز',

    // Table columns
    'table' => [
        'name' => 'نام',
        'code' => 'کد',
        'type' => 'نوع',
        'items_count' => 'تعداد اقلام',
        'total_value' => 'ارزش کل',
        'is_active' => 'وضعیت',
    ],

    // Messages
    'created' => 'ست/سبد با موفقیت ایجاد شد',
    'updated' => 'ست/سبد با موفقیت بروزرسانی شد',
    'deleted' => 'ست/سبد با موفقیت حذف شد',
    'built_successfully' => 'ست با موفقیت ساخته شد',
    'insufficient_inventory' => 'موجودی انبار کافی نیست',
    'no_items' => 'این ست هیچ کالایی ندارد',

    // Validation
    'validation' => [
        'min_one_item' => 'حداقل یک کالا باید به ست اضافه شود',
        'coefficient_min' => 'ضریب باید بیشتر از صفر باشد',
        'quantity_min' => 'تعداد باید بیشتر از صفر باشد',
    ],

    // Helpers
    'coefficient_helper' => 'ضریب تبدیل واحد (مثال: 1000 گرم = 1 کیلوگرم، ضریب = 1000)',
    'effective_quantity_helper' => 'تعداد مؤثر = تعداد × ضریب',
];
