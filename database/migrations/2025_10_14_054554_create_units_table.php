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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('نام واحد شمارش');
            $table->string('symbol')->nullable()->comment('نماد واحد');
            $table->string('code')->unique()->comment('کد واحد');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->string('category')->nullable()->comment('دسته‌بندی (وزن، طول، حجم و...)');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال');
            $table->integer('sort_order')->default(0)->comment('ترتیب نمایش');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
