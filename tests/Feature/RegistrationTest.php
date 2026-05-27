<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RegistrationTest extends TestCase
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

    /**
     * Test registration page loads successfully.
     */
    public function test_registration_page_loads_successfully(): void
    {
        $response = $this->get(route('dang-ky'));
        $response->assertStatus(200);
        $response->assertSee('Đăng ký tài khoản');
        $response->assertSee('Người hiến máu');
        $response->assertSee('Đơn vị tổ chức');
    }

    /**
     * Test successful donor registration.
     */
    public function test_can_register_as_donor_successfully(): void
    {
        $response = $this->post(route('dang-ky.submit'), [
            'role_type' => 'donor',
            'HoTen' => 'Nguyễn Văn Register',
            'SoDienThoai' => '0987112233',
            'Email' => 'donor_register@gmail.com',
            'NgaySinh' => '1998-05-15',
            'GioiTinh' => 'Nam',
            'MatKhau' => 'Password123',
            'MatKhau_confirmation' => 'Password123',
        ]);

        $response->assertRedirect(route('dang-nhap'));
        $response->assertSessionHas('success', 'Đăng ký tài khoản người hiến máu thành công! Vui lòng đăng nhập.');

        // Assert user created in NguoiDung
        $user = DB::table('NguoiDung')->where('Email', 'donor_register@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Nguyễn Văn Register', $user->HoTen);
        $this->assertEquals(1, $user->TrangThai); // Active

        // Assert profile created in NguoiHienMau
        $donor = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
        $this->assertNotNull($donor);
        $this->assertEquals('1998-05-15', $donor->NgaySinh);
        $this->assertEquals(1, $donor->GioiTinh); // Nam -> 1
    }

    /**
     * Test successful organization registration.
     */
    public function test_can_register_as_organization_successfully(): void
    {
        $response = $this->post(route('dang-ky.submit'), [
            'role_type' => 'organization',
            'TenDonVi' => 'Đoàn Thanh Niên Phường E',
            'Email' => 'doanthanhnien_e@gmail.com',
            'SoDienThoai' => '0243112233',
            'NguoiDaiDien' => 'Lê Đại Diện',
            'Loai' => 'Doanh nghiệp',
            'DiaChi' => '97 Trần Cung, Cầu Giấy, Hà Nội',
            'MoTa' => 'Đoàn phường năng động thiện nguyện.',
            'MatKhau' => 'Password123',
            'MatKhau_confirmation' => 'Password123',
        ]);

        $response->assertRedirect(route('dang-nhap'));
        $response->assertSessionHas('success', 'Đăng ký tài khoản tổ chức thành công! Vui lòng chờ admin xét duyệt để kích hoạt tài khoản.');

        // Assert user created in NguoiDung with TrangThai = 2 (Pending approval / Frozen)
        $user = DB::table('NguoiDung')->where('Email', 'doanthanhnien_e@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Đoàn Thanh Niên Phường E', $user->HoTen);
        $this->assertEquals(2, $user->TrangThai); // Frozen

        // Assert profile created in DonViToChuc with TrangThai = 2
        $org = DB::table('DonViToChuc')->where('OwnerUserId', $user->Id)->first();
        $this->assertNotNull($org);
        $this->assertEquals('Đoàn Thanh Niên Phường E', $org->TenDonVi);
        $this->assertEquals('Doanh nghiệp', $org->Loai);
        $this->assertEquals(2, $org->TrangThai); // Pending / Frozen
    }

    /**
     * Test registration validation rules work.
     */
    public function test_registration_validation_rules(): void
    {
        // 1. Password confirmation mismatch
        $response = $this->post(route('dang-ky.submit'), [
            'role_type' => 'donor',
            'HoTen' => 'Name',
            'SoDienThoai' => '0912123123',
            'Email' => 'email_error@gmail.com',
            'NgaySinh' => '1995-10-10',
            'GioiTinh' => 'Nữ',
            'MatKhau' => 'Password123',
            'MatKhau_confirmation' => 'Different123',
        ]);

        $response->assertSessionHasErrors(['MatKhau']);

        // 2. Duplicate email check
        $response = $this->post(route('dang-ky.submit'), [
            'role_type' => 'donor',
            'HoTen' => 'Name',
            'SoDienThoai' => '0912123123',
            'Email' => 'admin@system.com', // Duplicate admin email
            'NgaySinh' => '1995-10-10',
            'GioiTinh' => 'Nữ',
            'MatKhau' => 'Password123',
            'MatKhau_confirmation' => 'Password123',
        ]);

        $response->assertSessionHasErrors(['Email']);
    }

    /**
     * Test campaign registration form page loads successfully.
     */
    public function test_registration_form_page_loads_successfully(): void
    {
        $response = $this->get(route('frontend.chuong-trinh.register'));
        $response->assertStatus(200);
        $response->assertSee('Đăng ký');
        $response->assertSee('Hiến máu nhân đạo');
    }

    /**
     * Test logged in donor can register for a program instantly.
     */
    public function test_authenticated_donor_can_register_for_campaign_instantly(): void
    {
        $donorUser = DB::table('NguoiDung')->where('Email', 'nguyenthif@gmail.com')->first();
        $this->assertNotNull($donorUser);

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

        $campaign = DB::table('ChuongTrinhHienMau')->whereIn('TrangThai', [2, 3])->first();
        $this->assertNotNull($campaign);

        $response = $this->withSession([
            'admin_user' => [
                'id' => $donorUser->Id,
                'name' => $donorUser->HoTen,
                'role' => 'Người tham gia',
            ]
        ])->post(route('frontend.chuong-trinh.register.submit'), [
            'ChuongTrinhId' => $campaign->Id,
            'GhiChu' => 'Đăng ký nhanh',
        ]);

        $response->assertRedirect(route('frontend.lich-su-dang-ky'));
        $response->assertSessionHas('success', 'Đăng ký tham gia chương trình hiến máu thành công!');

        $this->assertDatabaseHas('DangKyHienMau', [
            'NguoiHienMauId' => $nhm->Id,
            'ChuongTrinhId' => $campaign->Id,
            'GhiChu' => 'Đăng ký nhanh',
            'TrangThai' => 1,
        ]);
    }

    /**
     * Test logged in donor duplicate registration is prevented.
     */
    public function test_authenticated_donor_duplicate_registration_prevented(): void
    {
        $donorUser = DB::table('NguoiDung')->where('Email', 'nguyenthif@gmail.com')->first();
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

        $campaign = DB::table('ChuongTrinhHienMau')->whereIn('TrangThai', [2, 3])->first();
        $this->assertNotNull($campaign);

        DB::table('DangKyHienMau')->insert([
            'NguoiHienMauId' => $nhm->Id,
            'ChuongTrinhId' => $campaign->Id,
            'ThoiGianDangKy' => now(),
            'TrangThai' => 1,
            'GhiChu' => 'Đăng ký lần 1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withSession([
            'admin_user' => [
                'id' => $donorUser->Id,
                'name' => $donorUser->HoTen,
                'role' => 'Người tham gia',
            ]
        ])->post(route('frontend.chuong-trinh.register.submit'), [
            'ChuongTrinhId' => $campaign->Id,
        ]);

        $response->assertRedirect(route('frontend.lich-su-dang-ky'));
        $response->assertSessionHas('success', 'Bạn đã đăng ký chương trình này từ trước.');
    }

    /**
     * Test a guest user can register an account and book a campaign in one step.
     */
    public function test_guest_can_register_account_and_campaign_in_one_step(): void
    {
        $campaign = DB::table('ChuongTrinhHienMau')->whereIn('TrangThai', [2, 3])->first();
        $this->assertNotNull($campaign);

        $response = $this->post(route('frontend.chuong-trinh.register.submit'), [
            'ChuongTrinhId' => $campaign->Id,
            'HoTen' => 'Khách Hiến Máu',
            'SoDienThoai' => '0979887766',
            'Email' => 'guest_donor@gmail.com',
            'CCCD' => '001099887766',
            'NgaySinh' => '1996-06-16',
            'GioiTinh' => 'Nữ',
            'MatKhau' => 'Password123',
            'MatKhau_confirmation' => 'Password123',
            'GhiChu' => 'Khách tự đăng ký',
        ]);

        $response->assertRedirect(route('frontend.lich-su-dang-ky'));
        $response->assertSessionHas('admin_user');

        $user = DB::table('NguoiDung')->where('Email', 'guest_donor@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Khách Hiến Máu', $user->HoTen);

        $nhm = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
        $this->assertNotNull($nhm);
        $this->assertEquals('001099887766', $nhm->CCCD);

        $this->assertDatabaseHas('DangKyHienMau', [
            'NguoiHienMauId' => $nhm->Id,
            'ChuongTrinhId' => $campaign->Id,
            'GhiChu' => 'Khách tự đăng ký',
            'TrangThai' => 1,
        ]);
    }
}
