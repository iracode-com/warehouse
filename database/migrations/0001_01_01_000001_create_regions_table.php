<?php

use App\Enums\RegionType;
use App\Models\Location\Region;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regions', function (Blueprint $table) {

            $table->comment('جدول نواحی (تقسیمات جغرافیایی)');

            $table->id();
            $table->foreignIdFor(Region::class, 'parent_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate()
                ->comment('والد ناحیه');

            $table->string('type')->comment('نوع منطقه');

            $table->string('name', 100)->comment('نام ناحیه');
            $table->string('code')->nullable()->comment('کد اختصاصی ناحیه');
            $table->integer('national_hub_region_id')->nullable()->comment('قطب کشور');
            $table->text('description')->nullable()->comment('توضیحات ناحیه');
            $table->integer('ordering')->nullable();
            $table->double('lat')->nullable()->comment('عرض جغرافیایی');
            $table->double('lon')->nullable()->comment('طول جغرافیایی');
            $table->string('width')->nullable()->comment('عرض جغرافیایی');
            $table->string('length')->nullable()->comment('طول جغرافیایی');
            $table->double('height')->nullable()->comment('ارتفاع');
            $table->string('central_address')->nullable()->comment('آدرس مرکزی');
            $table->string('central_postal_code')->nullable()->comment('کد پستی مرکزی');
            $table->string('central_phone')->nullable()->comment('تلفن مرکزی');
            $table->string('central_mobile')->nullable()->comment('موبایل مرکزی');
            $table->string('central_email')->nullable()->comment('ایمیل مرکزی');
            $table->string('central_fax')->nullable()->comment('فکس مرکزی');
            $table->boolean('status')->default(true)->comment('وضعیت(فعال/غیرفعال)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regions');
    }
};
