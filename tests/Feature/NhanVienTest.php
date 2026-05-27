<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NhanVienTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and base tables
        $this->seed(\Database\Seeders\VaiTroSeeder::class);
        $this->seed(\Database\Seeders\AdminUserSeeder::class);
        $this->seed(\Database\Seeders\NguoiDungDemoSeeder::class);
        $this->seed(\Database\Seeders\ChuongTrinhSeeder::class);
    }

    private function actingAsStaff()
    {
        $staff = DB::table('NguoiDung')->where('Email', 'tranthibinh@gmail.com')->first();
        $id = $staff ? $staff->Id : 2;
        return $this->withSession([
            'admin_user' => [
                'id' => $id,
                'name' => 'Trần Thị Bình',
                'role' => 'Nhân viên'
            ]
        ]);
    }

    /**
     * Test staff portal home dashboard page loads successfully.
     */
    public function test_staff_dashboard_page_loads(): void
    {
        $response = $this->actingAsStaff()->get(route('nhan-vien.index'));
        $response->assertStatus(200);
        $response->assertSee('Trang nhân viên');
        $response->assertSee('Tìm kiếm nhanh');
        $response->assertSee('Họ và tên');
    }

    /**
     * Test health records view loads successfully with metrics.
     */
    public function test_staff_health_records_page_loads(): void
    {
        $response = $this->actingAsStaff()->get(route('nhan-vien.ho-so'));
        $response->assertStatus(200);
        $response->assertSee('Hồ sơ sức khỏe người hiến máu');
        $response->assertSee('Tổng số hồ sơ');
        $response->assertSee('Tìm kiếm');
    }

    /**
     * Test searching and filtering health records by name, phone, email, and program.
     */
    public function test_staff_health_records_filters(): void
    {
        // 1. Get seeded role and program IDs
        $roleId = DB::table('VaiTro')->where('TenVaiTro', 'Người tham gia')->value('Id');
        $progId1 = DB::table('ChuongTrinhHienMau')->where('TenChuongTrinh', 'Giọt hồng yêu thương 2025')->value('Id');
        $progId2 = DB::table('ChuongTrinhHienMau')->where('TenChuongTrinh', 'Hiến máu nhân đạo đợt 1')->value('Id');

        // 2. Insert donors
        $userId1 = DB::table('NguoiDung')->insertGetId([
            'HoTen' => 'Nguyễn Anh Tuấn',
            'Email' => 'anhtuan@gmail.com',
            'SoDienThoai' => '0977112233',
            'MatKhauHash' => bcrypt('password123'),
            'VaiTroId' => $roleId,
            'TrangThai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId2 = DB::table('NguoiDung')->insertGetId([
            'HoTen' => 'Lê Thị Thu',
            'Email' => 'thule@gmail.com',
            'SoDienThoai' => '0966445566',
            'MatKhauHash' => bcrypt('password123'),
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
            'GioiTinh' => 1, // Nam
            'NhomMau' => 'O+',
            'DiaChi' => 'Hà Nội',
            'CanNang' => 70.0,
            'NgheNghiep' => 'Lập trình viên',
            'SoLanDaHien' => 2,
            'TrangThaiSucKhoe' => 1,
        ]);

        $nhmId2 = DB::table('NguoiHienMau')->insertGetId([
            'NguoiDungId' => $userId2,
            'PublicId' => (string) \Illuminate\Support\Str::uuid(),
            'CCCD' => '987654321098',
            'NgaySinh' => '1998-10-22',
            'GioiTinh' => 2, // Nữ
            'NhomMau' => 'AB-',
            'DiaChi' => 'TP HCM',
            'CanNang' => 52.5,
            'NgheNghiep' => 'Nhân viên văn phòng',
            'SoLanDaHien' => 1,
            'TrangThaiSucKhoe' => 1,
        ]);

        // 3. Insert DangKyHienMau registrations
        $dkId1 = DB::table('DangKyHienMau')->insertGetId([
            'ChuongTrinhId' => $progId1,
            'NguoiHienMauId' => $nhmId1,
            'ThoiGianDangKy' => now(),
            'TrangThai' => 1,
            'GhiChu' => 'Đăng ký kiểm tra sức khỏe',
        ]);

        $dkId2 = DB::table('DangKyHienMau')->insertGetId([
            'ChuongTrinhId' => $progId2,
            'NguoiHienMauId' => $nhmId2,
            'ThoiGianDangKy' => now(),
            'TrangThai' => 1,
            'GhiChu' => 'Đăng ký kiểm tra sức khỏe',
        ]);

        // 4. Insert health records pre-donation
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

        $hsskId2 = DB::table('HoSoSucKhoe')->insertGetId([
            'DangKyId' => $dkId2,
            'HuyetAp' => '130/85',
            'NhipTim' => 82,
            'CanNang' => 52.5,
            'NhietDo' => 36.8,
            'Hemoglobin' => 12.8,
            'KetQua' => 1,
            'LyDoTuChoi' => '',
            'Nhommau' => 'AB-',
            'NguoiKham' => 'Bác sĩ B',
            'ThoiGianKham' => now(),
        ]);

        // 5. Insert donation record dossiers
        DB::table('HoSoHienMau')->insert([
            [
                'NguoiHienMauId' => $nhmId1,
                'ChuongTrinhId' => $progId1,
                'HoSoSucKhoeId' => $hsskId1,
                'LuongMau' => 350,
                'ThoiGianHien' => now(),
                'KetQuaSauHien' => 1,
                'GhiChu' => 'Huyết động ổn định, hồi phục nhanh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'NguoiHienMauId' => $nhmId2,
                'ChuongTrinhId' => $progId2,
                'HoSoSucKhoeId' => $hsskId2,
                'LuongMau' => 250,
                'ThoiGianHien' => now(),
                'KetQuaSauHien' => 1,
                'GhiChu' => 'Bình thường',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 5. Test empty search (both must show up)
        $response = $this->actingAsStaff()->get(route('nhan-vien.ho-so'));
        $response->assertStatus(200);
        $response->assertSee('anhtuan@gmail.com');
        $response->assertSee('thule@gmail.com');
        $response->assertSee('350 ml');
        $response->assertSee('250 ml');

        // 6. Test search filter by name
        $response = $this->actingAsStaff()->get(route('nhan-vien.ho-so', ['search' => 'Anh Tuấn']));
        $response->assertStatus(200);
        $response->assertSee('anhtuan@gmail.com');
        $response->assertDontSee('thule@gmail.com');

        // 7. Test search filter by phone number
        $response = $this->actingAsStaff()->get(route('nhan-vien.ho-so', ['search' => '0966445566']));
        $response->assertStatus(200);
        $response->assertDontSee('anhtuan@gmail.com');
        $response->assertSee('thule@gmail.com');

        // 8. Test search filter by email
        $response = $this->actingAsStaff()->get(route('nhan-vien.ho-so', ['search' => 'anhtuan@gmail.com']));
        $response->assertStatus(200);
        $response->assertSee('anhtuan@gmail.com');
        $response->assertDontSee('thule@gmail.com');

        // 9. Test filter by program
        $response = $this->actingAsStaff()->get(route('nhan-vien.ho-so', ['chuong_trinh_id' => $progId2]));
        $response->assertStatus(200);
        $response->assertDontSee('anhtuan@gmail.com');
        $response->assertSee('thule@gmail.com');
    }

    /**
     * Test staff can submit the creation modal form and add a new health record to the database, auto-creating a new donor account.
     */
    public function test_staff_can_store_health_record_and_auto_create_donor(): void
    {
        $progId = DB::table('ChuongTrinhHienMau')->where('TenChuongTrinh', 'Giọt hồng yêu thương 2025')->value('Id');

        $response = $this->actingAsStaff()->post(route('nhan-vien.ho-so.store'), [
            'hoten' => 'Trần Văn Tạo',
            'sodienthoai' => '0933887766',
            'email' => 'taotran@gmail.com',
            'cccd' => '112233445566',
            'ngaysinh' => '1993-12-12',
            'gioitinh' => 1,
            'diachi' => 'Hà Nội',
            'chuong_trinh_id' => $progId,
            'nhom_mau' => 'B+',
            'huyet_ap' => '120/80',
            'nhip_tim' => 76,
            'nhiet_do' => 36.5,
            'can_nang' => 68.0,
            'hemoglobin' => 14.1,
            'nguoi_kham' => 'Bác sĩ Khám tuyển',
            'luong_mau' => 350,
            'ket_qua_sau_hien' => 1,
            'ghi_chu' => 'Hiến máu thành công, sức khỏe ổn định',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Thêm mới hồ sơ sức khỏe thành công!');

        // Assert NguoiDung and NguoiHienMau were created
        $user = DB::table('NguoiDung')->where('Email', 'taotran@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('Trần Văn Tạo', $user->HoTen);
        $this->assertEquals('0933887766', $user->SoDienThoai);

        $nhm = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
        $this->assertNotNull($nhm);
        $this->assertEquals('112233445566', $nhm->CCCD);

        // Assert HoSoSucKhoe record was created in database
        $this->assertDatabaseHas('HoSoSucKhoe', [
            'HuyetAp' => '120/80',
            'NhipTim' => 76,
            'NhietDo' => 36.5,
            'CanNang' => 68.0,
            'Hemoglobin' => 14.1,
            'Nhommau' => 'B+',
            'NguoiKham' => 'Bác sĩ Khám tuyển',
        ]);

        // Assert HoSoHienMau was created in database
        $this->assertDatabaseHas('HoSoHienMau', [
            'NguoiHienMauId' => $nhm->Id,
            'ChuongTrinhId' => $progId,
            'LuongMau' => 350,
            'KetQuaSauHien' => 1,
            'GhiChu' => 'Hiến máu thành công, sức khỏe ổn định',
        ]);
    }

    /**
     * Test staff can submit health record for an existing user, which uses their existing account.
     */
    public function test_staff_can_store_health_record_for_existing_user(): void
    {
        $roleId = DB::table('VaiTro')->where('TenVaiTro', 'Người tham gia')->value('Id');
        $progId = DB::table('ChuongTrinhHienMau')->where('TenChuongTrinh', 'Giọt hồng yêu thương 2025')->value('Id');

        $userId = DB::table('NguoiDung')->insertGetId([
            'HoTen' => 'Lê Thị Thu',
            'Email' => 'thule@gmail.com',
            'SoDienThoai' => '0911223344',
            'MatKhauHash' => bcrypt('password123'),
            'VaiTroId' => $roleId,
            'TrangThai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $nhmId = DB::table('NguoiHienMau')->insertGetId([
            'NguoiDungId' => $userId,
            'PublicId' => (string) \Illuminate\Support\Str::uuid(),
            'CCCD' => '998877665544',
            'NgaySinh' => '1995-05-05',
            'GioiTinh' => 2,
            'NhomMau' => 'O+',
            'DiaChi' => 'Hải Phòng',
            'CanNang' => 54.0,
            'NgheNghiep' => 'Giáo viên',
            'SoLanDaHien' => 0,
            'TrangThaiSucKhoe' => 1,
        ]);

        $response = $this->actingAsStaff()->post(route('nhan-vien.ho-so.store'), [
            'hoten' => 'Lê Thị Thu Updated', // should find existing
            'sodienthoai' => '0911223344',
            'email' => 'thule@gmail.com',
            'cccd' => '998877665544',
            'ngaysinh' => '1995-05-05',
            'gioitinh' => 2,
            'diachi' => 'Hải Phòng',
            'chuong_trinh_id' => $progId,
            'nhom_mau' => 'O+',
            'huyet_ap' => '110/70',
            'nhip_tim' => 72,
            'nhiet_do' => 36.4,
            'can_nang' => 54.0,
            'hemoglobin' => 13.5,
            'nguoi_kham' => 'Bác sĩ Khám tuyển',
            'luong_mau' => 250,
            'ket_qua_sau_hien' => 1,
            'ghi_chu' => 'Tốt',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Thêm mới hồ sơ sức khỏe thành công!');

        // Assert no duplicate user created
        $this->assertEquals(1, DB::table('NguoiDung')->where('Email', 'thule@gmail.com')->count());

        // Assert HoSoHienMau linked to the existing user
        $this->assertDatabaseHas('HoSoHienMau', [
            'NguoiHienMauId' => $nhmId,
            'ChuongTrinhId' => $progId,
            'LuongMau' => 250,
        ]);
    }

    /**
     * Test staff can successfully update their own profile details.
     */
    public function test_staff_can_update_profile_dossier(): void
    {
        $staffUser = DB::table('NguoiDung')->where('Email', 'tranthibinh@gmail.com')->first();
        $this->assertNotNull($staffUser);
        $id = $staffUser->Id;

        $this->assertDatabaseHas('NguoiDung', [
            'Id' => $id,
            'Email' => 'tranthibinh@gmail.com',
            'HoTen' => 'Trần Thị Bình',
        ]);

        $response = $this->actingAsStaff()->post(route('nhan-vien.profile.update'), [
            'HoTen' => 'Trần Thị Bình Updated',
            'Email' => 'tranthibinh_new@gmail.com',
            'SoDienThoai' => '0988776655',
            'NgaySinh' => '1990-01-01',
            'GioiTinh' => 2,
            'MatKhau' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Cập nhật thông tin cá nhân thành công!');

        // Verify user in database
        $this->assertDatabaseHas('NguoiDung', [
            'Id' => $id,
            'HoTen' => 'Trần Thị Bình Updated',
            'Email' => 'tranthibinh_new@gmail.com',
            'SoDienThoai' => '0988776655',
            'NgaySinh' => '1990-01-01',
            'GioiTinh' => 2,
        ]);

        // Verify session update
        $sessionUser = session('admin_user');
        $this->assertEquals('Trần Thị Bình Updated', $sessionUser['name']);
        $this->assertEquals('tranthibinh_new@gmail.com', $sessionUser['email']);
    }

    /**
     * Test validation rules for staff profile editing.
     */
    public function test_staff_profile_update_validation(): void
    {
        // 1. Missing required field
        $response = $this->actingAsStaff()->post(route('nhan-vien.profile.update'), [
            'HoTen' => '',
            'Email' => 'binh@gmail.com',
            'SoDienThoai' => '0988776655',
        ]);
        $response->assertSessionHasErrors(['HoTen']);

        // 2. Duplicate email
        // Seed another user
        $roleId = DB::table('VaiTro')->where('TenVaiTro', 'Người tham gia')->value('Id');
        DB::table('NguoiDung')->insert([
            'HoTen' => 'User Khac',
            'Email' => 'unique_email@gmail.com',
            'SoDienThoai' => '0912345678',
            'MatKhauHash' => bcrypt('password'),
            'VaiTroId' => $roleId,
            'TrangThai' => 1,
        ]);

        $response = $this->actingAsStaff()->post(route('nhan-vien.profile.update'), [
            'HoTen' => 'Bình',
            'Email' => 'unique_email@gmail.com',
            'SoDienThoai' => '0988776655',
        ]);
        $response->assertSessionHasErrors(['Email']);

        // 3. Incorrect phone number regex pattern
        $response = $this->actingAsStaff()->post(route('nhan-vien.profile.update'), [
            'HoTen' => 'Bình',
            'Email' => 'binh@gmail.com',
            'SoDienThoai' => '12345',
        ]);
        $response->assertSessionHasErrors(['SoDienThoai']);
    }
}
