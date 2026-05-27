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
        Schema::create('ChuongTrinhHienMau', function (Blueprint $table) {
            $table->id('Id');
            $table->uuid('PublicId')->unique();
            $table->string('TenChuongTrinh', 500);
            $table->text('MoTa');
            $table->string('Banner', 500);
            $table->unsignedBigInteger('DonViToChucId');
            $table->string('DiaChi', 500);
            $table->string('BanDo', 1000)->nullable();
            $table->dateTime('ThoiGianBatDau');
            $table->dateTime('ThoiGianKetThuc');
            $table->dateTime('ThoiGianMoDangKy');
            $table->boolean('DangDienRa');
            $table->integer('SoLuongDuKien');
            $table->tinyInteger('TrangThai');
            $table->unsignedBigInteger('NguoiTaoId');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraints
            $table->foreign('DonViToChucId')->references('Id')->on('DonViToChuc')->onDelete('restrict');
            $table->foreign('NguoiTaoId')->references('Id')->on('NguoiDung')->onDelete('restrict');

            // Index optimizations
            $table->index('DonViToChucId');
            $table->index('NguoiTaoId');
            $table->index('PublicId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ChuongTrinhHienMau');
    }
};
