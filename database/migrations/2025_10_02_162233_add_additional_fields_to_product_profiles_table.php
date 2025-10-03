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
        Schema::table('product_profiles', function (Blueprint $table) {
            // دسته‌بندی کالا
            $table->enum('category_type', ['relief', 'non_relief', 'equipment', 'transport', 'support', 'scrap', 'defective', 'assets'])
                ->nullable()->after('category_id')->comment('نوع دسته‌بندی کالا');
            
            // نوع بسته‌بندی
            $table->enum('packaging_type', ['carton', 'shrink', 'box'])->nullable()->after('category_type')->comment('نوع بسته‌بندی');
            
            // نوع کالا
            $table->enum('product_type', ['consumable', 'capital'])->nullable()->after('packaging_type')->comment('نوع کالا');
            
            // واحدهای شمارش
            $table->string('primary_unit')->nullable()->after('unit_of_measure')->comment('واحد شمارش اصلی');
            $table->string('secondary_unit')->nullable()->after('primary_unit')->comment('واحد شمارش فرعی');
            
            // روش قیمت‌گذاری
            $table->enum('pricing_method', ['FIFO', 'LIFO', 'FEFO'])->nullable()->after('standard_cost')->comment('روش قیمت‌گذاری');
            
            // ویژگی‌ها
            $table->string('feature_1')->nullable()->after('pricing_method')->comment('ویژگی 1');
            $table->string('feature_2')->nullable()->after('feature_1')->comment('ویژگی 2');
            
            // تاریخ انقضا
            $table->boolean('has_expiry_date')->default(false)->after('feature_2')->comment('دارای تاریخ انقضا');
            
            // وضعیت‌های مختلف
            $table->enum('consumption_status', ['high_consumption', 'strategic', 'low_consumption', 'stagnant'])
                ->nullable()->after('has_expiry_date')->comment('وضعیت مصرف');
            $table->boolean('is_flammable')->default(false)->after('consumption_status')->comment('وضعیت اشتعال');
            $table->boolean('has_return_policy')->default(false)->after('is_flammable')->comment('وضعیت عودت پذیری');
            
            // آدرس کالا
            $table->string('product_address')->nullable()->after('has_return_policy')->comment('آدرس کالا');
            
            // حداقل موجودی و نقطه سفارش (JSON برای ذخیره بر اساس مکان)
            $table->json('minimum_stock_by_location')->nullable()->after('product_address')->comment('حداقل موجودی بر اساس مکان');
            $table->json('reorder_point_by_location')->nullable()->after('minimum_stock_by_location')->comment('نقطه سفارش بر اساس مکان');
            
            // مشخصات فنی و شرایط نگهداری
            $table->boolean('has_technical_specs')->default(false)->after('reorder_point_by_location')->comment('دارای مشخصات فنی');
            $table->text('technical_specs')->nullable()->after('has_technical_specs')->comment('مشخصات فنی');
            $table->boolean('has_storage_conditions')->default(false)->after('technical_specs')->comment('دارای شرایط نگهداری');
            $table->text('storage_conditions')->nullable()->after('has_storage_conditions')->comment('شرایط نگهداری');
            
            // بازرسی کالا
            $table->boolean('has_inspection')->default(false)->after('storage_conditions')->comment('دارای بازرسی کالا');
            $table->text('inspection_details')->nullable()->after('has_inspection')->comment('جزئیات بازرسی');
            
            // کالای مشابه
            $table->boolean('has_similar_products')->default(false)->after('inspection_details')->comment('دارای کالای مشابه');
            $table->json('similar_products')->nullable()->after('has_similar_products')->comment('لیست کالاهای مشابه');
            
            // ارزش ریالی تقریبی
            $table->decimal('estimated_value', 15, 2)->nullable()->after('similar_products')->comment('ارزش ریالی تقریبی');
            $table->decimal('annual_inflation_rate', 5, 2)->default(0)->after('estimated_value')->comment('نرخ تورم سالیانه');
            
            // انبارهای مرتبط
            $table->json('related_warehouses')->nullable()->after('annual_inflation_rate')->comment('انبارهای مرتبط');
            
            // توضیحات اضافی
            $table->text('additional_description')->nullable()->after('notes')->comment('توضیحات اضافی');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'category_type',
                'packaging_type',
                'product_type',
                'primary_unit',
                'secondary_unit',
                'pricing_method',
                'feature_1',
                'feature_2',
                'has_expiry_date',
                'consumption_status',
                'is_flammable',
                'has_return_policy',
                'product_address',
                'minimum_stock_by_location',
                'reorder_point_by_location',
                'has_technical_specs',
                'technical_specs',
                'has_storage_conditions',
                'storage_conditions',
                'has_inspection',
                'inspection_details',
                'has_similar_products',
                'similar_products',
                'estimated_value',
                'annual_inflation_rate',
                'related_warehouses',
                'additional_description'
            ]);
        });
    }
};
