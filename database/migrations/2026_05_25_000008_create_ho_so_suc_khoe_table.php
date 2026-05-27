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
        Schema::create('HoSoSucKhoe', function (Blueprint $table) {
            $table->id('Id');
            $table->unsignedBigInteger('DangKyId');
            $table->string('HuyetAp', 20);
            $table->integer('NhipTim');
            $table->decimal('NhietDo', 4, 1);
            $table->decimal('CanNang', 5, 2);
            $table->decimal('Hemoglobin', 5, 2);
            $table->tinyInteger('KetQua');
            $table->string('LyDoTuChoi', 500);
            $table->string('Nhommau', 100);
            $table->string('NguoiKham', 500);
            $table->dateTime('ThoiGianKham');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraint
            $table->foreign('DangKyId')->references('Id')->on('DangKyHienMau')->onDelete('cascade');

            // Index optimization
            $table->index('DangKyId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HoSoSucKhoe');
    }
};
