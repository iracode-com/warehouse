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
        Schema::create('shelf_levels', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('کد طبقه (شناسه یکتا)');
            $table->string('name')->comment('نام طبقه');
            $table->unsignedBigInteger('rack_id')->comment('شناسه قفسه');
            $table->integer('level_number')->comment('شماره طبقه');
            $table->decimal('max_weight', 10, 2)->comment('حداکثر وزن قابل تحمل (کیلوگرم)');
            $table->enum('allowed_product_type', [
                'general', // عمومی
                'hazardous', // مواد خطرناک
                'auto_parts', // قطعات یدکی
                'emergency_supplies', // تجهیزات امدادی
                'fragile', // شکننده
                'heavy_duty', // سنگین
                'temperature_sensitive' // حساس به دما
            ])->comment('نوع کالای مجاز');
            $table->enum('occupancy_status', [
                'empty', // خالی
                'partial', // نیمه‌پر
                'full' // پر
            ])->default('empty')->comment('وضعیت اشغال');
            $table->decimal('current_weight', 10, 2)->default(0)->comment('وزن فعلی (کیلوگرم)');
            $table->decimal('height', 8, 2)->comment('ارتفاع طبقه (متر)');
            $table->decimal('width', 8, 2)->comment('عرض طبقه (متر)');
            $table->decimal('depth', 8, 2)->comment('عمق طبقه (متر)');
            $table->text('description')->nullable()->comment('توضیحات طبقه');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->timestamps();

            $table->foreign('rack_id')->references('id')->on('racks')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shelf_levels');
    }
};
