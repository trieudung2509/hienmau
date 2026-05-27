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
        Schema::create('NguoiHienMau', function (Blueprint $table) {
            $table->id('Id');
            $table->uuid('PublicId')->unique();
            $table->unsignedBigInteger('NguoiDungId');
            $table->string('CCCD', 20);
            $table->date('NgaySinh');
            $table->tinyInteger('GioiTinh');
            $table->string('NhomMau', 5);
            $table->string('DiaChi', 500);
            $table->decimal('CanNang', 5, 2);
            $table->string('NgheNghiep', 255);
            $table->dateTime('LanHienGanNhat')->nullable();
            $table->integer('SoLanDaHien');
            $table->tinyInteger('TrangThaiSucKhoe');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraint
            $table->foreign('NguoiDungId')->references('Id')->on('NguoiDung')->onDelete('cascade');

            // Index optimizations
            $table->index('NguoiDungId');
            $table->index('PublicId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('NguoiHienMau');
    }
};
