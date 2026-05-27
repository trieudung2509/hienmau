<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
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
     * Test login page loads successfully.
     */
    public function test_login_page_loads_successfully(): void
    {
        $response = $this->get(route('dang-nhap'));
        $response->assertStatus(200);
        $response->assertSee('Đăng nhập');
        $response->assertSee('Email hoặc Số điện thoại');
    }

    /**
     * Test successful login as donor.
     */
    public function test_can_login_as_donor_successfully(): void
    {
        $response = $this->post(route('dang-nhap.submit'), [
            'email_or_phone' => 'nguyenthif@gmail.com',
            'password' => 'F@123456',
        ]);

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('admin_user');
        
        $sessionUser = session('admin_user');
        $this->assertEquals('Nguyễn Thị F', $sessionUser['name']);
        $this->assertEquals('Người tham gia', $sessionUser['role']);
    }

    /**
     * Test successful login as system admin.
     */
    public function test_can_login_as_admin_successfully(): void
    {
        $response = $this->post(route('dang-nhap.submit'), [
            'email_or_phone' => 'nguyenvanan@gmail.com',
            'password' => 'An@123456',
        ]);

        $response->assertRedirect(route('admin.home'));
        $response->assertSessionHas('admin_user');
        
        $sessionUser = session('admin_user');
        $this->assertEquals('Nguyễn Văn An', $sessionUser['name']);
        $this->assertEquals('Quản trị viên', $sessionUser['role']);
    }

    /**
     * Test successful login as organization.
     */
    public function test_can_login_as_organization_successfully(): void
    {
        $response = $this->post(route('dang-nhap.submit'), [
            'email_or_phone' => 'contact@yhn.edu.vn',
            'password' => 'Dhyhn@123456',
        ]);

        $response->assertRedirect(route('don-vi-to-chuc.index'));
        $response->assertSessionHas('admin_user');
        
        $sessionUser = session('admin_user');
        $this->assertEquals('Trường ĐH Y Hà Nội', $sessionUser['name']);
        $this->assertEquals('Đơn vị tổ chức', $sessionUser['role']);

        // Assert that accessing the dashboard works
        $dashResponse = $this->get(route('don-vi-to-chuc.index'));
        $dashResponse->assertStatus(200);
        $dashResponse->assertSee('Đoàn Thanh niên ĐH Y Hà Nội');
        $dashResponse->assertSee('Đơn vị tổ chức');
    }

    /**
     * Test successful blocking of login for an organization role user who does not belong to any organization.
     */
    public function test_cannot_login_as_organization_without_linked_organization(): void
    {
        $response = $this->post(route('dang-nhap.submit'), [
            'email_or_phone' => 'abc.company@gmail.com',
            'password' => 'Abc@123456',
        ]);

        $response->assertSessionHasErrors(['email_or_phone']);
        $errors = session('errors')->get('email_or_phone');
        $this->assertContains('Tài khoản đơn vị tổ chức không thuộc về bất kỳ đơn vị nào.', $errors);
    }

    /**
     * Test successful program proposal listing and submission for an organizational user.
     */
    public function test_can_manage_program_proposals_as_organization(): void
    {
        // 1. Login
        $this->post(route('dang-nhap.submit'), [
            'email_or_phone' => 'contact@yhn.edu.vn',
            'password' => 'Dhyhn@123456',
        ]);

        // 2. View proposals page
        $viewResponse = $this->get(route('don-vi-to-chuc.chuong-trinh'));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Quản lý đề xuất chương trình');

        // 3. Post a new proposal
        $proposalData = [
            'TenChuongTrinh' => 'Hiến máu đông ấm 2026',
            'MoTa' => 'Chương trình hiến máu sưởi ấm mùa đông.',
            'DiaChi' => 'Trường Đại học Y Hà Nội',
            'ThoiGianBatDau' => now()->addDays(10)->format('Y-m-d\TH:i'),
            'ThoiGianKetThuc' => now()->addDays(10)->addHours(8)->format('Y-m-d\TH:i'),
            'ThoiGianMoDangKy' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'SoLuongDuKien' => 150,
        ];

        $storeResponse = $this->post(route('don-vi-to-chuc.chuong-trinh.store'), $proposalData);
        $storeResponse->assertRedirect(route('don-vi-to-chuc.chuong-trinh'));
        $storeResponse->assertSessionHas('success', 'Đề xuất chương trình hiến máu thành công.');

        // 4. Verify DB record exists
        $this->assertDatabaseHas('ChuongTrinhHienMau', [
            'TenChuongTrinh' => 'Hiến máu đông ấm 2026',
            'DiaChi' => 'Trường Đại học Y Hà Nội',
            'SoLuongDuKien' => 150,
            'TrangThai' => 1, // Pending approval
        ]);
    }

    /**
     * Test login fails with invalid credentials.
     */
    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->post(route('dang-nhap.submit'), [
            'email_or_phone' => 'nguyenthif@gmail.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertSessionHasErrors(['email_or_phone']);
    }

    /**
     * Test accessing /admin redirects dynamically based on logged in role.
     */
    public function test_admin_base_route_redirects_based_on_role(): void
    {
        // 1. Guest redirects to admin login via session middleware
        $response = $this->get('/admin');
        $response->assertRedirect(route('admin.login'));

        // 2. Logged in as Quản trị viên (Admin) redirects to admin.home (/admin/trang-chu)
        $responseAdmin = $this->withSession([
            'admin_user' => [
                'id' => 1,
                'name' => 'System Admin',
                'role' => 'Quản trị viên'
            ]
        ])->get('/admin');
        $responseAdmin->assertRedirect(route('admin.home'));

        // 3. Logged in as Nhân viên (Staff) redirects to nhan-vien.index (/admin/nhan-vien)
        $responseStaff = $this->withSession([
            'admin_user' => [
                'id' => 2,
                'name' => 'Trần Thị Bình',
                'role' => 'Nhân viên'
            ]
        ])->get('/admin');
        $responseStaff->assertRedirect(route('nhan-vien.index'));
    }
}
