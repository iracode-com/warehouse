<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('کد منطقه (شناسه یکتا)');
            $table->string('name')->comment('نام منطقه');
            $table->enum('zone_type', [
                'cold_storage', // ذخیره‌سازی سرد
                'hot_storage', // ذخیره‌سازی گرم
                'general', // عمومی
                'hazardous_materials', // مواد خطرناک
                'auto_parts', // لوازم یدکی خودرو
                'emergency_supplies', // تجهیزات امدادی
                'temporary' // موقت
            ])->comment('نوع منطقه');
            $table->decimal('capacity_cubic_meters', 10, 2)->nullable()->comment('ظرفیت منطقه (مترمکعب)');
            $table->integer('capacity_pallets')->nullable()->comment('ظرفیت منطقه (تعداد پالت)');
            $table->decimal('temperature', 5, 2)->nullable()->comment('دمای منطقه (درجه سانتی‌گراد)');
            $table->text('description')->nullable()->comment('توضیحات منطقه');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            // $table->unsignedBigInteger('warehouse_id')->comment('شناسه انبار');
            $table->foreignId('warehouse_id')
                ->constrained('warehouses', 'id', 'zone_warehouse_id')
                ->onDelete('cascade');
                
            $table->timestamps();

            // $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn([
                'code',
                'name',
                'zone_type',
                'capacity_cubic_meters',
                'capacity_pallets',
                'temperature',
                'description',
                'is_active',
                'warehouse_id'
            ]);
        });
    }
};
