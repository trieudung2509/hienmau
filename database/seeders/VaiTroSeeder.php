<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaiTroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $roles = [
            [
                'TenVaiTro' => 'Quản trị viên',
            ],
            [
                'TenVaiTro' => 'Nhân viên',
            ],
            [
                'TenVaiTro' => 'Đơn vị tổ chức',
            ],
            [
                'TenVaiTro' => 'Người tham gia',
            ],
            [
                'TenVaiTro' => 'Người dùng',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('VaiTro')->updateOrInsert(
                ['TenVaiTro' => $role['TenVaiTro']],
                $role
            );
        }
    }
}
