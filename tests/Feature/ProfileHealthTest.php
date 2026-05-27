<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProfileHealthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
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
     * Test profile edit page loads successfully.
     */
    public function test_profile_page_loads_successfully(): void
    {
        // 1. As Admin
        $response = $this->actingAsAdmin()->get(route('profile.edit', ['role' => 'admin']));
        $response->assertStatus(200);
        $response->assertSee('Thông tin cá nhân');
        $response->assertSee('Hồ sơ sức khỏe');

        // 2. As Nhan-vien
        $response = $this->get(route('profile.edit', ['role' => 'nhan-vien']));
        $response->assertStatus(200);
        $response->assertSee('Thông tin cá nhân');
        $response->assertSee('Hồ sơ sức khỏe');

        // 3. As Donor
        $response = $this->get(route('profile.edit', ['role' => 'donor']));
        $response->assertStatus(200);
        $response->assertSee('Thông tin cá nhân');
        $response->assertSee('Hồ sơ sức khỏe');
    }

    /**
     * Test updating personal info and health record together successfully.
     */
    public function test_can_update_profile_and_health_records(): void
    {
        // Let's get the default admin user (Id = 1)
        $admin = DB::table('NguoiDung')->where('Id', 1)->first();
        $this->assertNotNull($admin);

        $response = $this->actingAsAdmin()->post(route('profile.update'), [
            'HoTen' => 'System Admin New Name',
            'Email' => 'admin_new@system.com',
            'SoDienThoai' => '0988776655',
            'Nhommau' => 'AB (Rh-)',
            'HuyetAp' => '130/85',
            'NhipTim' => 80,
            'NhietDo' => 36.8,
            'CanNang' => 72.5,
            'Hemoglobin' => 15.2,
            'NguoiKham' => 'PGS. TS. Nguyễn Hữu Dũng',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('profile.edit', ['role' => 'admin']));
        $response->assertSessionHas('success', 'Cập nhật thông tin cá nhân và hồ sơ sức khỏe thành công.');

        // Assert NguoiDung database updated
        $this->assertDatabaseHas('NguoiDung', [
            'Id' => 1,
            'HoTen' => 'System Admin New Name',
            'Email' => 'admin_new@system.com',
            'SoDienThoai' => '0988776655',
        ]);

        // Assert NguoiHienMau record was created or matches
        $nhm = DB::table('NguoiHienMau')->where('NguoiDungId', 1)->first();
        $this->assertNotNull($nhm);

        // Assert DangKyHienMau record was created
        $dangKy = DB::table('DangKyHienMau')->where('NguoiHienMauId', $nhm->Id)->first();
        $this->assertNotNull($dangKy);

        // Assert HoSySucKhoe record matches physical metrics
        $this->assertDatabaseHas('HoSoSucKhoe', [
            'DangKyId' => $dangKy->Id,
            'Nhommau' => 'AB (Rh-)',
            'HuyetAp' => '130/85',
            'NhipTim' => 80,
            'NhietDo' => 36.8,
            'CanNang' => 72.5,
            'Hemoglobin' => 15.2,
            'NguoiKham' => 'PGS. TS. Nguyễn Hữu Dũng',
        ]);
    }

    /**
     * Test health record validation rules work.
     */
    public function test_profile_update_validation_rules(): void
    {
        // 1. Invalid phone number format
        $response = $this->actingAsAdmin()->post(route('profile.update'), [
            'HoTen' => 'Admin Name',
            'Email' => 'admin@system.com',
            'SoDienThoai' => '12345', // invalid
            'NhipTim' => 25, // too low
            'NhietDo' => 50, // too high
            'role' => 'admin',
        ]);

        $response->assertSessionHasErrors(['SoDienThoai']);
    }

    /**
     * Test registration history page requires authentication.
     */
    public function test_registration_history_page_requires_auth(): void
    {
        $response = $this->get(route('frontend.lich-su-dang-ky'));
        $response->assertRedirect(route('dang-nhap'));
    }

    /**
     * Test logged in donor can view their registration history.
     */
    public function test_donor_can_view_registration_history_page(): void
    {
        $donorUser = DB::table('NguoiDung')->where('Email', 'nguyenthif@gmail.com')->first();
        $this->assertNotNull($donorUser);

        // Seed NguoiHienMau record if missing
        $nhmId = DB::table('NguoiHienMau')->where('NguoiDungId', $donorUser->Id)->value('Id');
        if (!$nhmId) {
            $nhmId = DB::table('NguoiHienMau')->insertGetId([
                'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
                'NguoiDungId' => $donorUser->Id,
                'CCCD' => '001097654321',
                'NgaySinh' => '1997-12-05',
                'GioiTinh' => 2,
                'NhomMau' => 'O',
                'DiaChi' => 'Hà Nội',
                'CanNang' => 55,
                'NgheNghiep' => 'Tự do',
                'SoLanDaHien' => 0,
                'TrangThaiSucKhoe' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $response = $this->withSession([
            'admin_user' => [
                'id' => $donorUser->Id,
                'name' => $donorUser->HoTen,
                'role' => 'Người tham gia',
            ]
        ])->get(route('frontend.lich-su-dang-ky'));

        $response->assertStatus(200);
        $response->assertSee('Lịch sử đăng ký hiến máu');
        $response->assertSee('Đã đăng ký');
    }

    /**
     * Test logged in donor can cancel/unsubscribe from a pending registration.
     */
    public function test_donor_can_cancel_registration_successfully(): void
    {
        $donorUser = DB::table('NguoiDung')->where('Email', 'nguyenthif@gmail.com')->first();
        $this->assertNotNull($donorUser);

        // Seed NguoiHienMau record if missing
        $nhm = DB::table('NguoiHienMau')->where('NguoiDungId', $donorUser->Id)->first();
        if (!$nhm) {
            $nhmId = DB::table('NguoiHienMau')->insertGetId([
                'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
                'NguoiDungId' => $donorUser->Id,
                'CCCD' => '001097654321',
                'NgaySinh' => '1997-12-05',
                'GioiTinh' => 2,
                'NhomMau' => 'O',
                'DiaChi' => 'Hà Nội',
                'CanNang' => 55,
                'NgheNghiep' => 'Tự do',
                'SoLanDaHien' => 0,
                'TrangThaiSucKhoe' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $nhm = DB::table('NguoiHienMau')->where('Id', $nhmId)->first();
        }

        $campaignId = DB::table('ChuongTrinhHienMau')->value('Id');
        $this->assertNotNull($campaignId);

        $regId = DB::table('DangKyHienMau')->insertGetId([
            'NguoiHienMauId' => $nhm->Id,
            'ChuongTrinhId' => $campaignId,
            'ThoiGianDangKy' => now(),
            'TrangThai' => 1, // Pending/Registered
            'GhiChu' => 'Hồ sơ tự cập nhật',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession([
            'admin_user' => [
                'id' => $donorUser->Id,
                'name' => $donorUser->HoTen,
                'role' => 'Người tham gia',
            ]
        ])->post(route('frontend.lich-su-dang-ky.cancel', $regId));

        $response->assertRedirect(route('frontend.lich-su-dang-ky'));
        $response->assertSessionHas('success', 'Hủy đăng ký chương trình hiến máu thành công.');

        $this->assertDatabaseHas('DangKyHienMau', [
            'Id' => $regId,
            'TrangThai' => 0,
        ]);
    }
}
