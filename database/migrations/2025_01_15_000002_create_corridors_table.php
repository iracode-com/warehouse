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
        Schema::create('corridors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('کد راهرو (شناسه یکتا)');
            $table->string('name')->comment('نام راهرو');
            $table->unsignedBigInteger('zone_id')->comment('شناسه منطقه');
            $table->integer('rack_count')->default(0)->comment('تعداد قفسه‌ها در راهرو');
            $table->enum('access_type', [
                'pedestrian', // پیاده
                'forklift', // لیفتراک
                'crane', // جرثقیل
                'mixed' // مختلط
            ])->comment('نوع دسترسی');
            $table->decimal('width', 8, 2)->comment('عرض راهرو (متر)');
            $table->decimal('length', 8, 2)->comment('طول راهرو (متر)');
            $table->decimal('height', 8, 2)->nullable()->comment('ارتفاع راهرو (متر)');
            $table->text('description')->nullable()->comment('توضیحات راهرو');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->timestamps();

            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corridors');
    }
};
