<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AdminThongKeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database
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
     * Test statistics dashboard renders successfully for admin.
     */
    public function test_statistics_dashboard_renders_successfully(): void
    {
        $response = $this->actingAsAdmin()->get(route('admin.thong-ke'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.thong-ke');
        $response->assertViewHas([
            'totalPrograms',
            'totalParticipants',
            'totalBlood',
            'totalDonations',
            'status1',
            'status3',
            'status4',
            'status5',
            'bloodTypes',
            'topPrograms',
            'monthlyVolume',
            'yearlyTrend'
        ]);
    }
}
