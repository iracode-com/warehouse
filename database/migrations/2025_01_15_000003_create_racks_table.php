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
        Schema::create('racks', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('کد قفسه (شناسه یکتا)');
            $table->string('name')->comment('نام قفسه');
            $table->unsignedBigInteger('corridor_id')->comment('شناسه راهرو');
            $table->enum('rack_type', [
                'fixed', // ثابت
                'movable', // متحرک
                'pallet_rack', // پالت‌دار
                'shelving', // قفسه‌بندی
                'cantilever', // کنسول
                'drive_in', // رانشی
                'push_back' // فشاری
            ])->comment('نوع قفسه');
            $table->integer('level_count')->comment('تعداد طبقات قفسه');
            $table->decimal('capacity_per_level', 10, 2)->comment('ظرفیت هر طبقه (کیلوگرم)');
            $table->decimal('height', 8, 2)->comment('ارتفاع قفسه (متر)');
            $table->decimal('width', 8, 2)->comment('عرض قفسه (متر)');
            $table->decimal('depth', 8, 2)->comment('عمق قفسه (متر)');
            $table->decimal('max_weight', 10, 2)->comment('حداکثر وزن قابل تحمل (کیلوگرم)');
            $table->text('description')->nullable()->comment('توضیحات قفسه');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال/غیرفعال');
            $table->timestamps();

            $table->foreign('corridor_id')->references('id')->on('corridors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racks');
    }
};
