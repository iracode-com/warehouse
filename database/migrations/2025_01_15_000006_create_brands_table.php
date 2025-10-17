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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('نام برند');
            $table->string('code')->unique()->nullable()->comment('کد برند');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->string('phone')->nullable()->comment('شماره تماس');
            $table->string('email')->nullable()->comment('ایمیل');
            $table->string('website')->nullable()->comment('وب‌سایت');
            $table->string('address')->nullable()->comment('آدرس');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->integer('sort_order')->default(0)->comment('ترتیب نمایش');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
