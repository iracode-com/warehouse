<?php

return [
    'general_settings' => [
        'title'           => 'تنظیمات عمومی',
        'heading'         => 'تنظیمات عمومی',
        'subheading'      => 'مدیریت تنظیمات عمومی سایت از اینجا',
        'navigationLabel' => 'عمومی',
        'sections'        => [
            'site'  => [
                'title'       => 'سایت',
                'description' => 'مدیریت تنظیمات پایه',
            ],
            'theme' => [
                'title'       => 'پوسته',
                'description' => 'تغییر قالب پیش‌فرض',
            ],
        ],
        'fields'          => [
            'brand_name'             => 'نام برند',
            'search_engine_indexing' => 'ایندکس پنل ادمین',
            'site_active'            => 'وضعیت سایت',
            'brand_logo_height'      => 'ارتفاع لوگوی برند',
            'brand_logo'             => 'لوگوی برند',
            'site_favicon'           => 'فاوآیکون سایت',
            'primary'                => 'اصلی',
            'secondary'              => 'ثانویه',
            'gray'                   => 'خاکستری',
            'success'                => 'موفقیت',
            'danger'                 => 'خطر',
            'info'                   => 'اطلاعات',
            'warning'                => 'هشدار',
        ],
    ],
    'mail_settings'    => [

        'title'           => 'تنظیمات ایمیل',
        'heading'         => 'تنظیمات ایمیل',
        'subheading'      => 'مدیریت پیکربندی ایمیل',
        'navigationLabel' => 'ایمیل',
        'sections'        => [
            'config'  => [
                'title'       => 'پیکربندی',
                'description' => 'توضیحات',
            ],
            'sender'  => [
                'title'       => 'از (فرستنده)',
                'description' => 'توضیحات',
            ],
            'mail_to' => [
                'title'       => 'ارسال به',
                'description' => 'توضیحات',
            ],
        ],
        'fields'          => [
            'placeholder' => [
                'receiver_email' => 'ایمیل گیرنده ',
            ],
            'driver'      => 'درایور',
            'host'        => 'میزبان',
            'port'        => 'پورت',
            'encryption'  => 'رمزنگاری',
            'timeout'     => 'مهلت زمانی',
            'username'    => 'نام کاربری',
            'password'    => 'رمز عبور',
            'email'       => 'ایمیل',
            'name'        => 'نام',
            'mail_to'     => 'ارسال به',
        ],
        'actions'         => [
            'send_test_mail' => 'ارسال ایمیل آزمایشی',
        ],
    ],

    // Organization Resource Translations
    'organization' => [
        'title' => 'مشخصات سازمانی',
        'heading' => 'مشخصات سازمانی',
        'subheading' => 'مدیریت اطلاعات و ساختار سازمانی',
        'navigationLabel' => 'مشخصات سازمانی',
        'navigationGroup' => 'اطلاعات سازمانی',
        'sections' => [
            'basic_info' => [
                'title' => 'اطلاعات پایه',
                'description' => 'اطلاعات اصلی سازمان',
            ],
            'contact_info' => [
                'title' => 'اطلاعات تماس',
                'description' => 'اطلاعات ارتباطی سازمان',
            ],
            'structure' => [
                'title' => 'ساختار سازمانی',
                'description' => 'مدیریت ساختار و سلسله مراتب سازمانی',
            ],
        ],
        'fields' => [
            'name' => 'نام سازمان',
            'slug' => 'نامک',
            'logo' => 'لوگو',
            'icon' => 'آیکون',
            'tel' => 'تلفن',
            'fax' => 'فکس',
            'industry' => 'صنعت',
            'personnel_count' => 'تعداد پرسنل',
            'address' => 'آدرس',
            'national_id' => 'شناسه ملی',
            'economy_code' => 'کد اقتصادی',
        ],
        'actions' => [
            'save' => 'ذخیره',
            'create_structure' => 'ایجاد ساختار',
            'add_structure' => 'افزودن ساختار',
            'edit_structure' => 'ویرایش ساختار',
            'delete_structure' => 'حذف ساختار',
            'create_new_structure' => 'ایجاد ساختار جدید',
            'update_structure' => 'بروزرسانی ساختار',
        ],
        'messages' => [
            'organization_updated' => 'سازمان با موفقیت بروزرسانی شد.',
            'structure_created' => 'ساختار با موفقیت ایجاد شد.',
            'structure_updated' => 'ساختار با موفقیت بروزرسانی شد.',
            'structure_deleted' => 'ساختار با موفقیت حذف شد.',
            'cannot_delete_with_children' => 'نمی‌توان ساختاری که دارای زیرمجموعه است را حذف کرد.',
            'structure_moved' => 'ساختار با موفقیت منتقل شد.',
            'structure_moved_to_root' => 'ساختار با موفقیت به سطح اصلی منتقل شد.',
        ],
        'structure' => [
            'title' => 'عنوان',
            'parent' => 'ساختار والد',
            'parent_structure' => 'ساختار والد',
            'region' => 'منطقه',
            'region_optional' => 'منطقه (اختیاری)',
            'root_top_level' => 'ریشه (سطح بالا)',
            'select_region' => 'انتخاب منطقه',
            'enter_structure_title' => 'عنوان ساختار را وارد کنید',
            'drag_to_reorder' => 'برای تغییر ترتیب بکشید',
            'toggle_children' => 'تغییر نمایش زیرمجموعه‌ها',
            'add_child_structure' => 'افزودن ساختار فرزند',
            'edit_structure' => 'ویرایش ساختار',
            'delete_structure' => 'حذف ساختار',
            'no_structures_found' => 'هیچ ساختار سازمانی یافت نشد.',
            'click_add_structure' => 'برای ایجاد اولین ساختار کلیک کنید.',
            'organizational_structure' => 'ساختار سازمانی',
            'create_structure' => 'ایجاد ساختار',
            'drag_and_drop_instructions' => 'برای تغییر ترتیب ساختارها، آنها را بکشید و رها کنید',
        ],
    ],

    // Position Resource Translations
    'position' => [
        'title' => 'سمت‌ها',
        'heading' => 'مدیریت سمت‌ها',
        'subheading' => 'مدیریت سمت‌ها و پست‌های سازمانی',
        'navigationLabel' => 'سمت‌ها',
        'navigationGroup' => 'اطلاعات سازمانی',
        'sections' => [
            'basic_info' => [
                'title' => 'اطلاعات پایه',
                'description' => 'اطلاعات اصلی سمت',
            ],
        ],
        'fields' => [
            'name' => 'نام سمت',
            'description' => 'توضیحات',
            'status' => 'فعال / غیرفعال',
            'active' => 'فعال',
            'inactive' => 'غیرفعال',
        ],
        'actions' => [
            'create' => 'ایجاد سمت',
            'edit' => 'ویرایش سمت',
            'delete' => 'حذف سمت',
            'view' => 'مشاهده سمت',
            'save' => 'ذخیره',
            'cancel' => 'انصراف',
        ],
        'messages' => [
            'position_created' => 'سمت با موفقیت ایجاد شد.',
            'position_updated' => 'سمت با موفقیت بروزرسانی شد.',
            'position_deleted' => 'سمت با موفقیت حذف شد.',
            'confirm_delete' => 'آیا از حذف این سمت اطمینان دارید؟',
        ],
        'pages' => [
            'list' => [
                'title' => 'لیست سمت‌ها',
                'description' => 'مشاهده و مدیریت تمام سمت‌های سازمانی',
            ],
            'create' => [
                'title' => 'ایجاد سمت جدید',
                'description' => 'افزودن سمت جدید به سازمان',
            ],
            'edit' => [
                'title' => 'ویرایش سمت',
                'description' => 'ویرایش اطلاعات سمت',
            ],
        ],
    ],

    // Common translations
    'common' => [
        'actions' => [
            'create' => 'ایجاد',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
            'view' => 'مشاهده',
            'save' => 'ذخیره',
            'cancel' => 'انصراف',
            'back' => 'بازگشت',
            'next' => 'بعدی',
            'previous' => 'قبلی',
            'search' => 'جستجو',
            'filter' => 'فیلتر',
            'export' => 'خروجی',
            'import' => 'ورودی',
        ],
        'status' => [
            'active' => 'فعال',
            'inactive' => 'غیرفعال',
            'enabled' => 'فعال',
            'disabled' => 'غیرفعال',
        ],
        'messages' => [
            'created_successfully' => 'با موفقیت ایجاد شد.',
            'updated_successfully' => 'با موفقیت بروزرسانی شد.',
            'deleted_successfully' => 'با موفقیت حذف شد.',
            'confirm_delete' => 'آیا از حذف این مورد اطمینان دارید؟',
            'no_records_found' => 'هیچ رکوردی یافت نشد.',
        ],
    ],
];
