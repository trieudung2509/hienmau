<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class NguoiDungDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách VaiTrò để ánh xạ
        $roles = DB::table('VaiTro')->pluck('Id', 'TenVaiTro')->toArray();

        // 8 người dùng đặc thù từ ảnh giao diện
        $users = [
            [
                'HoTen' => 'Nguyễn Văn An',
                'Email' => 'nguyenvanan@gmail.com',
                'SoDienThoai' => '0901234567',
                'MatKhauHash' => Hash::make('An@123456'),
                'VaiTroId' => $roles['Quản trị viên'],
                'TrangThai' => 1, // Hoạt động
                'NgaySinh' => '1985-05-12',
                'GioiTinh' => 1, // Nam
                'created_at' => Carbon::create(2024, 3, 12, 10, 30, 0),
                'updated_at' => Carbon::create(2024, 3, 12, 10, 30, 0),
            ],
            [
                'HoTen' => 'Trần Thị Bình',
                'Email' => 'tranthibinh@gmail.com',
                'SoDienThoai' => '0912345678',
                'MatKhauHash' => Hash::make('Binh@123456'),
                'VaiTroId' => $roles['Nhân viên'],
                'TrangThai' => 1,
                'NgaySinh' => '1992-08-15',
                'GioiTinh' => 2, // Nữ
                'created_at' => Carbon::create(2024, 3, 15, 14, 20, 0),
                'updated_at' => Carbon::create(2024, 3, 15, 14, 20, 0),
            ],
            [
                'HoTen' => 'Lê Minh Cường',
                'Email' => 'leminhcuong@gmail.com',
                'SoDienThoai' => '0923456789',
                'MatKhauHash' => Hash::make('Cuong@123456'),
                'VaiTroId' => $roles['Nhân viên'],
                'TrangThai' => 1,
                'NgaySinh' => '1990-11-20',
                'GioiTinh' => 1, // Nam
                'created_at' => Carbon::create(2024, 3, 16, 9, 15, 0),
                'updated_at' => Carbon::create(2024, 3, 16, 9, 15, 0),
            ],
            [
                'HoTen' => 'Trường ĐH Y Hà Nội',
                'Email' => 'contact@yhn.edu.vn',
                'SoDienThoai' => '02438523798',
                'MatKhauHash' => Hash::make('Dhyhn@123456'),
                'VaiTroId' => $roles['Đơn vị tổ chức'],
                'TrangThai' => 1,
                'NgaySinh' => '1960-01-01',
                'GioiTinh' => 3, // Khác
                'created_at' => Carbon::create(2024, 3, 18, 11, 45, 0),
                'updated_at' => Carbon::create(2024, 3, 18, 11, 45, 0),
            ],
            [
                'HoTen' => 'Công ty TNHH ABC',
                'Email' => 'abc.company@gmail.com',
                'SoDienThoai' => '02812345678',
                'MatKhauHash' => Hash::make('Abc@123456'),
                'VaiTroId' => $roles['Đơn vị tổ chức'],
                'TrangThai' => 1,
                'NgaySinh' => '2000-01-01',
                'GioiTinh' => 3, // Khác
                'created_at' => Carbon::create(2024, 3, 20, 16, 30, 0),
                'updated_at' => Carbon::create(2024, 3, 20, 16, 30, 0),
            ],
            [
                'HoTen' => 'Phạm Thị Dung',
                'Email' => 'phamthidung@gmail.com',
                'SoDienThoai' => '0934567890',
                'MatKhauHash' => Hash::make('Dung@123456'),
                'VaiTroId' => $roles['Người tham gia'],
                'TrangThai' => 1,
                'NgaySinh' => '1995-04-18',
                'GioiTinh' => 2, // Nữ
                'created_at' => Carbon::create(2024, 3, 22, 8, 20, 0),
                'updated_at' => Carbon::create(2024, 3, 22, 8, 20, 0),
            ],
            [
                'HoTen' => 'Hoàng Văn E',
                'Email' => 'hoangvane@gmail.com',
                'SoDienThoai' => '0945678901',
                'MatKhauHash' => Hash::make('E@123456'),
                'VaiTroId' => $roles['Người tham gia'],
                'TrangThai' => 2, // Đã đóng băng
                'NgaySinh' => '1988-09-25',
                'GioiTinh' => 1, // Nam
                'created_at' => Carbon::create(2024, 3, 25, 13, 10, 0),
                'updated_at' => Carbon::create(2024, 3, 25, 13, 10, 0),
            ],
            [
                'HoTen' => 'Nguyễn Thị F',
                'Email' => 'nguyenthif@gmail.com',
                'SoDienThoai' => '0956789012',
                'MatKhauHash' => Hash::make('F@123456'),
                'VaiTroId' => $roles['Người tham gia'],
                'TrangThai' => 1,
                'NgaySinh' => '1997-12-05',
                'GioiTinh' => 2, // Nữ
                'created_at' => Carbon::create(2024, 3, 28, 15, 05, 0),
                'updated_at' => Carbon::create(2024, 3, 28, 15, 05, 0),
            ],
        ];

        foreach ($users as $u) {
            DB::table('NguoiDung')->updateOrInsert(
                ['Email' => $u['Email']],
                $u
            );
        }
    }
}
