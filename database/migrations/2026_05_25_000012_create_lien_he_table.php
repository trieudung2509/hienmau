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
        Schema::create('LienHe', function (Blueprint $table) {
            $table->id('Id');
            $table->string('HoTen', 255);
            $table->string('Email', 255);
            $table->string('SoDienThoai', 20);
            $table->string('TieuDe', 255);
            $table->text('NoiDung');
            $table->tinyInteger('TrangThai')->default(0); // 0 = Chưa đọc, 1 = Đã đọc
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('LienHe');
    }
};
