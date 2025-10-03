<?php

return [
    // Navigation Groups
    'navigation_groups' => [
        'warehouse_management' => 'مدیریت انبار',
        'location_management' => 'مدیریت مکان',
        'user_management' => 'مدیریت کاربران',
    ],

    'title' => 'عنوان انبار',
    'usage_type' => 'نوع کاربری',
    'ownership_type' => 'نوع مالکیت',
    'province_id' => 'استان',
    'city_id' => 'شهر',
    'town_id' => 'شهرستان',
    'village_id' => 'روستا',
    'manager_name' => 'نام مسئول انبار',
    'manager_phone' => 'شماره تماس مسئول انبار',
    'keeper_name' => 'نام انباردار/کاربر',
    'keeper_mobile' => 'شماره موبایل انباردار',
    'telephone' => 'تلفن ثابت',
    'warehouse_info' => 'توضیحات انبار',
    'address' => 'آدرس کامل',
    'postal_address' => 'آدرس دقیق پستی',
    'province' => 'استان',
    'branch' => 'شعبه',
    'base' => 'پایگاه',
    'establishment_year' => 'سال تاسیس انبار',
    'construction_year' => 'سال ساخت',
    'branch_establishment_year' => 'سال تاسیس شعبه',
    'population_census' => 'سرشماری جمعیت فعلی',
    'population_census_1395' => 'سرشماری جمعیت 1395',
    'area' => 'متراژ کل انبار',
    'under_construction_area' => 'متراژ در دست ساخت',
    'warehouse_area' => 'متراژ سوله (انبار، انباری)',
    'warehouse_count' => 'تعداد سوله',
    'small_inventory_count' => 'تعداد موجودی کوچک',
    'large_inventory_count' => 'تعداد موجودی بزرگ',
    'diesel_forklift_status' => 'لیفتراک دیزلی',
    'gasoline_forklift_status' => 'لیفتراک بنزینی',
    'gas_forklift_status' => 'لیفتراک گازسوز',
    'forklift_standard' => 'وضعیت استاندارد',
    'ramp_length' => 'طول رمپ',
    'ramp_height' => 'ارتفاع رمپ',
    'warehouse_insurance' => 'بیمه انبارها',
    'building_insurance' => 'بیمه ابنیه',
    'fire_suppression_system' => 'سیستم اطفا حریق',
    'fire_alarm_system' => 'سیستم اعلان حریق',
    'cctv_system' => 'دوربین مدار بسته',
    'lighting_system' => 'سیستم روشنایی',
    'ram_rack' => 'رام و راک',
    'ram_rack_count' => 'تعداد رام راک',
    'longitude' => 'طول جغرافیایی',
    'latitude' => 'عرض جغرافیایی',
    'longitude_e' => 'طول جغرافیایی (E)',
    'latitude_n' => 'عرض جغرافیایی (N)',
    'altitude' => 'ارتفاع از سطح دریا',
    'gps_x' => 'GPS X',
    'gps_y' => 'GPS Y',
    'provincial_risk_percentage' => 'درصد خطرپذیری استانی',
    'approved_grade' => 'درجه مصوب',
    'nearest_branch_1' => 'شعبه اول',
    'distance_to_branch_1' => 'فاصله تا شعبه اول',
    'nearest_branch_2' => 'شعبه دوم',
    'distance_to_branch_2' => 'فاصله تا شعبه دوم',
    'warehouse_location' => 'موقعیت جغرافیایی انبار',
    'geographic_info_desc' => 'جزئیات موقعیت جغرافیایی انبار',

    // Usage types
    'usage_types' => [
        'emergency' => 'امدادی',
        'scrap_used' => 'اسقاط و مستعمل (غیرامدادی)',
        'auto_parts' => 'لوازم و قطعات یدکی خودرو',
        'ready_operations' => 'آماده عملیات',
        'air_rescue_parts' => 'لوازم و قطعات امداد هوایی',
        'rescue_equipment' => 'تجهیزات امداد و نجات',
        'temporary' => 'موقت',
        'multi_purpose' => 'چند منظوره',
    ],

    // Natural hazards
    'natural_hazards' => 'در معرض کدامیک از مخاطرات طبیعی قرار دارد؟',
    'natural_hazards_types' => [
        'landslide' => 'ریزش کوه',
        'flood_path' => 'مسیر سیلاب',
        'near_fault' => 'در نزدیکی گسل',
        'landslip' => 'رانش و لغزش',
        'steep_slope' => 'شیب زیاد زمین',
        'wind_storm' => 'باد و طوفان',
        'cold_climate' => 'سردسیر',
        'hot_climate' => 'گرمسیر',
    ],

    // Location and access fields
    'urban_location' => 'استقرار در محدوده شهری',
    'urban_location_types' => [
        'inside_city' => 'داخل شهر',
        'under_5km' => 'زیر 5 km',
        'between_5_10km' => 'بین 5 الی 10 km',
        'above_10km' => 'بالای 10 km',
    ],

    'main_road_access' => 'استقرار در مسیر جاده های اصلی بین شهری',
    'main_road_access_types' => [
        'has' => 'دارد',
        'has_not' => 'ندارد',
    ],

    'heavy_vehicle_access' => 'امکان تردد کلیه خودروهای سنگین به محوطه منتهی به انبار',
    'heavy_vehicle_access_types' => [
        'has' => 'دارد',
        'has_not' => 'ندارد',
    ],

    'terminal_proximity' => 'در نزدیکی پایانه',
    'terminal_proximity_types' => [
        'sea' => 'دریایی',
        'rail' => 'ریلی',
        'air' => 'هوایی',
        'land' => 'زمینی',
    ],

    'parking_facilities' => 'پارکینگ خودروها و ماشین آلات بجز فضای تردد خودروها',
    'parking_facilities_types' => [
        'covered' => 'پارکینگ (مسقف)',
        'open_space' => 'پارکینگ (فضای باز)',
        'none' => 'ندارد',
    ],

    'utilities' => 'دارا بودن انشعابات',
    'utilities_types' => [
        'water' => 'آب',
        'electricity' => 'برق',
        'phone' => 'تلفن',
        'internet' => 'اینترنت',
        'wireless' => 'بی سیم',
        'office_facilities' => 'امکانات اداری',
    ],

    'neighboring_organizations' => 'همجواری با سایر ارگانها',
    'neighboring_organizations_types' => [
        'police' => 'نیروی انتظامی',
        'traffic_police' => 'پلیس راه',
        'toll' => 'عوارضی',
        'emergency' => 'اورژانس',
        'road_maintenance' => 'راهداری',
    ],

    // Ownership types
    'ownership_types' => [
        'owned' => 'مالکیتی',
        'rented' => 'استیجاری',
        'donated' => 'اهدا',
    ],

    // Structure types
    'structure_types' => [
        'concrete' => 'بتنی',
        'metal' => 'فلزی',
        'prefabricated' => 'پیش‌ساخته',
    ],

    // Status options
    'status_options' => [
        'yes' => 'دارد',
        'no' => 'ندارد',
    ],

    'health_status' => [
        'healthy' => 'سالم',
        'defective' => 'معیوب',
        'installing' => 'در حال نصب',
    ],

    'standard_status' => [
        'standard' => 'استاندارد',
        'deficit' => 'کسری',
        'surplus' => 'مازاد',
    ],

        // Tabs
        'tabs' => [
            'basic_info' => 'اطلاعات پایه',
            'technical_info' => 'اطلاعات فنی',
            'facilities_equipment' => 'امکانات و تجهیزات',
            'geographic_info' => 'اطلاعات جغرافیایی',
            'additional_info' => 'اطلاعات تکمیلی',
        ],

    // Branch tabs
    'branch_tabs' => [
        'basic_info' => 'اطلاعات پایه',
        'technical_info' => 'اطلاعات فنی',
        'additional_info' => 'اطلاعات تکمیلی',
    ],

    // Region translations
    'region' => [
        'basic_info' => 'اطلاعات پایه',
        'geographic_info' => 'اطلاعات جغرافیایی',
        'name' => 'نام منطقه',
        'type' => 'نوع منطقه',
        'code' => 'کد منطقه',
        'parent' => 'منطقه والد',
        'description' => 'توضیحات',
        'is_active' => 'فعال',
        'ordering' => 'ترتیب',
        'lat' => 'عرض جغرافیایی',
        'lon' => 'طول جغرافیایی',
        'height' => 'ارتفاع',
        'central_address' => 'آدرس مرکزی',
        'central_postal_code' => 'کد پستی مرکزی',
        'central_phone' => 'تلفن مرکزی',
        'central_mobile' => 'موبایل مرکزی',
        'central_fax' => 'فکس مرکزی',
        'central_email' => 'ایمیل مرکزی',
        
        'sections' => [
            'basic_info' => 'اطلاعات پایه',
            'basic_info_desc' => 'اطلاعات اصلی منطقه',
            'status_info' => 'وضعیت',
            'status_info_desc' => 'وضعیت فعال/غیرفعال و ترتیب',
            'coordinates' => 'مختصات جغرافیایی',
            'coordinates_desc' => 'موقعیت جغرافیایی منطقه',
            'central_info' => 'اطلاعات مرکزی',
            'central_info_desc' => 'اطلاعات تماس و آدرس مرکزی',
            'warehouse_location' => 'موقعیت انبار',
        ],
        
        'table' => [
            'name' => 'نام',
            'type' => 'نوع',
            'parent' => 'والد',
            'code' => 'کد',
            'is_active' => 'فعال',
            'lat' => 'عرض',
            'lon' => 'طول',
            'created_at' => 'تاریخ ایجاد',
        ],
        
        'filters' => [
            'type' => 'نوع منطقه',
            'is_active' => 'وضعیت فعال',
            'parent' => 'منطقه والد',
            'all' => 'همه',
            'active' => 'فعال',
            'inactive' => 'غیرفعال',
        ],
        
        'actions' => [
            'create' => 'ایجاد منطقه',
            'view' => 'مشاهده',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
            'delete_selected' => 'حذف انتخاب شده‌ها',
        ],
        
        'navigation' => [
            'singular' => 'تقسیمات کشوری',
            'plural' => 'تقسیمات کشوری',
        ],
    ],

    // Region Type translations
    'region_types' => [
        'country' => 'کشور',
        'headquarter' => 'ستاد',
        'province' => 'استان',
        'branch' => 'شعبه',
        'town' => 'شهرستان',
        'district' => 'ناحیه',
        'rural_district' => 'بخش روستایی',
        'city' => 'شهر',
        'village' => 'روستا',
    ],

    // User translations
    'user' => [
        'name' => 'نام',
        'family' => 'نام خانوادگی',
        'email' => 'ایمیل',
        'mobile' => 'موبایل',
        'username' => 'نام کاربری',
        'password' => 'رمز عبور',
        'password_confirmation' => 'تکرار رمز عبور',
        'roles' => 'نقش‌ها',
        'roles_helper' => 'نقش‌های کاربر را انتخاب کنید',
        
        'sections' => [
            'personal_info' => 'اطلاعات شخصی',
            'personal_info_desc' => 'اطلاعات اصلی کاربر',
            'authentication' => 'احراز هویت',
            'authentication_desc' => 'رمز عبور و اطلاعات ورود',
            'roles' => 'نقش‌ها و دسترسی‌ها',
            'roles_desc' => 'تعیین نقش‌های کاربر در سیستم',
        ],
        
        'table' => [
            'name' => 'نام',
            'family' => 'نام خانوادگی',
            'email' => 'ایمیل',
            'mobile' => 'موبایل',
            'username' => 'نام کاربری',
            'roles' => 'نقش‌ها',
            'created_at' => 'تاریخ ایجاد',
            'email_copied' => 'ایمیل کپی شد',
            'mobile_copied' => 'موبایل کپی شد',
        ],
        
        'filters' => [
            'email_verified' => 'وضعیت ایمیل',
            'all' => 'همه',
            'verified' => 'تایید شده',
            'unverified' => 'تایید نشده',
        ],
        
        'actions' => [
            'create' => 'ایجاد کاربر',
            'view' => 'مشاهده',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
            'delete_selected' => 'حذف انتخاب شده‌ها',
        ],
        
        'navigation' => [
            'singular' => 'کاربر',
            'plural' => 'کاربران',
        ],
    ],

    // Branch translations
    'branch' => [
        'name' => 'نام شعبه',
        'branch_type' => 'نوع شعبه',
        'two_digit_code' => 'کد دو رقمی',
        'date_establishment' => 'تاریخ تاسیس',
        'phone' => 'شماره تماس',
        'fax' => 'شماره فکس',
        'address' => 'آدرس پستی',
        'description' => 'توضیحات',
        'postal_code' => 'کد پستی',
        'coding' => 'کدینگ',
        'vhf_address' => 'کد خطاب VHF',
        'hf_address' => 'کد خطاب HF',
        'vhf_channel' => 'کانال VHF',
        'lat' => 'عرض جغرافیایی (Decimal)',
        'lon' => 'طول جغرافیایی (Decimal)',
        'lat_deg' => 'عرض (درجه)',
        'lat_min' => 'عرض (دقیقه)',
        'lat_sec' => 'عرض (ثانیه)',
        'lon_deg' => 'طول (درجه)',
        'lon_min' => 'طول (دقیقه)',
        'lon_sec' => 'طول (ثانیه)',
        'height' => 'ارتفاع از سطح دریا',
        'closed_thursday' => 'پنجشنبه تعطیل است؟',
        'closure_date' => 'تاریخ تعطیلی',
        'closure_document' => 'سند تعطیلی',
        'date_closed_thursday' => 'تاریخ شروع تعطیلی پنجشنبه‌ها',
        'date_closed_thursday_end' => 'تاریخ پایان تعطیلی پنجشنبه‌ها',
        'img_header' => 'تصویر سردرب شعبه',
        'img_building' => 'تصویر ساختمان شعبه',
        
        // Branch types
        'branch_types' => [
            'county' => 'شهرستان',
            'headquarters' => 'ستاد',
            'branch' => 'شعبه',
            'independent_office' => 'دفترنمایندگی مستقل',
            'dependent_office' => 'دفترنمایندگی وابسته',
            'urban_area' => 'مناطق شهری',
        ],
        
        // Sections
        'sections' => [
            'basic_info' => 'اطلاعات کلی شعبه',
            'basic_info_desc' => 'اطلاعات اصلی و تماس شعبه',
            'contact_info' => 'اطلاعات تماس',
            'contact_info_desc' => 'شماره تماس و آدرس شعبه',
            'communication_info' => 'اطلاعات ارتباطی',
            'communication_info_desc' => 'اطلاعات VHF و HF',
            'geographic_info' => 'اطلاعات جغرافیایی',
            'geographic_info_desc' => 'مختصات جغرافیایی شعبه',
            'closure_info' => 'اطلاعات تعطیلی',
            'closure_info_desc' => 'اطلاعات تعطیلی پنجشنبه‌ها',
            'images' => 'تصاویر',
            'images_desc' => 'تصاویر شعبه',
            'warehouse_location' => 'موقعیت جغرافیایی انبار',
        ],
        
        // Table
        'table' => [
            'name' => 'نام شعبه',
            'branch_type' => 'نوع شعبه',
            'phone' => 'شماره تماس',
            'address' => 'آدرس',
            'closed_thursday' => 'تعطیل پنجشنبه',
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
            'delete_selected' => 'حذف انتخاب شده',
        ],
        
        // Filters
        'filters' => [
            'branch_type' => 'نوع شعبه',
            'closed_thursday' => 'تعطیل پنجشنبه',
            'all' => 'همه',
            'closed' => 'تعطیل',
            'open' => 'باز',
        ],
    ],

    // Sections
    'sections' => [
        'basic_info' => 'اطلاعات پایه انبار',
        'basic_info_desc' => 'اطلاعات کلی و تماس انبار',
        'personnel_info' => 'اطلاعات پرسنلی',
        'personnel_info_desc' => 'مسئول انبار و کاربران انبار',
        'location_info' => 'اطلاعات مکانی',
        'location_info_desc' => 'موقعیت جغرافیایی و آدرس انبار',
        'temporal_info' => 'اطلاعات زمانی و سال تاسیس',
        'temporal_info_desc' => 'سال‌های تاسیس و ساخت انبار',
        'ownership_area' => 'اطلاعات مالکیت و متراژ',
        'ownership_area_desc' => 'نوع مالکیت و مساحت انبار',
        'structure_count' => 'نوع سازه و تعداد',
        'structure_count_desc' => 'ساختار فیزیکی و تعداد سوله‌ها',
        'pallet_info' => 'اطلاعات باکس پالت',
        'pallet_info_desc' => 'تعداد موجودی‌های کوچک و بزرگ',
        'forklift_info' => 'اطلاعات لیفتراک',
        'forklift_info_desc' => 'وضعیت و مشخصات لیفتراک‌ها',
        'other_info' => 'سایر اطلاعات',
        'other_info_desc' => 'بیمه، امنیت و تجهیزات',
        'geographic_info' => 'اطلاعات جغرافیایی',
        'geographic_info_desc' => 'مختصات GPS و موقعیت جغرافیایی',
        'additional_info' => 'اطلاعات اضافی',
        'additional_info_desc' => 'درجه‌بندی و ارزیابی ریسک',
        'keeper_info' => 'اطلاعات انباردار/کاربر انبار',
        'keeper_info_desc' => 'اطلاعات تماس و آدرس انباردار',
        'branch_distance' => 'فاصله تا نزدیک‌ترین شعبه',
        'branch_distance_desc' => 'فاصله تا شعبه‌های نزدیک',
    ],

    // Placeholders
    'placeholders' => [
        'select_option' => 'انتخاب کنید',
        'branch_name' => 'نام شعبه',
        'grade_example' => 'مثال: A، B، C',
    ],

    // Units
    'units' => [
        'square_meter' => 'متر مربع',
        'kilometer' => 'کیلومتر',
        'degree' => 'درجه',
        'meter' => 'متر',
        'person' => 'نفر',
        'count' => 'عدد',
        'percent' => '%',
        'year_sh' => 'ه.ش',
    ],

    // Table
    'table' => [
        'title' => 'عنوان انبار',
        'manager' => 'نام مسئول',
        'usage' => 'کاربری',
        'province' => 'استان',
        'city' => 'شهر',
        'branch' => 'شعبه',
        'area' => 'متراژ',
        'ownership' => 'نوع مالکیت',
        'insurance' => 'بیمه',
        'created_at' => 'تاریخ ایجاد',
        'view' => 'مشاهده',
        'edit' => 'ویرایش',
        'delete' => 'حذف',
        'delete_selected' => 'حذف انتخاب شده',
    ],

    // Filters
    'filters' => [
        'usage_type' => 'کاربری انبار',
        'ownership_type' => 'نوع مالکیت',
        'province' => 'استان',
        'insurance' => 'بیمه انبار',
        'area_range' => 'متراژ',
        'area_from' => 'متراژ از',
        'area_to' => 'متراژ تا',
        'all' => 'همه',
        'has' => 'دارد',
        'has_not' => 'ندارد',
    ],

    // Warehouse Manager translations
    'warehouse_manager' => [
        'first_name' => 'نام',
        'last_name' => 'نام خانوادگی',
        'full_name' => 'نام کامل',
        'national_id' => 'کد ملی',
        'employee_id' => 'کد پرسنلی',
        'birth_date' => 'تاریخ تولد',
        'gender' => 'جنسیت',
        'phone' => 'تلفن ثابت',
        'mobile' => 'موبایل',
        'email' => 'ایمیل',
        'address' => 'آدرس',
        'postal_code' => 'کد پستی',
        'hire_date' => 'تاریخ استخدام',
        'employment_status' => 'وضعیت استخدام',
        'position' => 'سمت',
        'department' => 'بخش',
        'salary' => 'حقوق',
        'job_description' => 'شرح وظایف',
        'warehouse' => 'انبار',
        'user' => 'کاربر سیستم',
        'emergency_contact_name' => 'نام تماس اضطراری',
        'emergency_contact_phone' => 'شماره تماس اضطراری',
        'emergency_contact_relation' => 'نسبت با تماس اضطراری',
        'notes' => 'یادداشت‌ها',
        'certifications' => 'گواهینامه‌ها',
        'skills' => 'مهارت‌ها',
        'is_primary_manager' => 'انباردار اصلی',
        'can_approve_orders' => 'امکان تایید سفارشات',
        'can_manage_inventory' => 'امکان مدیریت موجودی',
        'age' => 'سن',
        'years_of_service' => 'سابقه کار',

        // Gender options
        'genders' => [
            'male' => 'مرد',
            'female' => 'زن',
        ],

        // Employment status options
        'employment_statuses' => [
            'active' => 'فعال',
            'inactive' => 'غیرفعال',
            'terminated' => 'قطع همکاری',
            'retired' => 'بازنشسته',
        ],

        // Sections
        'sections' => [
            'personal_info' => 'اطلاعات شخصی',
            'personal_info_desc' => 'اطلاعات اصلی انباردار',
            'professional_info' => 'اطلاعات شغلی',
            'professional_info_desc' => 'اطلاعات استخدام و شغل',
            'warehouse_assignment' => 'تخصیص انبار',
            'warehouse_assignment_desc' => 'انبار مربوطه و دسترسی‌ها',
            'emergency_contact' => 'تماس اضطراری',
            'emergency_contact_desc' => 'اطلاعات تماس اضطراری',
            'additional_info' => 'اطلاعات تکمیلی',
            'additional_info_desc' => 'مهارت‌ها، گواهینامه‌ها و یادداشت‌ها',
        ],

        // Table columns
        'table' => [
            'name' => 'نام',
            'employee_id' => 'کد پرسنلی',
            'position' => 'سمت',
            'warehouse' => 'انبار',
            'employment_status' => 'وضعیت',
            'hire_date' => 'تاریخ استخدام',
            'years_of_service' => 'سابقه کار',
            'is_primary' => 'اصلی',
            'created_at' => 'تاریخ ایجاد',
        ],

        // Filters
        'filters' => [
            'employment_status' => 'وضعیت استخدام',
            'warehouse' => 'انبار',
            'is_primary' => 'انباردار اصلی',
            'can_approve' => 'امکان تایید',
            'can_manage' => 'امکان مدیریت',
        ],

        // Actions
        'actions' => [
            'create' => 'ایجاد انباردار',
            'view' => 'مشاهده',
            'edit' => 'ویرایش',
            'delete' => 'حذف',
            'delete_selected' => 'حذف انتخاب شده‌ها',
        ],

        // Navigation
        'navigation' => [
            'singular' => 'انباردار',
            'plural' => 'انبارداران',
        ],
    ],
];
