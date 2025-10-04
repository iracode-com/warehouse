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
            
            // Basic warehouse information
            $table->string('title'); // عنوان انبار

            $table->string('telephone')->nullable(); // تلفن
            
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
            $table->enum('diesel_forklift_status', ['healthy', 'defective'])->nullable(); // دیزلی - سالم/معیوب
            $table->enum('gasoline_forklift_status', ['healthy', 'defective'])->nullable(); // بنزینی - سالم/معیوب
            $table->enum('gas_forklift_status', ['healthy', 'defective'])->nullable(); // گازسوز - سالم/معیوب
            $table->enum('forklift_standard', ['standard', 'deficit', 'surplus'])->nullable(); // استاندارد/کسری/مازاد
            $table->decimal('ramp_length', 8, 2)->nullable(); // طول رمپ لیفتراک
            $table->decimal('ramp_height', 8, 2)->nullable(); // ارتفاع رمپ لیفتراک
            
            // Other information
            $table->enum('warehouse_insurance', ['yes', 'no']); // بیمه انبارها
            $table->enum('building_insurance', ['yes', 'no']); // بیمه ابنیه
            $table->enum('fire_suppression_system', ['healthy', 'defective', 'installing'])->nullable(); // سیستم اطفا حریق اتوماتیک
            $table->enum('fire_alarm_system', ['healthy', 'defective', 'installing'])->nullable(); // سیستم اعلان حریق اتوماتیک
            $table->enum('ram_rack', ['yes', 'no']); // رام و راک
            $table->integer('ram_rack_count')->nullable(); // تعداد رام راک
            $table->enum('cctv_system', ['healthy', 'defective', 'installing'])->nullable(); // دوربین مدار بسته
            $table->enum('lighting_system', ['healthy', 'defective'])->nullable(); // سیستم روشنایی استاندارد
            
            // Geographic information
            $table->decimal('longitude', 10, 7)->nullable(); // طول جغرافیایی
            $table->decimal('latitude', 10, 7)->nullable(); // عرض جغرافیایی
            $table->decimal('longitude_e', 10, 7)->nullable(); // طول جغرافیایی (E)
            $table->decimal('latitude_n', 10, 7)->nullable(); // عرض جغرافیایی (N)
            $table->decimal('altitude', 8, 2)->nullable(); // ارتفاع
            $table->text('address'); // آدرس
            
            // Additional information
            $table->integer('branch_establishment_year')->nullable(); // سال تاسیس شعبه
            $table->integer('population_census_1395')->nullable(); // سرشماری جمعیت 1395
            $table->decimal('provincial_risk_percentage', 5, 2)->nullable(); // درصد خطرپذیری استانی
            $table->string('approved_grade')->nullable(); // درجه مصوب
            $table->decimal('warehouse_area', 10, 2)->nullable(); // متراژ سوله (انبار، انباری)
            $table->decimal('gps_x', 10, 7)->nullable(); // GPS x
            $table->decimal('gps_y', 10, 7)->nullable(); // GPS y
            
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
