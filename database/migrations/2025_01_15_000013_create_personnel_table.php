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
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('نام');
            $table->string('family')->comment('نام خانوادگی');
            $table->string('full_name')->nullable()->comment('نام کامل');
            $table->string('fathername')->nullable()->comment('نام پدر');
            $table->enum('gender', ['male', 'female'])->comment('جنسیت');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->comment('وضعیت تاهل');
            $table->string('personnel_code')->unique()->nullable()->comment('کد پرسنلی');
            $table->string('national_code', 10)->unique()->nullable()->comment('کد ملی');
            $table->string('identity_code', 10)->unique()->nullable()->comment('کد شناسنامه');
            $table->foreignId('sodoor_city_id')->nullable()->constrained('regions', 'id', 'pp_sodoor_city_id')->comment('شهر صدور شناسنامه');
            $table->enum('is_iranian', [1, 2])->default(1)->comment('نوع ملیت (1: ایرانی، 2: غیرایرانی)');
            $table->string('passport_number')->unique()->nullable()->comment('شماره پاسپورت');
            $table->foreignId('nationality_country_id')->nullable()->constrained('regions', 'id', 'pp_nationality_country_id')->comment('کشور ملیت');
            $table->date('birth_date')->nullable()->comment('تاریخ تولد');
            $table->string('mobile', 11)->nullable()->comment('موبایل');
            $table->string('phone', 11)->nullable()->comment('تلفن');
            $table->string('email')->nullable()->comment('ایمیل');
            $table->foreignId('country_id')->constrained('regions', 'id', 'pp_country_id')->comment('کشور');
            $table->foreignId('province_id')->constrained('regions', 'id', 'pp_province_id')->comment('استان');
            $table->foreignId('city_id')->constrained('regions', 'id', 'pp_city_id')->comment('شهر');
            $table->text('address')->nullable()->comment('آدرس');
            $table->datetime('start_hire_date')->nullable()->comment('تاریخ شروع استخدام');
            $table->datetime('end_hire_date')->nullable()->comment('تاریخ پایان استخدام');
            $table->foreignId('position_id')->nullable()->constrained('positions')->comment('سمت');
            $table->foreignId('employment_type_id')->nullable()->constrained('employment_types')->comment('نوع استخدام');
            $table->foreignId('cooperation_type_id')->nullable()->constrained('cooperation_types')->comment('نوع همکاری');
            $table->string('expert_field')->nullable()->comment('رشته تخصصی');
            $table->string('resume_file')->nullable()->comment('فایل رزومه');
            $table->boolean('eoc_membership')->default(false)->comment('عضویت در EOC');
            $table->foreignId('education_degree_id')->nullable()->constrained('education_degrees')->comment('مقطع تحصیلی');
            $table->foreignId('education_field_id')->nullable()->constrained('education_fields')->comment('رشته تحصیلی');
            $table->foreignId('user_id')->nullable()->constrained('users')->comment('کاربر مرتبط');
            $table->boolean('status')->default(true)->comment('وضعیت فعال/غیرفعال');
            // $table->foreignId('structure_id')->nullable()->constrained('organizational_structures')->comment('ساختار سازمانی');
            $table->enum('job_field', ['warehouse_manager', 'warehouse_keeper', 'warehouse_operator', 'warehouse_supervisor'])->comment('رشته شغلی');
            $table->string('bale_chat_id', 30)->nullable()->comment('شناسه چت بله');
            $table->boolean('prefers_sms')->default(false)->comment('ترجیح پیامک');
            $table->boolean('prefers_bale')->default(false)->comment('ترجیح بله');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};
