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
        Schema::create('positions', function (Blueprint $table) {
            $table->id()->comment('شناسه یکتا');
            $table->string('name')->comment('عنوان سمت یا پوزیشن');
            
            $table->tinyInteger('status')->default(1);
            
            
            $table->text('description')->nullable()->comment('توضیحات سمت');

            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('created_by')->nullable()->constrained('users')->comment('ایجاد شده توسط کاربر');
            $table->foreignId('updated_by')->nullable()->constrained('users')->comment('آخرین تغییر توسط کاربر');

            $table->comment('جدول سمت‌ها (پوزیشن‌های سازمانی)');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
