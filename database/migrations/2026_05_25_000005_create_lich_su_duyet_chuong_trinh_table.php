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
        Schema::create('LichSuDuyetChuongTrinh', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('ChuongTrinhId');
            $table->unsignedBigInteger('NguoiDuyetId');
            $table->tinyInteger('TrangThai');
            $table->string('GhiChu', 500);
            $table->dateTime('ThoiGian');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraints
            $table->foreign('ChuongTrinhId')->references('Id')->on('ChuongTrinhHienMau')->onDelete('cascade');
            $table->foreign('NguoiDuyetId')->references('Id')->on('NguoiDung')->onDelete('restrict');

            // Index optimizations
            $table->index('ChuongTrinhId');
            $table->index('NguoiDuyetId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('LichSuDuyetChuongTrinh');
    }
};
