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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            
            // Product Profile relationship
            $table->unsignedBigInteger('product_profile_id')->comment('شناسه شناسنامه کالا');
            $table->foreignId('source_document_id')->nullable()->constrained('documents')->nullOnDelete()->comment('سند مبدا');
            
            // Item specific fields
            $table->string('serial_number')->nullable()->comment('شماره سریال');
            $table->string('barcode')->nullable()->comment('بارکد کالا');
            $table->string('qr_code')->nullable()->comment('QR Code کالا');
            
            // Inventory properties
            $table->integer('current_stock')->default(0)->comment('موجودی فعلی');
            $table->integer('min_stock')->default(0)->comment('حداقل موجودی');
            $table->integer('max_stock')->nullable()->comment('حداکثر موجودی');
            $table->decimal('unit_cost', 10, 2)->nullable()->comment('هزینه واحد');
            $table->decimal('selling_price', 10, 2)->nullable()->comment('قیمت فروش');
            
            // Status and dates
            $table->enum('status', ['active', 'inactive', 'discontinued', 'recalled'])->default('active')->comment('وضعیت کالا');
            $table->date('manufacture_date')->nullable()->comment('تاریخ تولید');
            $table->date('production_date')->nullable()->comment('تاریخ تولید');
            $table->date('expiry_date')->nullable()->comment('تاریخ انقضا');
            $table->date('purchase_date')->nullable()->comment('تاریخ خرید');
            $table->string('batch_number', 100)->nullable()->comment('شماره بچ/دسته');
            
            // Location
            $table->unsignedBigInteger('warehouse_id')->nullable()->comment('شناسه انبار');
            $table->unsignedBigInteger('zone_id')->nullable()->comment('شناسه منطقه');
            $table->unsignedBigInteger('rack_id')->nullable()->comment('شناسه قفسه');
            $table->unsignedBigInteger('shelf_level_id')->nullable()->comment('شناسه طبقه');
            $table->unsignedBigInteger('pallet_id')->nullable()->comment('شناسه پالت');
            
            // Additional properties
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('product_profile_id')->references('id')->on('product_profiles')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('set null');
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('set null');
            $table->foreign('rack_id')->references('id')->on('racks')->onDelete('set null');
            $table->foreign('shelf_level_id')->references('id')->on('shelf_levels')->onDelete('set null');
            $table->foreign('pallet_id')->references('id')->on('pallets')->onDelete('set null');
            
            // Indexes
            $table->index(['product_profile_id', 'status']);
            $table->index(['warehouse_id', 'zone_id']);
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
