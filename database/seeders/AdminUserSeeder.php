<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lấy role Quản trị viên
        $adminRole = DB::table('VaiTro')
            ->where('TenVaiTro', 'Quản trị viên')
            ->first();

        if (!$adminRole) {
            throw new \Exception("Role Quản trị viên chưa tồn tại. Hãy chạy VaiTroSeeder trước.");
        }

        // Tạo admin user mặc định
        $email = 'admin@system.com';

        $existingUser = DB::table('NguoiDung')
            ->where('Email', $email)
            ->first();

        if (!$existingUser) {
            DB::table('NguoiDung')->insert([
                'HoTen' => 'System Admin',
                'Email' => $email,
                'SoDienThoai' => '0000000000',
                'MatKhauHash' => Hash::make('Admin@123456'),
                'VaiTroId' => $adminRole->Id,
                'TrangThai' => 1,
                'NgaySinh' => '1990-01-01',
                'GioiTinh' => 1, // Nam

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
