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
        Schema::create('product_set_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_set_id')->comment('شناسه ست/سبد');
            $table->unsignedBigInteger('product_profile_id')->comment('شناسه کالا');
            $table->decimal('quantity', 10, 2)->default(1)->comment('تعداد');
            $table->decimal('coefficient', 10, 4)->default(1)->comment('ضریب');
            $table->string('unit', 50)->nullable()->comment('واحد');
            $table->text('notes')->nullable()->comment('یادداشت');
            $table->timestamps();

            $table->foreign('product_set_id')->references('id')->on('product_sets')->onDelete('cascade');
            $table->foreign('product_profile_id')->references('id')->on('product_profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_set_items');
    }
};
