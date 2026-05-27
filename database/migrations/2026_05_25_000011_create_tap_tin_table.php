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
        Schema::create('TapTin', function (Blueprint $table) {
            $table->id('Id');
            $table->string('TenFile', 255);
            $table->string('DuongDan', 500);
            $table->string('LoaiFile', 50);
            $table->bigInteger('KichThuoc');
            $table->unsignedBigInteger('NguoiTaiLenId');
            $table->dateTime('NgayTaiLen');
            $table->timestamps();
            $table->softDeletes();

            // Foreign Key Constraint
            $table->foreign('NguoiTaiLenId')->references('Id')->on('NguoiDung')->onDelete('cascade');

            // Index optimization
            $table->index('NguoiTaiLenId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TapTin');
    }
};
