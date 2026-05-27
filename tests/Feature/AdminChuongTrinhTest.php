<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Carbon\Carbon;

class AdminChuongTrinhTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default roles, system admin, and programs seeder which creates organizations
        $this->seed(\Database\Seeders\VaiTroSeeder::class);
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
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
     * Test successful creation of a new blood donation campaign.
     */
    public function test_can_create_program_with_valid_data(): void
    {
        $org = DB::table('DonViToChuc')->first();
        $file = \Illuminate\Http\UploadedFile::fake()->image('banner.jpg');

        $response = $this->actingAsAdmin()->post(route('admin.chuong-trinh.store'), [
            'TenChuongTrinh' => 'Hiến Máu Cứu Người Hà Nội 2026',
            'MoTa' => 'Một giọt máu cho đi, một cuộc đời ở lại. Hãy chung tay cứu sống đồng bào.',
            'Banner' => $file,
            'DonViToChucId' => $org->Id,
            'DiaChi' => '1 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội',
            'ThoiGianBatDau' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
            'ThoiGianKetThuc' => Carbon::now()->addDays(5)->addHours(8)->format('Y-m-d H:i:s'),
            'ThoiGianMoDangKy' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
            'SoLuongDuKien' => 150,
            'TrangThai' => 1,
            'action_type' => 'create',
        ]);

        $response->assertRedirect(route('admin.chuong-trinh.index'));
        $response->assertSessionHas('success', 'Tạo chương trình thành công.');

        $program = DB::table('ChuongTrinhHienMau')->where('TenChuongTrinh', 'Hiến Máu Cứu Người Hà Nội 2026')->first();
        $this->assertNotNull($program);
        $this->assertStringContainsString('uploads/banners/', $program->Banner);

        $this->assertDatabaseHas('ChuongTrinhHienMau', [
            'TenChuongTrinh' => 'Hiến Máu Cứu Người Hà Nội 2026',
            'DiaChi' => '1 Trần Hưng Đạo, Hoàn Kiếm, Hà Nội',
            'DonViToChucId' => $org->Id,
            'SoLuongDuKien' => 150,
            'TrangThai' => 2,
        ]);

        $this->assertDatabaseHas('TapTin', [
            'TenFile' => 'banner.jpg',
            'DuongDan' => $program->Banner,
            'LoaiFile' => 'image/jpeg',
        ]);
    }

    /**
     * Test validations for campaign creation (capacity <= 0, past start date, date logically incorrect).
     */
    public function test_validation_fails_for_invalid_campaign_data(): void
    {
        $org = DB::table('DonViToChuc')->first();
        $file = \Illuminate\Http\UploadedFile::fake()->image('banner.jpg');

        // 1. Capacity <= 0 (e.g. 0 or negative)
        $response = $this->actingAsAdmin()->post(route('admin.chuong-trinh.store'), [
            'TenChuongTrinh' => 'Hiến Máu Lỗi',
            'MoTa' => 'Mô tả ngắn',
            'Banner' => $file,
            'DonViToChucId' => $org->Id,
            'DiaChi' => 'Địa chỉ test',
            'ThoiGianBatDau' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
            'ThoiGianKetThuc' => Carbon::now()->addDays(5)->addHours(8)->format('Y-m-d H:i:s'),
            'ThoiGianMoDangKy' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
            'SoLuongDuKien' => 0, // Invalid!
            'TrangThai' => 1,
            'action_type' => 'create',
        ]);
        $response->assertSessionHasErrors(['SoLuongDuKien']);

        // 2. Start date in the past (before today)
        $response = $this->actingAsAdmin()->post(route('admin.chuong-trinh.store'), [
            'TenChuongTrinh' => 'Hiến Máu Quá Khứ',
            'MoTa' => 'Mô tả ngắn',
            'Banner' => $file,
            'DonViToChucId' => $org->Id,
            'DiaChi' => 'Địa chỉ test',
            'ThoiGianBatDau' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'), // Past date!
            'ThoiGianKetThuc' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
            'ThoiGianMoDangKy' => Carbon::now()->subDays(5)->format('Y-m-d H:i:s'),
            'SoLuongDuKien' => 100,
            'TrangThai' => 1,
            'action_type' => 'create',
        ]);
        $response->assertSessionHasErrors(['ThoiGianBatDau']);

        // 3. End date before or equal to start date
        $response = $this->actingAsAdmin()->post(route('admin.chuong-trinh.store'), [
            'TenChuongTrinh' => 'Lỗi Ngày Kết Thúc',
            'MoTa' => 'Mô tả',
            'Banner' => $file,
            'DonViToChucId' => $org->Id,
            'DiaChi' => 'Địa chỉ',
            'ThoiGianBatDau' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
            'ThoiGianKetThuc' => Carbon::now()->addDays(4)->format('Y-m-d H:i:s'), // Before start!
            'ThoiGianMoDangKy' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
            'SoLuongDuKien' => 100,
            'TrangThai' => 1,
            'action_type' => 'create',
        ]);
        $response->assertSessionHasErrors(['ThoiGianKetThuc']);

        // 4. Registration date after start date
        $response = $this->actingAsAdmin()->post(route('admin.chuong-trinh.store'), [
            'TenChuongTrinh' => 'Lỗi Ngày Mở Đăng Ký',
            'MoTa' => 'Mô tả',
            'Banner' => $file,
            'DonViToChucId' => $org->Id,
            'DiaChi' => 'Địa chỉ',
            'ThoiGianBatDau' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
            'ThoiGianKetThuc' => Carbon::now()->addDays(6)->format('Y-m-d H:i:s'),
            'ThoiGianMoDangKy' => Carbon::now()->addDays(5)->addHour()->format('Y-m-d H:i:s'), // After start!
            'SoLuongDuKien' => 100,
            'TrangThai' => 1,
            'action_type' => 'create',
        ]);
        $response->assertSessionHasErrors(['ThoiGianMoDangKy']);
    }

    /**
     * Test successful update of a program.
     */
    public function test_can_update_program(): void
    {
        $prog = DB::table('ChuongTrinhHienMau')->first();
        $org = DB::table('DonViToChuc')->first();
        $file = \Illuminate\Http\UploadedFile::fake()->image('updated_banner.png');

        $response = $this->actingAsAdmin()->post(route('admin.chuong-trinh.update', $prog->Id), [
            'TenChuongTrinh' => 'Tên Mới Được Cập Nhật',
            'MoTa' => 'Mô tả mới tinh của chương trình.',
            'Banner' => $file,
            'DonViToChucId' => $org->Id,
            'DiaChi' => 'Địa điểm mới',
            'ThoiGianBatDau' => Carbon::now()->addDays(10)->format('Y-m-d H:i:s'),
            'ThoiGianKetThuc' => Carbon::now()->addDays(10)->addHours(6)->format('Y-m-d H:i:s'),
            'ThoiGianMoDangKy' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
            'SoLuongDuKien' => 300,
            'TrangThai' => 2,
            'action_type' => 'edit',
            'edit_id' => $prog->Id,
        ]);

        $response->assertRedirect(route('admin.chuong-trinh.index'));
        $response->assertSessionHas('success', 'Cập nhật chương trình thành công.');

        $updatedProg = DB::table('ChuongTrinhHienMau')->where('Id', $prog->Id)->first();
        $this->assertStringContainsString('uploads/banners/', $updatedProg->Banner);

        $this->assertDatabaseHas('ChuongTrinhHienMau', [
            'Id' => $prog->Id,
            'TenChuongTrinh' => 'Tên Mới Được Cập Nhật',
            'DiaChi' => 'Địa điểm mới',
            'SoLuongDuKien' => 300,
            'TrangThai' => 2,
        ]);

        $this->assertDatabaseHas('TapTin', [
            'TenFile' => 'updated_banner.png',
            'DuongDan' => $updatedProg->Banner,
            'LoaiFile' => 'image/png',
        ]);
    }

    /**
     * Test successful soft deletion of a campaign.
     */
    public function test_can_soft_delete_program(): void
    {
        $prog = DB::table('ChuongTrinhHienMau')->first();

        $response = $this->actingAsAdmin()->post(route('admin.chuong-trinh.destroy', $prog->Id));
        $response->assertRedirect(route('admin.chuong-trinh.index'));
        $response->assertSessionHas('success', 'Xóa chương trình thành công.');

        // Assert record is soft deleted (deleted_at is NOT null)
        $deletedRecord = DB::table('ChuongTrinhHienMau')->where('Id', $prog->Id)->first();
        $this->assertNotNull($deletedRecord->deleted_at);

        // Fetch index and assert it doesn't show in the listings
        $response = $this->actingAsAdmin()->get(route('admin.chuong-trinh.index'));
        $response->assertStatus(200);
        $response->assertDontSee($prog->TenChuongTrinh);
    }

    /**
     * Test successful approval of a campaign.
     */
    public function test_can_approve_campaign(): void
    {
        $prog = DB::table('ChuongTrinhHienMau')->where('TrangThai', 1)->first();
        $this->assertNotNull($prog);

        $response = $this->actingAsAdmin()->post(route('admin.chuong-trinh.approve', $prog->Id));
        $response->assertSessionHas('success');

        $updatedProg = DB::table('ChuongTrinhHienMau')->where('Id', $prog->Id)->first();
        $this->assertEquals(2, $updatedProg->TrangThai);
    }

    /**
     * Test paginating list of campaigns correctly.
     */
    public function test_can_paginate_campaigns(): void
    {
        $org = DB::table('DonViToChuc')->first();
        $admin = DB::table('NguoiDung')->where('Email', 'admin@system.com')->first();

        // Already 5 seeded programs. Let's add 12 more programs (total 17)
        for ($i = 1; $i <= 12; $i++) {
            DB::table('ChuongTrinhHienMau')->insert([
                'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
                'TenChuongTrinh' => "Chiến Dịch Hiến Máu $i",
                'MoTa' => "Mô tả chiến dịch $i",
                'Banner' => 'linear-gradient(135deg, #f43f5e 0%, #e11d48 100%)',
                'DonViToChucId' => $org->Id,
                'DiaChi' => "Địa chỉ $i",
                'ThoiGianBatDau' => Carbon::now()->addDays($i + 10)->format('Y-m-d H:i:s'),
                'ThoiGianKetThuc' => Carbon::now()->addDays($i + 10)->addHours(8)->format('Y-m-d H:i:s'),
                'ThoiGianMoDangKy' => Carbon::now()->addDays($i)->format('Y-m-d H:i:s'),
                'DangDienRa' => 0,
                'SoLuongDuKien' => 100 + $i,
                'TrangThai' => 2,
                'NguoiTaoId' => $admin->Id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Fetch page 1 with 10 per page
        $response = $this->actingAsAdmin()->get(route('admin.chuong-trinh.index', ['per_page' => 10, 'page' => 1]));
        $response->assertStatus(200);
        $response->assertViewHas('programs');

        $programs = $response->viewData('programs');
        $this->assertEquals(10, $programs->count());
        $this->assertEquals(17, $programs->total());

        // Fetch page 2 with 10 per page
        $response = $this->actingAsAdmin()->get(route('admin.chuong-trinh.index', ['per_page' => 10, 'page' => 2]));
        $response->assertStatus(200);
        $programs = $response->viewData('programs');
        $this->assertEquals(7, $programs->count());
    }

    /**
     * Test campaign status is computed dynamically based on current time (over time).
     */
    public function test_campaign_status_rendered_correctly_based_on_time(): void
    {
        $org = DB::table('DonViToChuc')->first();
        $admin = DB::table('NguoiDung')->where('Email', 'admin@system.com')->first();

        // 1. Ongoing program: status approved, start in past, end in future
        $ongoingId = DB::table('ChuongTrinhHienMau')->insertGetId([
            'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
            'TenChuongTrinh' => 'Chiến Dịch Đang Diễn Ra',
            'MoTa' => 'Mô tả',
            'Banner' => 'linear-gradient(135deg, #f43f5e 0%, #e11d48 100%)',
            'DonViToChucId' => $org->Id,
            'DiaChi' => 'Hà Nội',
            'ThoiGianBatDau' => Carbon::now()->subHours(2)->format('Y-m-d H:i:s'),
            'ThoiGianKetThuc' => Carbon::now()->addHours(4)->format('Y-m-d H:i:s'),
            'ThoiGianMoDangKy' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'),
            'DangDienRa' => 1,
            'SoLuongDuKien' => 100,
            'TrangThai' => 3, // ongoing
            'NguoiTaoId' => $admin->Id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Upcoming program: status approved, start in future
        $upcomingId = DB::table('ChuongTrinhHienMau')->insertGetId([
            'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
            'TenChuongTrinh' => 'Chiến Dịch Sắp Diễn Ra',
            'MoTa' => 'Mô tả',
            'Banner' => 'linear-gradient(135deg, #f43f5e 0%, #e11d48 100%)',
            'DonViToChucId' => $org->Id,
            'DiaChi' => 'Hà Nội',
            'ThoiGianBatDau' => Carbon::now()->addDays(5)->format('Y-m-d H:i:s'),
            'ThoiGianKetThuc' => Carbon::now()->addDays(5)->addHours(8)->format('Y-m-d H:i:s'),
            'ThoiGianMoDangKy' => Carbon::now()->addDays(1)->format('Y-m-d H:i:s'),
            'DangDienRa' => 0,
            'SoLuongDuKien' => 100,
            'TrangThai' => 2, // approved / upcoming
            'NguoiTaoId' => $admin->Id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Ended program: status approved, end in past
        $endedId = DB::table('ChuongTrinhHienMau')->insertGetId([
            'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
            'TenChuongTrinh' => 'Chiến Dịch Đã Kết Thúc',
            'MoTa' => 'Mô tả',
            'Banner' => 'linear-gradient(135deg, #f43f5e 0%, #e11d48 100%)',
            'DonViToChucId' => $org->Id,
            'DiaChi' => 'Hà Nội',
            'ThoiGianBatDau' => Carbon::now()->subDays(5)->format('Y-m-d H:i:s'),
            'ThoiGianKetThuc' => Carbon::now()->subDays(5)->addHours(8)->format('Y-m-d H:i:s'),
            'ThoiGianMoDangKy' => Carbon::now()->subDays(10)->format('Y-m-d H:i:s'),
            'DangDienRa' => 0,
            'SoLuongDuKien' => 100,
            'TrangThai' => 5, // ended
            'NguoiTaoId' => $admin->Id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // View ongoing tab
        $response = $this->actingAsAdmin()->get(route('admin.chuong-trinh.index', ['tab' => 'dang-dien-ra']));
        $response->assertStatus(200);
        $response->assertSee('Chiến Dịch Đang Diễn Ra');
        $response->assertDontSee('Chiến Dịch Sắp Diễn Ra');

        // View upcoming/approved tab
        $response = $this->actingAsAdmin()->get(route('admin.chuong-trinh.index', ['tab' => 'da-duyet']));
        $response->assertStatus(200);
        $response->assertSee('Chiến Dịch Sắp Diễn Ra');
        $response->assertDontSee('Chiến Dịch Đang Diễn Ra');

        // View ended tab
        $response = $this->actingAsAdmin()->get(route('admin.chuong-trinh.index', ['tab' => 'da-ket-thuc']));
        $response->assertStatus(200);
        $response->assertSee('Chiến Dịch Đã Kết Thúc');
    }
}
