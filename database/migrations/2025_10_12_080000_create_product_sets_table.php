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
        Schema::create('product_sets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_profile_id')->comment('شناسه کالای ست/سبد');
            $table->string('name')->comment('نام ست/سبد');
            $table->string('code')->unique()->comment('کد ست/سبد');
            $table->text('description')->nullable()->comment('توضیحات');
            $table->enum('set_type', ['set', 'basket'])->default('set')->comment('نوع: ست یا سبد');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال');
            $table->integer('total_quantity')->default(1)->comment('تعداد کل در هر ست');
            $table->string('unit', 50)->nullable()->comment('واحد');
            $table->timestamps();

            $table->foreign('product_profile_id')->references('id')->on('product_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sets');
    }
};
