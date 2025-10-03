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
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('نام قانون');
            $table->text('description')->nullable()->comment('توضیحات قانون');
            $table->unsignedBigInteger('category_id')->nullable()->comment('شناسه دسته‌بندی هدف');
            $table->unsignedBigInteger('attribute_id')->nullable()->comment('شناسه فیلد هدف');
            $table->enum('rule_type', [
                'numeric', 'date', 'string', 'boolean', 'json', 'custom'
            ])->comment('نوع قانون');
            $table->enum('condition_type', [
                'equals', 'not_equals', 'greater_than', 'less_than', 
                'greater_equal', 'less_equal', 'contains', 'not_contains',
                'in', 'not_in', 'between', 'not_between', 'is_null', 'is_not_null'
            ])->comment('نوع شرط');
            $table->text('condition_value')->nullable()->comment('مقدار شرط');
            $table->json('condition_values')->nullable()->comment('مقادیر شرط (برای in, between)');
            $table->enum('alert_type', [
                'info', 'warning', 'error', 'critical'
            ])->default('warning')->comment('نوع هشدار');
            $table->string('alert_title')->comment('عنوان هشدار');
            $table->text('alert_message')->comment('پیام هشدار');
            $table->json('alert_recipients')->nullable()->comment('گیرندگان هشدار (JSON)');
            $table->integer('priority')->default(1)->comment('اولویت قانون (1-10)');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->boolean('is_realtime')->default(false)->comment('آیا در زمان واقعی چک شود');
            $table->integer('check_interval')->default(3600)->comment('فاصله چک (ثانیه)');
            $table->datetime('last_checked')->nullable()->comment('آخرین زمان چک');
            $table->integer('trigger_count')->default(0)->comment('تعداد فعال‌سازی');
            $table->json('metadata')->nullable()->comment('اطلاعات اضافی');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('attribute_id')->references('id')->on('category_attributes')->onDelete('cascade');
            
            // Indexes
            $table->index(['category_id', 'is_active']);
            $table->index(['rule_type', 'is_active']);
            $table->index(['priority', 'is_active']);
            $table->index('last_checked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
