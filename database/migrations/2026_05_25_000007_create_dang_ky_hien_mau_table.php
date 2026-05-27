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
        Schema::create('DangKyHienMau', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('ChuongTrinhId');
            $table->unsignedBigInteger('NguoiHienMauId');
            $table->dateTime('ThoiGianDangKy');
            $table->tinyInteger('TrangThai');
            $table->string('GhiChu', 500);
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraints
            $table->foreign('ChuongTrinhId')->references('Id')->on('ChuongTrinhHienMau')->onDelete('cascade');
            $table->foreign('NguoiHienMauId')->references('Id')->on('NguoiHienMau')->onDelete('cascade');

            // Index optimizations
            $table->index('ChuongTrinhId');
            $table->index('NguoiHienMauId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DangKyHienMau');
    }
};
