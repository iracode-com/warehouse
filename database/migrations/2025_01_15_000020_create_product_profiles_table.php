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
            $table->string('category_type')->nullable()->comment('نوع دسته‌بندی کالا');
            $table->unsignedBigInteger('packaging_type_id')->nullable()->comment('شناسه نوع بسته‌بندی');
            $table->string('product_type')->nullable()->comment('نوع کالا');
            $table->unsignedBigInteger('brand_id')->nullable()->comment('شناسه برند');
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
            $table->unsignedBigInteger('unit_of_measure_id')->nullable()->comment('شناسه واحد اندازه‌گیری');
            $table->string('primary_unit')->nullable()->comment('واحد شمارش اصلی');
            $table->unsignedBigInteger('primary_unit_id')->nullable()->comment('شناسه واحد شمارش اصلی');
            $table->string('secondary_unit')->nullable()->comment('واحد شمارش فرعی');
            $table->unsignedBigInteger('secondary_unit_id')->nullable()->comment('شناسه واحد شمارش فرعی');
            $table->string('manufacturer')->nullable()->comment('تولیدکننده');
            $table->string('country_of_origin')->nullable()->comment('کشور سازنده');
            $table->integer('shelf_life_days')->nullable()->comment('مدت ماندگاری (روز)');
            $table->decimal('standard_cost', 10, 2)->nullable()->comment('قیمت استاندارد');
            $table->string('pricing_method')->nullable()->comment('روش قیمت‌گذاری');
            $table->string('feature_1')->nullable()->comment('ویژگی 1');
            $table->string('feature_2')->nullable()->comment('ویژگی 2');
            $table->boolean('has_expiry_date')->default(false)->comment('دارای تاریخ انقضا');
            $table->string('consumption_status')->nullable()->comment('وضعیت مصرف');
            $table->boolean('is_flammable')->default(false)->comment('وضعیت اشتعال');
            $table->boolean('has_return_policy')->default(false)->comment('وضعیت عودت پذیری');
            $table->string('product_address')->nullable()->comment('آدرس کالا');
            $table->json('minimum_stock_by_location')->nullable()->comment('حداقل موجودی بر اساس مکان');
            $table->json('reorder_point_by_location')->nullable()->comment('نقطه سفارش بر اساس مکان');
            $table->boolean('has_technical_specs')->default(false)->comment('دارای مشخصات فنی');
            $table->text('technical_specs')->nullable()->comment('مشخصات فنی');
            $table->boolean('has_storage_conditions')->default(false)->comment('دارای شرایط نگهداری');
            $table->text('storage_conditions')->nullable()->comment('شرایط نگهداری');
            $table->boolean('has_inspection')->default(false)->comment('دارای بازرسی کالا');
            $table->text('inspection_details')->nullable()->comment('جزئیات بازرسی');
            $table->boolean('has_similar_products')->default(false)->comment('دارای کالای مشابه');
            $table->json('similar_products')->nullable()->comment('لیست کالاهای مشابه');
            $table->decimal('estimated_value', 15, 2)->nullable()->comment('ارزش ریالی تقریبی');
            $table->decimal('annual_inflation_rate', 5, 2)->default(0)->comment('نرخ تورم سالیانه');
            $table->json('related_warehouses')->nullable()->comment('انبارهای مرتبط');
            $table->text('additional_description')->nullable()->comment('توضیحات اضافی');
            
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
            $table->foreign('packaging_type_id')->references('id')->on('packaging_types')->onDelete('set null');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('unit_of_measure_id')->references('id')->on('units')->onDelete('set null');
            $table->foreign('primary_unit_id')->references('id')->on('units')->onDelete('set null');
            $table->foreign('secondary_unit_id')->references('id')->on('units')->onDelete('set null');
            
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
