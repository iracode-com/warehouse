<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('education_degrees', function (Blueprint $table) {
            $table->id()->comment('شناسه یکتا');

            $table->string("name", 100)->comment('عنوان مدرک تحصیلی');
            $table->tinyInteger("status")->default(1)->comment('وضعیت فعال یا غیرفعال (1=فعال)');
            $table->text("description")->nullable()->comment('توضیحات تکمیلی مدرک تحصیلی');

            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained('users')->comment('ایجاد شده توسط کاربر');
            $table->foreignId('updated_by')->nullable()->constrained('users')->comment('آخرین تغییر توسط کاربر');

            $table->comment('جدول مدارک تحصیلی');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education_degrees');
    }
};
