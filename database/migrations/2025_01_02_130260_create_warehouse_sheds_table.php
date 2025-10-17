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
        Schema::create('warehouse_sheds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('code')->unique();
            
            // ابعاد و متراژ
            $table->decimal('length', 10, 2)->nullable(); // طول به متر
            $table->decimal('width', 10, 2)->nullable(); // عرض به متر
            $table->decimal('height', 10, 2)->nullable(); // ارتفاع به متر
            $table->decimal('area', 10, 2)->nullable(); // مساحت به متر مربع
            $table->decimal('volume', 10, 2)->nullable(); // حجم به متر مکعب
            
            // نوع سازه
            $table->string('structure_type')->nullable(); // نوع سازه
            $table->string('roof_type')->nullable(); // نوع سقف
            $table->string('foundation_type')->nullable(); // نوع فونداسیون
            $table->string('wall_material')->nullable(); // جنس دیوارها
            
            // موقعیت جغرافیایی
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_sheds');
    }
};
