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
        Schema::create('packaging_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('نام نوع بسته‌بندی');
            $table->string('code')->unique()->nullable()->comment('کد نوع بسته‌بندی');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->string('unit')->nullable()->comment('واحد اندازه‌گیری');
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
        Schema::dropIfExists('packaging_types');
    }
};
