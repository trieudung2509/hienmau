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
        Schema::create('ThongBao', function (Blueprint $table) {
            $table->id('Id');
            $table->string('TieuDe', 255);
            $table->text('NoiDung');
            $table->tinyInteger('LoaiThongBao');
            $table->dateTime('NgayGui');
            $table->unsignedBigInteger('NguoiGuiId')->nullable();
            $table->unsignedBigInteger('NguoiNhanId');
            $table->boolean('DaDoc')->default(false);
            $table->dateTime('ThoiGianDoc')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraints
            $table->foreign('NguoiGuiId')->references('Id')->on('NguoiDung')->onDelete('set null');
            $table->foreign('NguoiNhanId')->references('Id')->on('NguoiDung')->onDelete('cascade');

            // Index optimizations
            $table->index('NguoiGuiId');
            $table->index('NguoiNhanId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ThongBao');
    }
};
