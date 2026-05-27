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
        Schema::create('DonViToChuc', function (Blueprint $table) {
            $table->id('Id');
            $table->string('TenDonVi', 255);
            $table->string('MaDonVi', 50)->unique();
            $table->string('Loai', 100);
            $table->string('Email', 255);
            $table->string('SoDienThoai', 20);
            $table->string('DiaChi', 500);
            $table->text('MoTa');
            $table->string('NguoiDaiDien', 255);
            $table->tinyInteger('TrangThai');
            $table->string('HinhAnh', 500)->nullable();
            $table->unsignedBigInteger('OwnerUserId');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraint
            $table->foreign('OwnerUserId')->references('Id')->on('NguoiDung')->onDelete('restrict');

            // Index optimization for Foreign Key
            $table->index('OwnerUserId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('DonViToChuc');
    }
};
