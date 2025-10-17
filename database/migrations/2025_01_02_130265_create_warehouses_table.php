<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->unsignedBigInteger('base_id')->nullable();
            $table->unsignedBigInteger('shed_id')->nullable();
            $table->unsignedBigInteger('province_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->unsignedBigInteger('town_id')->nullable();
            $table->unsignedBigInteger('village_id')->nullable();
            
            // Basic warehouse information
            $table->string('title'); // عنوان انبار
            $table->string('telephone')->nullable(); // تلفن
            $table->string('manager_name')->nullable(); // نام مدیر
            $table->string('manager_phone')->nullable(); // تلفن مدیر
            
            // Warehouse usage type
            $table->enum('usage_type', [
                'emergency', // امدادی
                'scrap_used', // اسقاط و مستعمل (غیرامدادی)
                'auto_parts', // لوازم و قطعات یدکی خودرو
                'ready_operations', // آماده عملیات
                'air_rescue_parts', // لوازم و قطعات امداد هوایی
                'rescue_equipment', // تجهیزات امداد و نجات
                'temporary' // موقت
            ]);
            
            // Location information
            // $table->string('province')->nullable(); // استان
            // $table->string('branch')->nullable(); // شعبه
            // $table->string('base')->nullable(); // پایگاه
            $table->longText('warehouse_info')->nullable(); // اطلاعات سوله (انبار، انباری)
            
            // Establishment and construction
            $table->integer('establishment_year'); // سال تاسیس
            $table->integer('construction_year'); // سال ساخت
            $table->integer('population_census')->nullable(); // سرشماری جمعیت
            
            // Ownership type
            $table->enum('ownership_type', [
                'owned', // مالکیتی
                'rented', // استیجاری
                'donated' // اهدا
            ]);

            $table->enum('warehouse_standard', ['standard', 'deficit', 'surplus'])->nullable(); // استاندارد/کسری/مازاد

            $table->string('shed_number', 100);
            
            // Area information
            $table->decimal('area', 10, 2)->nullable(); // متراژ
            $table->decimal('under_construction_area', 10, 2)->nullable(); // متراژ در دست ساخت
            
            // Structure type
            $table->enum('structure_type', [
                'concrete', // بتنی
                'metal', // فلزی
                'prefabricated' // پیش‌ساخته
            ]);
            
            $table->integer('warehouse_count')->nullable(); // تعداد سوله
            
            // Pallet box information
            $table->integer('small_inventory_count')->nullable(); // تعداد موجودی کوچک
            $table->integer('large_inventory_count')->nullable(); // تعداد موجودی بزرگ
            
            // Forklift information
            $table->integer('diesel_forklift_healthy_count')->nullable(); // دیزلی - سالم/معیوب
            $table->integer('diesel_forklift_defective_count')->nullable(); // دیزلی - سالم/معیوب
            $table->integer('gasoline_forklift_healthy_count')->nullable(); // بنزینی - سالم/معیوب
            $table->integer('gasoline_forklift_defective_count')->nullable(); // بنزینی - سالم/معیوب
            $table->integer('gas_forklift_healthy_count')->nullable(); // گازسوز - سالم/معیوب
            $table->integer('gas_forklift_defective_count')->nullable(); // گازسوز - سالم/معیوب
            $table->integer('electrical_forklift_healthy_count')->nullable(); // برقی - سالم/معیوب
            $table->integer('electrical_forklift_defective_count')->nullable(); // برقی - سالم/معیوب
            $table->integer('dual_fuel_forklift_healthy_count')->nullable(); // دوگانه سوز - سالم/معیوب
            $table->integer('dual_fuel_forklift_defective_count')->nullable(); // دوگانه سوز - سالم/معیوب
            $table->decimal('ramp_length', 8, 2)->nullable(); // طول رمپ لیفتراک
            $table->decimal('ramp_height', 8, 2)->nullable(); // ارتفاع رمپ لیفتراک
            
            // Other information
            $table->enum('warehouse_insurance', ['yes', 'no']); // بیمه انبارها
            $table->enum('building_insurance', ['yes', 'no']); // بیمه ابنیه
            $table->enum('fire_suppression_system', ['healthy', 'defective', 'installing', 'auto'])->nullable(); // سیستم اطفا حریق اتوماتیک
            $table->enum('fire_alarm_system', ['healthy', 'defective', 'installing', 'auto'])->nullable(); // سیستم اعلان حریق اتوماتیک
            $table->enum('ram_rack', ['yes', 'no']); // رام و راک
            $table->integer('ram_rack_count')->nullable(); // تعداد رام راک
            $table->integer('fire_extinguishers_count')->nullable(); // تعداد کپسول آتش نشانی
            $table->enum('cctv_system', ['healthy', 'defective', 'installing', 'auto'])->nullable(); // دوربین مدار بسته
            $table->enum('lighting_system', ['healthy', 'defective'])->nullable(); // سیستم روشنایی استاندارد
            
            // Additional warehouse infrastructure fields
            $table->enum('flooring_type', ['epoxy', 'good_concrete', 'medium_concrete', 'poor_concrete'])->nullable()->comment('نوع کف سازی انبار');
            $table->enum('window_condition', ['proper_protection', 'suitable_height', 'healthy_glass', 'none'])->nullable()->comment('وضعیت پنجره ها');
            $table->enum('loading_platform', ['under_1m', '1_to_2m', '2_to_3m', '3_to_4m', 'none'])->nullable()->comment('سکوی بارانداز مناسب');
            $table->enum('external_fencing', ['yes', 'no'])->nullable()->comment('حصارکشی محوطه بیرونی');
            $table->enum('ventilation_system', ['both_sides', 'one_side', 'broken', 'none'])->nullable()->comment('تهویه هوای مناسب');
            $table->enum('wall_distance', ['attached', '1_to_2m', '2_to_3m', '3_to_4m', 'over_4m'])->nullable()->comment('فاصله دیوارهای انبار');
            $table->enum('security_guard', ['yes', 'no'])->nullable()->comment('وضعیت نگهبانی/سرایداری');
            
            // Geographic information
            $table->decimal('longitude', 10, 7)->nullable(); // طول جغرافیایی
            $table->decimal('latitude', 10, 7)->nullable(); // عرض جغرافیایی
            $table->decimal('longitude_e', 10, 7)->nullable(); // طول جغرافیایی (E)
            $table->decimal('latitude_n', 10, 7)->nullable(); // عرض جغرافیایی (N)
            $table->decimal('altitude', 8, 2)->nullable(); // ارتفاع
            $table->text('address'); // آدرس

            // طول – عرض- ارتفاع-متراز-در دست ساخت
            $table->decimal('building_length', 18, 2)->nullable();
            $table->decimal('building_width', 18, 2)->nullable();
            $table->decimal('building_height', 18, 2)->nullable();
            $table->decimal('building_metrage', 18, 2)->nullable();
            
            // Additional information
            $table->integer('branch_establishment_year')->nullable(); // سال تاسیس شعبه
            $table->integer('population_census_1395')->nullable(); // سرشماری جمعیت 1395
            $table->decimal('provincial_risk_percentage', 5, 2)->nullable(); // درصد خطرپذیری استانی
            $table->string('approved_grade')->nullable(); // درجه مصوب
            $table->decimal('warehouse_area', 10, 2)->nullable(); // متراژ سوله (انبار، انباری)
            $table->decimal('gps_x', 10, 7)->nullable(); // GPS x
            $table->decimal('gps_y', 10, 7)->nullable(); // GPS y
            
            // Additional location and access fields
            $table->json('natural_hazards')->nullable(); // مخاطرات طبیعی
            $table->string('urban_location')->nullable(); // موقعیت شهری
            $table->string('main_road_access')->nullable(); // دسترسی به جاده اصلی
            $table->string('heavy_vehicle_access')->nullable(); // دسترسی وسایل نقلیه سنگین
            $table->json('terminal_proximity')->nullable(); // مجاورت با ترمینال
            $table->string('parking_facilities')->nullable(); // امکانات پارکینگ
            $table->json('utilities')->nullable(); // انشعابات
            $table->json('neighboring_organizations')->nullable(); // سازمان‌های همسایه
            
            // Warehouse keeper/user information
            $table->string('keeper_name')->nullable(); // نام انباردار/کاربر
            $table->string('keeper_mobile')->nullable(); // شماره موبایل
            $table->text('postal_address')->nullable(); // آدرس دقیق پستی
            
            // Distance to nearest branches
            $table->foreignId('nearest_branch_1_id')->nullable()->constrained('branches'); // شعبه اول
            $table->decimal('distance_to_branch_1', 8, 2)->nullable(); // فاصله تا شعبه اول (به کیلومتر)
            $table->foreignId('nearest_branch_2_id')->nullable()->constrained('branches'); // شعبه دوم
            $table->decimal('distance_to_branch_2', 8, 2)->nullable(); // فاصله تا شعبه دوم (به کیلومتر)

            $table->boolean('status')->default(true); // فعال/غیر فعال
            
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            $table->foreign('base_id')->references('id')->on('bases')->onDelete('set null');
            $table->foreign('shed_id')->references('id')->on('warehouse_sheds')->onDelete('set null');
            $table->foreign('province_id')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('town_id')->references('id')->on('regions')->onDelete('set null');
            $table->foreign('village_id')->references('id')->on('regions')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
