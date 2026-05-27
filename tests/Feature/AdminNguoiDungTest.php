<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminNguoiDungTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database
        $this->seed(\Database\Seeders\VaiTroSeeder::class);
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
     * Test successful creation of a new user.
     */
    public function test_can_create_user_with_valid_data(): void
    {
        $role = DB::table('VaiTro')->where('TenVaiTro', 'Nhân viên')->first();

        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.store'), [
            'HoTen' => 'Nguyễn Hữu Nhân Viên',
            'Email' => 'nhanvien@gmail.com',
            'SoDienThoai' => '0987654321',
            'MatKhau' => '123456',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
        ]);

        $response->assertRedirect(route('admin.nguoi-dung.index'));
        $response->assertSessionHas('success', 'Thêm người dùng mới thành công.');

        $this->assertDatabaseHas('NguoiDung', [
            'HoTen' => 'Nguyễn Hữu Nhân Viên',
            'Email' => 'nhanvien@gmail.com',
            'SoDienThoai' => '0987654321',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
        ]);
    }

    /**
     * Test email validation (required, format, unique).
     */
    public function test_validation_fails_for_invalid_or_duplicate_email(): void
    {
        $role = DB::table('VaiTro')->where('TenVaiTro', 'Nhân viên')->first();

        // 1. Missing HoTen and Email
        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.store'), [
            'SoDienThoai' => '0987654321',
            'MatKhau' => '123456',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
        ]);
        $response->assertSessionHasErrors(['HoTen', 'Email']);

        // 2. Invalid Email format
        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.store'), [
            'HoTen' => 'Test User',
            'Email' => 'not-an-email',
            'SoDienThoai' => '0987654321',
            'MatKhau' => '123456',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
        ]);
        $response->assertSessionHasErrors(['Email']);

        // 3. Duplicate email
        // First create a user
        DB::table('NguoiDung')->insert([
            'HoTen' => 'User One',
            'Email' => 'duplicate@gmail.com',
            'SoDienThoai' => '0912345678',
            'MatKhauHash' => 'somehash',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.store'), [
            'HoTen' => 'User Two',
            'Email' => 'duplicate@gmail.com',
            'SoDienThoai' => '0987654321',
            'MatKhau' => '123456',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
        ]);
        $response->assertSessionHasErrors(['Email']);
    }

    /**
     * Test phone validation (required, format, unique).
     */
    public function test_validation_fails_for_invalid_or_duplicate_phone(): void
    {
        $role = DB::table('VaiTro')->where('TenVaiTro', 'Nhân viên')->first();

        // 1. Invalid phone format (not starting with 0, or too short/long)
        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.store'), [
            'HoTen' => 'Test User',
            'Email' => 'test@gmail.com',
            'SoDienThoai' => '1234567890', // Doesn't start with 02,03,05,07,08,09
            'MatKhau' => '123456',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
        ]);
        $response->assertSessionHasErrors(['SoDienThoai']);

        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.store'), [
            'HoTen' => 'Test User',
            'Email' => 'test@gmail.com',
            'SoDienThoai' => '09123', // Too short
            'MatKhau' => '123456',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
        ]);
        $response->assertSessionHasErrors(['SoDienThoai']);

        // 2. Duplicate Phone Number
        DB::table('NguoiDung')->insert([
            'HoTen' => 'User One',
            'Email' => 'userone@gmail.com',
            'SoDienThoai' => '0912345678',
            'MatKhauHash' => 'somehash',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.store'), [
            'HoTen' => 'User Two',
            'Email' => 'usertwo@gmail.com',
            'SoDienThoai' => '0912345678', // Duplicate
            'MatKhau' => '123456',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
        ]);
        $response->assertSessionHasErrors(['SoDienThoai']);
    }

    /**
     * Test password validation (required, minimum length).
     */
    public function test_validation_fails_for_short_password(): void
    {
        $role = DB::table('VaiTro')->where('TenVaiTro', 'Nhân viên')->first();

        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.store'), [
            'HoTen' => 'Test User',
            'Email' => 'test@gmail.com',
            'SoDienThoai' => '0987654321',
            'MatKhau' => '12345', // < 6 chars
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
        ]);
        $response->assertSessionHasErrors(['MatKhau']);
    }

    /**
     * Test paginating users correctly.
     */
    public function test_can_paginate_users(): void
    {
        $role = DB::table('VaiTro')->where('TenVaiTro', 'Nhân viên')->first();

        // Create 15 users
        for ($i = 1; $i <= 15; $i++) {
            DB::table('NguoiDung')->insert([
                'HoTen' => "User Number $i",
                'Email' => "user$i@gmail.com",
                'SoDienThoai' => "09123456" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'MatKhauHash' => 'somehash',
                'VaiTroId' => $role->Id,
                'TrangThai' => 1,
                'created_at' => now()->subMinutes(20 - $i), // different timestamps to ensure consistent order
                'updated_at' => now(),
            ]);
        }

        // Fetch page 1 with 10 per page
        $response = $this->actingAsAdmin()->get(route('admin.nguoi-dung.index', ['per_page' => 10, 'page' => 1]));
        $response->assertStatus(200);
        $response->assertViewHas('users');

        $users = $response->viewData('users');
        $this->assertEquals(10, $users->count());
        $this->assertEquals(15, $users->total());

        // Fetch page 2
        $response = $this->actingAsAdmin()->get(route('admin.nguoi-dung.index', ['per_page' => 10, 'page' => 2]));
        $response->assertStatus(200);
        $users = $response->viewData('users');
        $this->assertEquals(5, $users->count());
    }

    /**
     * Test successful update of a user.
     */
    public function test_can_update_user_with_valid_data(): void
    {
        $role = DB::table('VaiTro')->where('TenVaiTro', 'Nhân viên')->first();

        // Create user first
        $userId = DB::table('NguoiDung')->insertGetId([
            'HoTen' => 'Nguyễn Hữu Nhân Viên',
            'Email' => 'nhanvien@gmail.com',
            'SoDienThoai' => '0987654321',
            'MatKhauHash' => 'somehash',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'Id');

        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.update', $userId), [
            'HoTen' => 'Nguyễn Hữu Nhân Viên Đã Sửa',
            'Email' => 'nhanvien_sua@gmail.com',
            'SoDienThoai' => '0912345678',
            'VaiTroId' => $role->Id,
            'TrangThai' => 2,
        ]);

        $response->assertRedirect(route('admin.nguoi-dung.index'));
        $response->assertSessionHas('success', 'Cập nhật tài khoản người dùng thành công.');

        $this->assertDatabaseHas('NguoiDung', [
            'Id' => $userId,
            'HoTen' => 'Nguyễn Hữu Nhân Viên Đã Sửa',
            'Email' => 'nhanvien_sua@gmail.com',
            'SoDienThoai' => '0912345678',
            'VaiTroId' => $role->Id,
            'TrangThai' => 2,
        ]);
    }

    /**
     * Test password remains unchanged if left blank in update.
     */
    public function test_password_remains_unchanged_when_left_blank(): void
    {
        $role = DB::table('VaiTro')->where('TenVaiTro', 'Nhân viên')->first();

        // Create user first
        $userId = DB::table('NguoiDung')->insertGetId([
            'HoTen' => 'Nguyễn Hữu Nhân Viên',
            'Email' => 'nhanvien@gmail.com',
            'SoDienThoai' => '0987654321',
            'MatKhauHash' => 'old_hash',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'Id');

        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.update', $userId), [
            'HoTen' => 'Nguyễn Hữu Nhân Viên',
            'Email' => 'nhanvien@gmail.com',
            'SoDienThoai' => '0987654321',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
            'MatKhau' => '', // blank password should not change the old one
        ]);

        $response->assertRedirect(route('admin.nguoi-dung.index'));

        $this->assertDatabaseHas('NguoiDung', [
            'Id' => $userId,
            'MatKhauHash' => 'old_hash',
        ]);
    }
}
