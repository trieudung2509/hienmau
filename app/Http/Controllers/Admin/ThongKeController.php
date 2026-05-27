<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ThongKeController extends Controller
{
    public function index(Request $request)
    {
        // 1. Gather Date & Program Filters
        $khoangThoiGian = $request->get('khoang_thoi_gian', '2025');
        if ($khoangThoiGian === '2025') {
            $tuNgay = '2025-01-01';
            $denNgay = '2025-12-31';
        } elseif ($khoangThoiGian === '2024') {
            $tuNgay = '2024-01-01';
            $denNgay = '2024-12-31';
        } else {
            $tuNgay = $request->get('tu_ngay', '2025-01-01');
            $denNgay = $request->get('den_ngay', '2025-12-31');
        }
        $chuongTrinhId = $request->get('chuong_trinh_id', '');

        // 2. Fetch all programs for select filter dropdown
        $allPrograms = DB::table('ChuongTrinhHienMau')
            ->select('Id', 'TenChuongTrinh')
            ->whereNull('deleted_at')
            ->get();

        // 3. Count dynamic database totals for current period
        $programQuery = DB::table('ChuongTrinhHienMau')
            ->whereBetween('ThoiGianBatDau', [$tuNgay . ' 00:00:00', $denNgay . ' 23:59:59'])
            ->whereNull('deleted_at');
        if ($chuongTrinhId) {
            $programQuery->where('Id', $chuongTrinhId);
        }
        $totalPrograms = $programQuery->count();

        $donationQuery = DB::table('HoSoHienMau')
            ->whereBetween('ThoiGianHien', [$tuNgay . ' 00:00:00', $denNgay . ' 23:59:59'])
            ->whereNull('deleted_at');
        if ($chuongTrinhId) {
            $donationQuery->where('ChuongTrinhId', $chuongTrinhId);
        }
        $totalDonations = $donationQuery->count();
        $totalBlood = $donationQuery->sum('LuongMau');
        $totalParticipants = $donationQuery->distinct('NguoiHienMauId')->count('NguoiHienMauId');

        // 4. Compute growth compared to the equivalent previous period
        $prevTuNgay = Carbon::parse($tuNgay)->subYear()->format('Y-m-d');
        $prevDenNgay = Carbon::parse($denNgay)->subYear()->format('Y-m-d');

        $prevProgramQuery = DB::table('ChuongTrinhHienMau')
            ->whereBetween('ThoiGianBatDau', [$prevTuNgay . ' 00:00:00', $prevDenNgay . ' 23:59:59'])
            ->whereNull('deleted_at');
        if ($chuongTrinhId) {
            $prevProgramQuery->where('Id', $chuongTrinhId);
        }
        $prevPrograms = $prevProgramQuery->count();

        $prevDonationQuery = DB::table('HoSoHienMau')
            ->whereBetween('ThoiGianHien', [$prevTuNgay . ' 00:00:00', $prevDenNgay . ' 23:59:59'])
            ->whereNull('deleted_at');
        if ($chuongTrinhId) {
            $prevDonationQuery->where('ChuongTrinhId', $chuongTrinhId);
        }
        $prevDonations = $prevDonationQuery->count();
        $prevBlood = $prevDonationQuery->sum('LuongMau');
        $prevParticipants = $prevDonationQuery->distinct('NguoiHienMauId')->count('NguoiHienMauId');

        $calcGrowth = function($current, $previous) {
            if ($previous == 0) {
                return $current > 0 ? 100 : 0;
            }
            return (($current - $previous) / $previous) * 100;
        };

        $programGrowth = $calcGrowth($totalPrograms, $prevPrograms);
        $participantGrowth = $calcGrowth($totalParticipants, $prevParticipants);
        $bloodGrowth = $calcGrowth($totalBlood, $prevBlood);
        $donationGrowth = $calcGrowth($totalDonations, $prevDonations);

        // 5. Group by Status counts in DB for current period
        $statusCountsQuery = DB::table('ChuongTrinhHienMau')
            ->whereBetween('ThoiGianBatDau', [$tuNgay . ' 00:00:00', $denNgay . ' 23:59:59'])
            ->whereNull('deleted_at');
        if ($chuongTrinhId) {
            $statusCountsQuery->where('Id', $chuongTrinhId);
        }
        $statusCounts = $statusCountsQuery
            ->select('TrangThai', DB::raw('count(*) as total'))
            ->groupBy('TrangThai')
            ->pluck('total', 'TrangThai')
            ->toArray();

        $status1 = $statusCounts[1] ?? 0; // Chờ duyệt
        $status3 = $statusCounts[3] ?? 0; // Đang diễn ra
        $status4 = $statusCounts[4] ?? 0; // Đã hủy
        $status5 = ($statusCounts[5] ?? 0) + ($statusCounts[2] ?? 0); // Đã kết thúc + Đã duyệt

        // 6. Blood Group Breakdown for current period
        $bloodGroupCountsQuery = DB::table('NguoiHienMau as nhm')
            ->join('HoSoHienMau as hshm', 'nhm.Id', '=', 'hshm.NguoiHienMauId')
            ->whereBetween('hshm.ThoiGianHien', [$tuNgay . ' 00:00:00', $denNgay . ' 23:59:59'])
            ->whereNull('hshm.deleted_at');

        if ($chuongTrinhId) {
            $bloodGroupCountsQuery->where('hshm.ChuongTrinhId', $chuongTrinhId);
        }

        $bloodGroupCounts = $bloodGroupCountsQuery
            ->select('nhm.NhomMau', DB::raw('count(distinct hshm.NguoiHienMauId) as people'), DB::raw('sum(hshm.LuongMau) as ml'))
            ->groupBy('nhm.NhomMau')
            ->get()
            ->keyBy('NhomMau')
            ->toArray();

        $bloodTypes = [
            'A' => [
                'people' => (int) ($bloodGroupCounts['A']->people ?? 0),
                'ml' => (int) ($bloodGroupCounts['A']->ml ?? 0)
            ],
            'O' => [
                'people' => (int) ($bloodGroupCounts['O']->people ?? 0),
                'ml' => (int) ($bloodGroupCounts['O']->ml ?? 0)
            ],
            'B' => [
                'people' => (int) ($bloodGroupCounts['B']->people ?? 0),
                'ml' => (int) ($bloodGroupCounts['B']->ml ?? 0)
            ],
            'AB' => [
                'people' => (int) ($bloodGroupCounts['AB']->people ?? 0),
                'ml' => (int) ($bloodGroupCounts['AB']->ml ?? 0)
            ]
        ];

        // 7. Top 5 programs based on total blood volume and registrations
        $programsQuery = DB::table('ChuongTrinhHienMau as ct')
            ->whereBetween('ct.ThoiGianBatDau', [$tuNgay . ' 00:00:00', $denNgay . ' 23:59:59'])
            ->whereNull('ct.deleted_at');

        if ($chuongTrinhId) {
            $programsQuery->where('ct.Id', $chuongTrinhId);
        }

        $programsList = $programsQuery
            ->select('ct.Id', 'ct.TenChuongTrinh', 'ct.ThoiGianBatDau', 'ct.SoLuongDuKien')
            ->get();

        $topPrograms = [];
        foreach ($programsList as $p) {
            $registeredCount = DB::table('DangKyHienMau')
                ->where('ChuongTrinhId', $p->Id)
                ->whereNull('deleted_at')
                ->count();

            $totalBloodVolume = DB::table('HoSoHienMau')
                ->where('ChuongTrinhId', $p->Id)
                ->whereNull('deleted_at')
                ->sum('LuongMau');

            $percent = $p->SoLuongDuKien > 0 ? min(100, round(($registeredCount / $p->SoLuongDuKien) * 100)) : 0;

            $topPrograms[] = [
                'name' => $p->TenChuongTrinh,
                'date' => Carbon::parse($p->ThoiGianBatDau)->format('d/m/Y'),
                'participants' => "{$registeredCount}/{$p->SoLuongDuKien}",
                'percent' => $percent,
                'ml' => (int) $totalBloodVolume
            ];
        }

        usort($topPrograms, function($a, $b) {
            return $b['ml'] <=> $a['ml'];
        });

        $topPrograms = array_slice($topPrograms, 0, 5);

        // 8. Monthly volume chart data grouped using PHP for db-agnostic safety
        $targetYear = Carbon::parse($tuNgay)->year;

        $monthlyVolumeQuery = DB::table('HoSoHienMau')
            ->whereYear('ThoiGianHien', $targetYear)
            ->whereNull('deleted_at');

        if ($chuongTrinhId) {
            $monthlyVolumeQuery->where('ChuongTrinhId', $chuongTrinhId);
        }

        $records = $monthlyVolumeQuery
            ->select('ThoiGianHien', 'LuongMau')
            ->get();

        $monthlyVolume = array_fill(0, 12, 0);
        foreach ($records as $record) {
            $month = (int) Carbon::parse($record->ThoiGianHien)->month;
            if ($month >= 1 && $month <= 12) {
                $monthlyVolume[$month - 1] += (int) $record->LuongMau;
            }
        }

        // 9. Yearly Trend (5 years ending with targetYear)
        $years = [
            $targetYear - 4,
            $targetYear - 3,
            $targetYear - 2,
            $targetYear - 1,
            $targetYear
        ];
        $trendYears = array_map('strval', $years);

        $yearlyTrend = [];
        foreach ($years as $yr) {
            $yearlyQuery = DB::table('HoSoHienMau')
                ->whereYear('ThoiGianHien', $yr)
                ->whereNull('deleted_at');

            if ($chuongTrinhId) {
                $yearlyQuery->where('ChuongTrinhId', $chuongTrinhId);
            }

            $yearlyTrend[] = $yearlyQuery->distinct('NguoiHienMauId')->count('NguoiHienMauId');
        }

        $p2024 = $yearlyTrend[3] ?? 0;
        $p2025 = $yearlyTrend[4] ?? 0;
        $growthDiff = $p2025 - $p2024;
        $growthPercent = $p2024 > 0 ? ($growthDiff / $p2024) * 100 : ($growthDiff > 0 ? 100 : 0);

        return view('admin.thong-ke', compact(
            'totalPrograms',
            'totalParticipants',
            'totalBlood',
            'totalDonations',
            'programGrowth',
            'participantGrowth',
            'bloodGrowth',
            'donationGrowth',
            'status1',
            'status3',
            'status4',
            'status5',
            'bloodTypes',
            'topPrograms',
            'monthlyVolume',
            'yearlyTrend',
            'trendYears',
            'growthPercent',
            'growthDiff',
            'allPrograms',
            'tuNgay',
            'denNgay',
            'chuongTrinhId',
            'khoangThoiGian'
        ));
    }
}
