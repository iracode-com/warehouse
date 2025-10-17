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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('کد دسته‌بندی (شناسه یکتا)');
            $table->string('name')->comment('نام دسته‌بندی');
            $table->text('description')->nullable()->comment('توضیحات دسته‌بندی');
            $table->integer('hierarchy_level')->comment('سطح سلسله‌مراتبی (1=اصلی، 2=زیر، 3=زیرزیر، 4=کالا)');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('شناسه دسته والد');
            $table->integer('order_index')->default(0)->comment('ترتیب نمایش (اولویت)');
            $table->enum('status', ['active', 'inactive', 'archived'])->default('active')->comment('وضعیت دسته‌بندی');
            $table->string('icon')->nullable()->comment('آیکون دسته‌بندی');
            $table->string('color')->nullable()->comment('رنگ دسته‌بندی');
            $table->json('metadata')->nullable()->comment('اطلاعات اضافی (JSON)');
            $table->boolean('is_leaf')->default(false)->comment('آیا این دسته نهایی است (کالا)');
            $table->string('full_path')->nullable()->comment('مسیر کامل در سلسله‌مراتب');
            $table->integer('children_count')->default(0)->comment('تعداد زیردسته‌ها');
            $table->integer('items_count')->default(0)->comment('تعداد کالاهای این دسته');
            $table->string('category_type')->nullable()->comment('نوع دسته‌بندی');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            
            // Indexes
            $table->index(['parent_id', 'order_index']);
            $table->index(['hierarchy_level', 'status']);
            $table->index('full_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
