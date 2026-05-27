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
        Schema::create('NguoiDung', function (Blueprint $table) {
            $table->id('Id');
            $table->string('HoTen', 255);
            $table->string('Email', 255)->unique();
            $table->string('SoDienThoai', 20);
            $table->string('MatKhauHash', 500);
            $table->unsignedBigInteger('VaiTroId');
            $table->tinyInteger('TrangThai');
            $table->date('NgaySinh')->nullable();
            $table->tinyInteger('GioiTinh')->nullable(); // 1: Nam, 2: Nữ, 3: Khác
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraint
            $table->foreign('VaiTroId')->references('Id')->on('VaiTro')->onDelete('restrict');

            // Index optimization for Foreign Key
            $table->index('VaiTroId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('NguoiDung');
    }
};
