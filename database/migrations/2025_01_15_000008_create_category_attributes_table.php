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
        Schema::create('category_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->comment('شناسه دسته‌بندی');
            $table->string('name')->comment('نام فیلد');
            $table->string('label')->comment('برچسب نمایشی');
            $table->enum('type', [
                'text', 'number', 'date', 'datetime', 'boolean', 
                'select', 'multiselect', 'textarea', 'file', 'image'
            ])->comment('نوع فیلد');
            $table->json('options')->nullable()->comment('گزینه‌های انتخابی (برای select)');
            $table->text('default_value')->nullable()->comment('مقدار پیش‌فرض');
            $table->boolean('is_required')->default(false)->comment('آیا اجباری است');
            $table->boolean('is_searchable')->default(false)->comment('آیا قابل جستجو است');
            $table->boolean('is_filterable')->default(false)->comment('آیا قابل فیلتر است');
            $table->integer('order_index')->default(0)->comment('ترتیب نمایش');
            $table->json('validation_rules')->nullable()->comment('قوانین اعتبارسنجی (JSON)');
            $table->text('help_text')->nullable()->comment('متن راهنما');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            
            // Indexes
            $table->index(['category_id', 'is_active']);
            $table->index(['category_id', 'order_index']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_attributes');
    }
};
