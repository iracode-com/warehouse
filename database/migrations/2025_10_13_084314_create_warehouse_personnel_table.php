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
        Schema::create('warehouse_personnel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id')->comment('شناسه انبار');
            $table->unsignedBigInteger('personnel_id')->comment('شناسه پرسنل (انباردار)');
            $table->string('role')->default('warehouseman')->comment('نقش: warehouseman, supervisor, etc');
            $table->boolean('is_active')->default(true)->comment('وضعیت فعال');
            $table->timestamps();

            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->foreign('personnel_id')->references('id')->on('personnels')->onDelete('cascade');

            // هر پرسنل فقط یک بار در هر انبار
            $table->unique(['warehouse_id', 'personnel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_personnel');
    }
};
