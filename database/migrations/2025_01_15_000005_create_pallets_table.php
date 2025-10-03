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
        Schema::create('pallets', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('کد پالت/باکس (شناسه یکتا)');
            $table->string('name')->comment('نام پالت/باکس');
            $table->enum('pallet_type', [
                'small', // کوچک
                'large', // بزرگ
                'standard', // استاندارد
                'euro_pallet', // پالت اروپایی
                'american_pallet', // پالت آمریکایی
                'custom' // سفارشی
            ])->comment('نوع پالت/باکس');
            $table->decimal('length', 8, 2)->comment('طول (متر)');
            $table->decimal('width', 8, 2)->comment('عرض (متر)');
            $table->decimal('height', 8, 2)->comment('ارتفاع (متر)');
            $table->decimal('max_weight', 10, 2)->comment('وزن قابل تحمل (کیلوگرم)');
            $table->decimal('current_weight', 10, 2)->default(0)->comment('وزن فعلی (کیلوگرم)');
            $table->unsignedBigInteger('shelf_level_id')->nullable()->comment('شناسه طبقه فعلی');
            $table->string('current_position')->nullable()->comment('موقعیت فعلی (کد راهرو + کد قفسه + کد طبقه)');
            $table->enum('status', [
                'available', // در دسترس
                'occupied', // اشغال شده
                'maintenance', // تعمیر
                'damaged' // آسیب دیده
            ])->default('available')->comment('وضعیت پالت');
            $table->text('description')->nullable()->comment('توضیحات پالت');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->timestamps();

            $table->foreign('shelf_level_id')->references('id')->on('shelf_levels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pallets');
    }
};
