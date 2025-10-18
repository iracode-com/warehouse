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
            
            // Document relationship
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade')->comment('شناسه سند');
            
            // Product Profile relationship
            $table->foreignId('product_profile_id')->constrained('product_profiles')->onDelete('cascade')->comment('شناسه شناسنامه کالا');
            
            // Quantity and pricing
            $table->decimal('quantity', 10, 3)->comment('تعداد');
            $table->foreignId('unit_id')->constrained('units')->comment('واحد اندازه‌گیری');
            $table->decimal('unit_price', 12, 2)->default(0)->comment('قیمت واحد');
            $table->decimal('total_price', 12, 2)->default(0)->comment('قیمت کل');
            
            // Discount and tax
            $table->decimal('discount_percentage', 5, 2)->default(0)->comment('درصد تخفیف');
            $table->decimal('discount_amount', 12, 2)->default(0)->comment('مبلغ تخفیف');
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('درصد مالیات');
            $table->decimal('tax_amount', 12, 2)->default(0)->comment('مبلغ مالیات');
            $table->decimal('final_amount', 12, 2)->default(0)->comment('مبلغ نهایی');
            
            // Batch and expiry information
            $table->string('batch_number')->nullable()->comment('شماره بچ');
            $table->date('expiry_date')->nullable()->comment('تاریخ انقضا');
            $table->date('production_date')->nullable()->comment('تاریخ تولید');
            
            // Location information
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete()->comment('شناسه منطقه');
            $table->foreignId('rack_id')->nullable()->constrained('racks')->nullOnDelete()->comment('شناسه قفسه');
            $table->foreignId('shelf_level_id')->nullable()->constrained('shelf_levels')->nullOnDelete()->comment('شناسه طبقه');
            $table->foreignId('pallet_id')->nullable()->constrained('pallets')->nullOnDelete()->comment('شناسه پالت');
            
            // Additional information
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->json('item_images')->nullable()->comment('تصاویر کالا');
            $table->integer('sort_order')->default(0)->comment('ترتیب نمایش');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['document_id', 'product_profile_id']);
            $table->index(['document_id', 'sort_order']);
            $table->index('expiry_date');
            $table->index('batch_number');
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
