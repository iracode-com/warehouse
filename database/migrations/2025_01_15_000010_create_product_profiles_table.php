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
        Schema::create('product_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique()->comment('کد کالا (Stock Keeping Unit)');
            $table->string('name')->comment('نام کالا');
            $table->longText('description')->nullable()->comment('توضیحات کالا');
            $table->unsignedBigInteger('category_id')->comment('شناسه دسته‌بندی');
            $table->string('brand')->nullable()->comment('برند کالا');
            $table->string('model')->nullable()->comment('مدل کالا');
            $table->string('barcode')->nullable()->comment('بارکد کالا');
            $table->string('qr_code')->nullable()->comment('کد QR');
            
            // Physical properties - ویژگی‌های فیزیکی ثابت
            $table->decimal('weight', 10, 2)->nullable()->comment('وزن (کیلوگرم)');
            $table->decimal('length', 8, 2)->nullable()->comment('طول (سانتی‌متر)');
            $table->decimal('width', 8, 2)->nullable()->comment('عرض (سانتی‌متر)');
            $table->decimal('height', 8, 2)->nullable()->comment('ارتفاع (سانتی‌متر)');
            $table->decimal('volume', 10, 2)->nullable()->comment('حجم (لیتر)');
            
            // Product specifications - مشخصات محصول
            $table->string('unit_of_measure')->nullable()->comment('واحد اندازه‌گیری');
            $table->string('manufacturer')->nullable()->comment('تولیدکننده');
            $table->string('country_of_origin')->nullable()->comment('کشور سازنده');
            $table->integer('shelf_life_days')->nullable()->comment('مدت ماندگاری (روز)');
            $table->decimal('standard_cost', 10, 2)->nullable()->comment('قیمت استاندارد');
            
            // Status and metadata
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active')->comment('وضعیت شناسنامه');
            $table->json('custom_attributes')->nullable()->comment('ویژگی‌های سفارشی (JSON)');
            $table->json('images')->nullable()->comment('تصاویر کالا (JSON)');
            $table->json('documents')->nullable()->comment('اسناد کالا (JSON)');
            $table->json('specifications')->nullable()->comment('مشخصات کالا (JSON)');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            
            // Indexes
            $table->index(['category_id', 'status']);
            $table->index(['sku', 'status']);
            $table->index('barcode');
            $table->index('brand');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_profiles');
    }
};
