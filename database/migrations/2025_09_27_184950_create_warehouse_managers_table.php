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
        Schema::create('warehouse_managers', function (Blueprint $table) {
            $table->id();
            
            // Basic information
            $table->string('first_name'); // نام
            $table->string('last_name'); // نام خانوادگی
            $table->string('national_id')->unique(); // کد ملی
            $table->string('employee_id')->unique(); // کد پرسنلی
            $table->date('birth_date'); // تاریخ تولد
            $table->enum('gender', ['male', 'female']); // جنسیت
            $table->string('phone')->nullable(); // شماره تلفن ثابت
            $table->string('mobile'); // شماره موبایل
            $table->string('email')->nullable(); // ایمیل
            $table->text('address'); // آدرس
            $table->string('postal_code')->nullable(); // کد پستی
            
            // Professional information
            $table->date('hire_date'); // تاریخ استخدام
            $table->enum('employment_status', ['active', 'inactive', 'terminated', 'retired']); // وضعیت استخدام
            $table->string('position'); // سمت
            $table->string('department')->nullable(); // بخش
            $table->decimal('salary', 12, 2)->nullable(); // حقوق
            $table->text('job_description')->nullable(); // شرح وظایف
            
            // Warehouse assignment
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade'); // انبار مربوطه
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // کاربر سیستم (اختیاری)
            
            // Emergency contact
            $table->string('emergency_contact_name')->nullable(); // نام تماس اضطراری
            $table->string('emergency_contact_phone')->nullable(); // شماره تماس اضطراری
            $table->string('emergency_contact_relation')->nullable(); // نسبت با تماس اضطراری
            
            // Additional information
            $table->text('notes')->nullable(); // یادداشت‌ها
            $table->json('certifications')->nullable(); // گواهینامه‌ها و مدارک
            $table->json('skills')->nullable(); // مهارت‌ها
            $table->boolean('is_primary_manager')->default(false); // انباردار اصلی
            $table->boolean('can_approve_orders')->default(false); // امکان تایید سفارشات
            $table->boolean('can_manage_inventory')->default(true); // امکان مدیریت موجودی
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_managers');
    }
};
