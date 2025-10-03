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
        Schema::create('personnels', function (Blueprint $table) {
            $table->id()->comment('شناسه یکتا');

            $table->string("name", 100)->comment('نام');
            $table->string("family", 100)->comment('نام خانوادگی');
            $table->string("fathername", 100)->nullable()->comment('نام پدر');

            $table->enum("gender", [1, 2])->default(1)->comment('جنسیت: 1=>مرد, 2=>زن');
            $table->enum("marital_status", [1, 2])->default(1)->comment('وضعیت تأهل: 1=>مجرد, 2=>متأهل');

            $table->string("national_code", 10)->nullable()->comment('کد ملی');
            $table->string("identity_code", 10)->nullable()->comment('کد شناسایی');
            $table->string("personnel_code", 50)->nullable()->comment('کد پرسنلی');

            $table->foreignId("sodoor_city_id")->nullable()->constrained('regions')->comment('شهر صدور شناسنامه');

            $table->enum("is_iranian", [1, 2])->default(1)->comment('تابعیت: 1=>ایرانی, 2=>غیر ایرانی');

            $table->string("passport_number", 50)->nullable()->comment('شماره گذرنامه');

            $table->foreignId("nationality_country_id")->nullable()->constrained('regions')->comment('کشور تابعیت');
            $table->foreignId("country_id")->nullable()->constrained('regions')->comment('کشور محل سکونت');
            $table->foreignId("province_id")->nullable()->constrained('regions')->comment('استان محل سکونت');
            $table->foreignId("city_id")->nullable()->constrained('regions')->comment('شهر محل سکونت');

            $table->string("address", 500)->nullable()->comment('آدرس محل سکونت');

            $table->string("mobile", 11)->nullable()->comment('شماره موبایل');
            $table->string("phone", 11)->nullable()->comment('شماره تلفن ثابت');
            $table->string("email")->nullable()->comment('ایمیل');

            $table->date('birth_date')->nullable()->comment('تاریخ تولد');
            $table->datetime('start_hire_date')->nullable()->comment('تاریخ شروع استخدام');
            $table->datetime('end_hire_date')->nullable()->comment('تاریخ پایان استخدام');

            $table->foreignId("position_id")->nullable()->constrained('ar_positions')->comment('سمت سازمانی');
            $table->foreignId("employment_type_id")->nullable()->constrained('ar_employment_types')->comment('نوع استخدام');
            $table->foreignId("cooperation_type_id")->nullable()->constrained('ar_cooperation_types')->comment('نوع همکاری');
            $table->foreignId("education_degree_id")->nullable()->constrained('edu_education_degrees')->comment('مدرک تحصیلی');
            $table->foreignId("education_field_id")->nullable()->constrained('edu_education_fields')->comment('رشته تحصیلی');

            $table->foreignId("user_id")->nullable()->constrained('users')->comment('شناسه کاربر مرتبط');

            $table->tinyInteger("status")->default(1)->comment('وضعیت فعال یا غیرفعال (1=فعال)');
            $table->text("description")->nullable()->comment('توضیحات تکمیلی');

            $table->timestamps();

            $table->foreignId('created_by')->nullable()->constrained('users')->comment('ایجاد شده توسط کاربر');
            $table->foreignId('updated_by')->nullable()->constrained('users')->comment('آخرین تغییر توسط کاربر');

            $table->comment('جدول اطلاعات پرسنل');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnels');
    }
};