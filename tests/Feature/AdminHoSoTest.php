<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminHoSoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic dependencies
        $this->seed(\Database\Seeders\VaiTroSeeder::class);
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
        $this->seed(\Database\Seeders\NguoiDungDemoSeeder::class);
        $this->seed(\Database\Seeders\ChuongTrinhSeeder::class);
    }

    private function actingAsAdmin()
    {
        return $this->withSession([
            'admin_user' => [
                'id' => 1,
                'name' => 'System Admin',
                'role' => 'Quản trị viên'
            ]
        ]);
    }

    /**
     * Test admin profile dashboard page loads and works correctly with program search filtering.
     */
    public function test_admin_ho_so_index_and_program_filtering(): void
    {
        $roleId = DB::table('VaiTro')->where('TenVaiTro', 'Người tham gia')->value('Id');
        $progId1 = DB::table('ChuongTrinhHienMau')->where('TenChuongTrinh', 'Giọt hồng yêu thương 2025')->value('Id');
        $progId2 = DB::table('ChuongTrinhHienMau')->where('TenChuongTrinh', 'Hiến máu nhân đạo đợt 1')->value('Id');

        // Create user 1
        $userId1 = DB::table('NguoiDung')->insertGetId([
            'HoTen' => 'Nguyễn Anh Tuấn',
            'Email' => 'anhtuan@gmail.com',
            'SoDienThoai' => '0977112233',
            'MatKhauHash' => bcrypt('password'),
            'VaiTroId' => $roleId,
            'TrangThai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $nhmId1 = DB::table('NguoiHienMau')->insertGetId([
            'NguoiDungId' => $userId1,
            'PublicId' => (string) \Illuminate\Support\Str::uuid(),
            'CCCD' => '123456789012',
            'NgaySinh' => '1995-05-15',
            'GioiTinh' => 1,
            'NhomMau' => 'O+',
            'DiaChi' => 'Hà Nội',
            'CanNang' => 70.0,
            'NgheNghiep' => 'Lập trình viên',
            'SoLanDaHien' => 2,
            'TrangThaiSucKhoe' => 1,
        ]);

        // Create user 2
        $userId2 = DB::table('NguoiDung')->insertGetId([
            'HoTen' => 'Lê Thị Thu',
            'Email' => 'thule@gmail.com',
            'SoDienThoai' => '0966445566',
            'MatKhauHash' => bcrypt('password'),
            'VaiTroId' => $roleId,
            'TrangThai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $nhmId2 = DB::table('NguoiHienMau')->insertGetId([
            'NguoiDungId' => $userId2,
            'PublicId' => (string) \Illuminate\Support\Str::uuid(),
            'CCCD' => '987654321098',
            'NgaySinh' => '1998-10-22',
            'GioiTinh' => 2,
            'NhomMau' => 'AB-',
            'DiaChi' => 'TP HCM',
            'CanNang' => 52.5,
            'NgheNghiep' => 'Nhân viên văn phòng',
            'SoLanDaHien' => 1,
            'TrangThaiSucKhoe' => 1,
        ]);

        // Program 1: Registration active and successful donation
        $dkId1 = DB::table('DangKyHienMau')->insertGetId([
            'ChuongTrinhId' => $progId1,
            'NguoiHienMauId' => $nhmId1,
            'ThoiGianDangKy' => now(),
            'TrangThai' => 1,
            'GhiChu' => 'Không có',
        ]);

        $hsskId1 = DB::table('HoSoSucKhoe')->insertGetId([
            'DangKyId' => $dkId1,
            'HuyetAp' => '120/80',
            'NhipTim' => 75,
            'CanNang' => 70.0,
            'NhietDo' => 36.6,
            'Hemoglobin' => 14.5,
            'KetQua' => 1,
            'LyDoTuChoi' => '',
            'Nhommau' => 'O+',
            'NguoiKham' => 'Bác sĩ A',
            'ThoiGianKham' => now(),
        ]);

        DB::table('HoSoHienMau')->insert([
            'NguoiHienMauId' => $nhmId1,
            'ChuongTrinhId' => $progId1,
            'HoSoSucKhoeId' => $hsskId1,
            'LuongMau' => 350,
            'ThoiGianHien' => now(),
            'KetQuaSauHien' => 1,
            'GhiChu' => 'Hiến tốt',
        ]);

        // Program 2: Registration cancelled
        $dkId2 = DB::table('DangKyHienMau')->insertGetId([
            'ChuongTrinhId' => $progId2,
            'NguoiHienMauId' => $nhmId2,
            'ThoiGianDangKy' => now(),
            'TrangThai' => 0, // Cancelled
            'GhiChu' => 'Đã hủy',
        ]);

        // 1. Fetch index page with all registrations (both must show up)
        $response = $this->actingAsAdmin()->get(route('admin.ho-so.index'));
        $response->assertStatus(200);
        $response->assertSee('Nguyễn Anh Tuấn');
        $response->assertSee('Lê Thị Thu');
        $response->assertSee('350 ml');

        // 2. Filter by Program 1
        $response = $this->actingAsAdmin()->get(route('admin.ho-so.index', ['chuong_trinh_id' => $progId1]));
        $response->assertStatus(200);
        $response->assertSee('Nguyễn Anh Tuấn');
        $response->assertDontSee('Lê Thị Thu'); // Program 2 registrant hidden
        $response->assertViewHas('metrics', function ($metrics) {
            return $metrics['tong_ho_so'] == 1 && // 1 registration
                   $metrics['thanh_cong'] == 1 && // 1 success
                   $metrics['da_huy'] == 0;       // 0 cancelled
        });

        // 3. Filter by Program 2
        $response = $this->actingAsAdmin()->get(route('admin.ho-so.index', ['chuong_trinh_id' => $progId2]));
        $response->assertStatus(200);
        $response->assertDontSee('Nguyễn Anh Tuấn');
        $response->assertSee('Lê Thị Thu');
        $response->assertViewHas('metrics', function ($metrics) {
            return $metrics['tong_ho_so'] == 1 && // 1 registration
                   $metrics['thanh_cong'] == 0 && // 0 success
                   $metrics['da_huy'] == 1;       // 1 cancelled
        });
    }
}
