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
        Schema::create('bases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->unsignedTinyInteger('type_operational_center')->comment('نوع مرکز عملیاتی');
            $table->tinyInteger('account_type')->nullable()->comment('نوع حساب');
            $table->string('name')->comment('نام مرکز عملیاتی');
            $table->string('coding_old', 11)->nullable()->comment('کدینگ قدیم');
            $table->string('coding', 11)->default('')->comment('کدینگ');
            $table->tinyInteger('three_digit_code_new')->nullable()->comment('کد مرکز عملیاتی جدید');
            $table->string('activity_days', 65)->default('Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday')->comment('روزهای فعالیت');
            $table->string('date_activity_days', 10)->nullable()->comment('تاریخ اعمال روزهای فعالیت');
            $table->tinyInteger('type_ownership')->nullable()->comment('نوع مالکیت');
            $table->tinyInteger('type_structure')->nullable()->comment('نوع سازه');
            $table->string('start_activity', 10)->nullable()->comment('آغاز فعالیت');
            $table->string('branch_type', 10)->nullable()->comment('نوع شعبه');
            $table->string('end_activity', 10)->nullable()->comment('پایان فعالیت');
            $table->string('memory_martyr', 50)->nullable()->comment('یادمان شهید');
            $table->tinyInteger('seasonal_type')->nullable()->comment('نوع فصلی');
            $table->unsignedBigInteger('occasional_id')->nullable()->comment('عنوان مناسبتی');
            $table->tinyInteger('three_digit_code')->nullable()->comment('کد مرکز عملیاتی');
            $table->tinyInteger('license_state')->nullable()->comment('وضعیت مجوز پایگاه امدادی');
            $table->string('phone', 11)->nullable()->comment('تلفن');
            $table->string('fixed_number', 11)->nullable()->comment('شماره ثابت');
            $table->string('mobile', 11)->nullable()->comment('شماره همراه');
            $table->string('fax', 11)->nullable()->comment('شماره فکس');
            $table->string('satellite_phone', 11)->nullable()->comment('تلفن ماهواره‌ای');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->integer('lat_deg')->default(0)->comment('عرض جغرافیایی(DMS)');
            $table->integer('lat_min')->default(0)->comment('عرض جغرافیایی(DMS)');
            $table->decimal('lat_sec', 10, 7)->default(0)->comment('عرض جغرافیایی(DMS)');
            $table->integer('lon_deg')->default(0)->comment('طول جغرافیایی(DMS)');
            $table->integer('lon_min')->default(0)->comment('طول جغرافیایی(DMS)');
            $table->decimal('lon_sec', 10, 7)->default(0)->comment('طول جغرافیایی(DMS)');
            $table->integer('height')->nullable();
            $table->json('city_border')->nullable()->comment('مرز');
            $table->string('arena', 10)->nullable()->comment('عرصه');
            $table->string('ayan', 10)->nullable()->comment('اعیان');
            $table->string('img_header', 250)->nullable()->comment('تصویر سر درب');
            $table->string('img_license', 250)->nullable()->comment('تصویر مجوز فعالیت');
            $table->string('bfile1', 15)->nullable();
            $table->string('bfile2', 15)->nullable();
            $table->string('address', 150)->nullable()->comment('آدرس');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->string('postal_code', 10)->nullable()->comment('کد پستی');
            $table->tinyInteger('place_payment')->default(0)->comment('محل پرداخت');
            $table->tinyInteger('type_personnel_emis')->default(0)->comment('نوع فرد کشیک');
            $table->double('distance_to_branch')->unsigned()->default(0)->comment('فاصله تا شعبه');
            $table->boolean('is_active')->default(true)->comment('وضعیت');
            $table->boolean('status_emis')->default(true)->comment('وضعیت سامانه EMIS');
            $table->boolean('status_equipment')->default(true)->comment('وضعیت تجهیزات');
            $table->boolean('status_dims')->default(true)->comment('وضعیت DMIS');
            $table->boolean('status_air_relief')->default(true)->comment('وضعیت امداد هوایی');
            $table->boolean('status_memberrcs')->default(true)->comment('وضعیت ساجد');
            $table->boolean('status_emdadyar')->default(true)->comment('وضعیت امدادگران');
            $table->boolean('status_webgis')->default(true)->comment('وضعیت WebGIS');
            $table->unsignedInteger('raromis_id')->nullable();
            $table->unsignedInteger('member_id')->nullable();
            $table->unsignedInteger('emdadyar_id')->nullable()->comment('کد امدادیار');
            $table->tinyInteger('update_emdadyar_id')->nullable()->comment('آیا کد امدادیار بروزرسانی شده است؟');
            $table->boolean('not_conditions')->default(false)->comment('عدم اعمال شروط سامانه EMIS');
            $table->timestamps();

            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bases');
    }
};
