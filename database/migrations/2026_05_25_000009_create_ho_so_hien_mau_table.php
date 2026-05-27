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
        Schema::create('HoSoHienMau', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('NguoiHienMauId');
            $table->unsignedBigInteger('ChuongTrinhId');
            $table->unsignedBigInteger('HoSoSucKhoeId');
            $table->integer('LuongMau');
            $table->dateTime('ThoiGianHien');
            $table->tinyInteger('KetQuaSauHien');
            $table->string('GhiChu', 500);
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraints
            $table->foreign('NguoiHienMauId')->references('Id')->on('NguoiHienMau')->onDelete('cascade');
            $table->foreign('ChuongTrinhId')->references('Id')->on('ChuongTrinhHienMau')->onDelete('cascade');
            $table->foreign('HoSoSucKhoeId')->references('Id')->on('HoSoSucKhoe')->onDelete('cascade');

            // Index optimizations
            $table->index('NguoiHienMauId');
            $table->index('ChuongTrinhId');
            $table->index('HoSoSucKhoeId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HoSoHienMau');
    }
};
