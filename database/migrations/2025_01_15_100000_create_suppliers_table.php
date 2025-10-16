<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('کد تامین‌کننده');
            $table->string('name')->comment('نام تامین‌کننده');
            $table->string('company_name')->nullable()->comment('نام شرکت');
            $table->string('contact_person')->nullable()->comment('شخص تماس');
            $table->string('email')->nullable()->comment('ایمیل');
            $table->string('phone')->nullable()->comment('تلفن');
            $table->string('mobile')->nullable()->comment('موبایل');
            $table->text('address')->nullable()->comment('آدرس');
            $table->foreignId('province_id')->nullable()->constrained('provinces')->nullOnDelete()->comment('استان');
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete()->comment('شهر');
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete()->comment('کشور');
            $table->string('postal_code')->nullable()->comment('کد پستی');
            $table->string('website')->nullable()->comment('وب‌سایت');
            $table->text('notes')->nullable()->comment('یادداشت‌ها');
            $table->string('status')->default('active')->comment('وضعیت: active, inactive, suspended');
            $table->json('metadata')->nullable()->comment('اطلاعات اضافی');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('province_id');
            $table->index('city_id');
            $table->index('country_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
