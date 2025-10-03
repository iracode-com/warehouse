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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->tinyInteger('branch_type')->comment('نوع شعبه(0:شهرستان،1:ستاد،2:شعبه،3:دفترنمایندگی مستقل،4:دفترنمایندگی وابسته،5:مناطق شهری)');
            $table->json('name')->comment('نام شعبه');
            $table->string('two_digit_code', 2)->nullable()->comment('کد دو رقمی شعبه');
            $table->string('date_establishment', 10)->nullable()->comment('تاریخ تاسیس');
            $table->string('phone', 11)->nullable()->comment('شماره تماس');
            $table->string('fax', 11)->nullable()->comment('شماره فکس شعبه');
            $table->string('vhf_address', 20)->nullable()->comment('کد خطاب VHF');
            $table->string('hf_address', 20)->nullable()->comment('کد خطاب HF');
            $table->string('vhf_channel', 20)->nullable()->comment('کانال VHF');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lon', 10, 7)->nullable();
            $table->integer('lat_deg')->comment('عرض جغرافیایی(DMS)');
            $table->integer('lat_min')->comment('عرض جغرافیایی(DMS)');
            $table->decimal('lat_sec', 10, 7)->comment('عرض جغرافیایی(DMS)');
            $table->integer('lon_deg')->comment('طول جغرافیایی(DMS)');
            $table->integer('lon_min')->comment('طول جغرافیایی(DMS)');
            $table->decimal('lon_sec', 10, 7)->comment('طول جغرافیایی(DMS)');
            $table->json('city_border')->nullable()->comment('مرز');
            $table->integer('height')->nullable();
            $table->string('img_header', 250)->nullable()->comment('تصویر سردرب شعبه');
            $table->string('img_building', 250)->nullable()->comment('تصویر ساختمان شعبه');
            $table->string('address', 150)->nullable()->comment('آدرس پستی(نشانی)');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->string('postal_code', 10)->nullable()->comment('کد پستی');
            $table->string('coding', 6)->nullable()->comment('کدینگ');
            $table->boolean('closed_thursday')->default(false)->comment('پنجشنبه تعطیل است؟(1:آری،0:خیر)');
            $table->date('closure_date')->nullable();
            $table->string('closure_document', 255)->nullable();
            $table->string('date_closed_thursday', 255)->nullable()->comment('تاریخ اعمال شدن تعطیلی پنجشنبه ها');
            $table->string('date_closed_thursday_end', 255)->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
