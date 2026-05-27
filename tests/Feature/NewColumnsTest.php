<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class NewColumnsTest extends TestCase
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
     * Test creating and updating a user with NgaySinh and GioiTinh fields.
     */
    public function test_can_create_and_update_user_with_ngaysinh_and_gioitinh(): void
    {
        // 1. Create a user
        $role = DB::table('VaiTro')->where('TenVaiTro', 'Nhân viên')->first();
        $this->assertNotNull($role);

        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.store'), [
            'HoTen' => 'Nguyễn Văn Test Fields',
            'Email' => 'test_fields@gmail.com',
            'SoDienThoai' => '0988667788',
            'MatKhau' => 'Password123',
            'VaiTroId' => $role->Id,
            'TrangThai' => 1,
            'NgaySinh' => '1995-06-20',
            'GioiTinh' => 1, // Nam
        ]);

        $response->assertRedirect(route('admin.nguoi-dung.index'));
        $response->assertSessionHas('success', 'Thêm người dùng mới thành công.');

        $this->assertDatabaseHas('NguoiDung', [
            'Email' => 'test_fields@gmail.com',
            'NgaySinh' => '1995-06-20',
            'GioiTinh' => 1,
        ]);

        // Get the new user
        $user = DB::table('NguoiDung')->where('Email', 'test_fields@gmail.com')->first();

        // 2. Update user
        $response = $this->actingAsAdmin()->post(route('admin.nguoi-dung.update', $user->Id), [
            'HoTen' => 'Nguyễn Văn Test Fields Updated',
            'Email' => 'test_fields_updated@gmail.com',
            'SoDienThoai' => '0988667788',
            'VaiTroId' => $role->Id,
            'TrangThai' => 2,
            'NgaySinh' => '1996-07-21',
            'GioiTinh' => 2, // Nữ
        ]);

        $response->assertRedirect(route('admin.nguoi-dung.index'));
        $response->assertSessionHas('success', 'Cập nhật tài khoản người dùng thành công.');

        $this->assertDatabaseHas('NguoiDung', [
            'Id' => $user->Id,
            'Email' => 'test_fields_updated@gmail.com',
            'NgaySinh' => '1996-07-21',
            'GioiTinh' => 2,
        ]);
    }

    /**
     * Test creating and updating DonViToChuc with HinhAnh logo.
     */
    public function test_can_create_and_update_donvitochuc_with_hinhanh(): void
    {
        $owner = DB::table('NguoiDung')->where('Id', 1)->first();

        // 1. Create DonViToChuc
        $response = $this->actingAsAdmin()->post(route('admin.don-vi-to-chuc.store'), [
            'TenDonVi' => 'Hội Chữ Thập Đỏ Test',
            'MaDonVi' => 'HCTD-TEST',
            'Loai' => 'Từ thiện',
            'Email' => 'hctd_test@gmail.com',
            'SoDienThoai' => '0243998877',
            'DiaChi' => '123 Test Street, Hanoi',
            'MoTa' => 'Mô tả hội chữ thập đỏ test.',
            'NguoiDaiDien' => 'Nguyễn Văn Đại Diện',
            'TrangThai' => 1,
            'OwnerUserId' => $owner->Id,
            'HinhAnh' => 'https://images.unsplash.com/photo-test',
        ]);

        $response->assertRedirect(route('admin.don-vi-to-chuc.index'));
        $response->assertSessionHas('success', 'Them don vi to chuc thanh cong.');

        $this->assertDatabaseHas('DonViToChuc', [
            'MaDonVi' => 'HCTD-TEST',
            'HinhAnh' => 'https://images.unsplash.com/photo-test',
        ]);

        $org = DB::table('DonViToChuc')->where('MaDonVi', 'HCTD-TEST')->first();

        // 2. Update DonViToChuc
        $response = $this->actingAsAdmin()->post(route('admin.don-vi-to-chuc.update', $org->Id), [
            'TenDonVi' => 'Hội Chữ Thập Đỏ Test Updated',
            'MaDonVi' => 'HCTD-TEST-NEW',
            'Loai' => 'Từ thiện',
            'Email' => 'hctd_test_updated@gmail.com',
            'SoDienThoai' => '0243998877',
            'DiaChi' => '123 Test Street, Hanoi',
            'MoTa' => 'Mô tả hội chữ thập đỏ test updated.',
            'NguoiDaiDien' => 'Nguyễn Văn Đại Diện',
            'TrangThai' => 2,
            'OwnerUserId' => $owner->Id,
            'HinhAnh' => 'https://images.unsplash.com/photo-test-updated',
        ]);

        $response->assertRedirect(route('admin.don-vi-to-chuc.index'));
        $response->assertSessionHas('success', 'Cap nhat don vi to chuc thanh cong.');

        $this->assertDatabaseHas('DonViToChuc', [
            'Id' => $org->Id,
            'MaDonVi' => 'HCTD-TEST-NEW',
            'HinhAnh' => 'https://images.unsplash.com/photo-test-updated',
        ]);
    }
}
