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
        Schema::create('document_items', function (Blueprint $table) {
            $table->id();

            // ارتباط با سند
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete()->comment('شناسه سند');

            // قلم کالا
            $table->foreignId('item_id')->constrained('items')->restrictOnDelete()->comment('شناسه قلم کالا');

            // مقادیر
            $table->decimal('quantity', 15, 3)->comment('تعداد/مقدار');
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete()->comment('واحد اندازه‌گیری');

            // قیمت‌ها
            $table->decimal('unit_price', 15, 2)->default(0)->comment('قیمت واحد');
            $table->decimal('total_price', 15, 2)->default(0)->comment('قیمت کل');
            $table->decimal('discount_percentage', 5, 2)->default(0)->comment('درصد تخفیف');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('مبلغ تخفیف');
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('درصد مالیات');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('مبلغ مالیات');
            $table->decimal('final_amount', 15, 2)->default(0)->comment('مبلغ نهایی');

            $table->json('item_images')->nullable()->comment('تصاویر کالا');

            // اطلاعات بچ و انقضا (برای ردیابی)
            $table->string('batch_number')->nullable()->comment('شماره بچ/دسته');
            $table->date('expiry_date')->nullable()->comment('تاریخ انقضا');
            $table->date('production_date')->nullable()->comment('تاریخ تولید');

            // مکان در انبار (برای سند رسید)
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete()->comment('زون');
            $table->foreignId('rack_id')->nullable()->constrained('racks')->nullOnDelete()->comment('قفسه');
            $table->foreignId('shelf_level_id')->nullable()->constrained('shelf_levels')->nullOnDelete()->comment('طبقه');
            $table->foreignId('pallet_id')->nullable()->constrained('pallets')->nullOnDelete()->comment('پالت');

            // یادداشت
            $table->text('notes')->nullable()->comment('یادداشت‌ها');

            // ترتیب نمایش
            $table->integer('sort_order')->default(0)->comment('ترتیب');

            $table->timestamps();

            // Indexes
            $table->index('document_id');
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_items');
    }
};
