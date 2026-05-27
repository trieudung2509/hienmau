<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HoSoController extends Controller
{
    public function index(Request $request)
    {
        // 1. Base query for blood donation registration records and dossiers
        $query = DB::table('DangKyHienMau as dk')
            ->join('NguoiHienMau as nhm', 'dk.NguoiHienMauId', '=', 'nhm.Id')
            ->join('NguoiDung as nd', 'nhm.NguoiDungId', '=', 'nd.Id')
            ->join('ChuongTrinhHienMau as ct', 'dk.ChuongTrinhId', '=', 'ct.Id')
            ->leftJoin('HoSoHienMau as hs', function ($join) {
                $join->on('hs.NguoiHienMauId', '=', 'dk.NguoiHienMauId')
                    ->on('hs.ChuongTrinhId', '=', 'dk.ChuongTrinhId')
                    ->whereNull('hs.deleted_at');
            })
            ->leftJoin('HoSoSucKhoe as hssk', 'hs.HoSoSucKhoeId', '=', 'hssk.Id')
            ->select(
                'dk.Id as DangKyId',
                'dk.ThoiGianDangKy',
                'dk.TrangThai as DangKyTrangThai',
                'hs.Id as HoSoId',
                'hs.LuongMau',
                'hs.ThoiGianHien',
                'hs.KetQuaSauHien',
                'hs.GhiChu',
                'nd.HoTen',
                'nd.Email',
                'nd.SoDienThoai',
                'nhm.NgaySinh',
                'nhm.GioiTinh',
                'nhm.NhomMau',
                'ct.TenChuongTrinh',
                'hssk.HuyetAp',
                'hssk.NhipTim',
                'hssk.CanNang',
                'hssk.NhietDo',
                'hssk.Hemoglobin',
                'hssk.NguoiKham'
            )
            ->whereNull('dk.deleted_at');

        // 2. Filter by Name, Email, Phone Number, or Program
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('nd.HoTen', 'like', "%{$search}%")
                  ->orWhere('nd.Email', 'like', "%{$search}%")
                  ->orWhere('nd.SoDienThoai', 'like', "%{$search}%");
            });
        }

        if ($request->filled('chuong_trinh_id')) {
            $query->where('dk.ChuongTrinhId', $request->get('chuong_trinh_id'));
        }

        // 3. Paginate the list
        $perPage = 10;
        $records = $query->orderBy('dk.ThoiGianDangKy', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // 4. Fetch all programs for select dropdown filter
        $programsList = DB::table('ChuongTrinhHienMau')
            ->whereNull('deleted_at')
            ->select('Id', 'TenChuongTrinh')
            ->get();

        // 5. Calculate key stats (globally or filtered by program)
        $hoSoBase = DB::table('HoSoHienMau')->whereNull('deleted_at');
        $dangKyBase = DB::table('DangKyHienMau')->whereNull('deleted_at');

        if ($request->filled('chuong_trinh_id')) {
            $progId = $request->get('chuong_trinh_id');
            $hoSoBase->where('ChuongTrinhId', $progId);
            $dangKyBase->where('ChuongTrinhId', $progId);
        }

        $totalHoSo = $dangKyBase->count();
        $successCount = (clone $hoSoBase)->where('KetQuaSauHien', 1)->count();
        $totalVol = (clone $hoSoBase)->where('KetQuaSauHien', 1)->sum('LuongMau');
        $daHuyCount = (clone $dangKyBase)->where('TrangThai', 0)->count();

        $metrics = [
            'tong_ho_so' => $totalHoSo,
            'thanh_cong' => $successCount,
            'tong_luong_mau' => number_format($totalVol, 0, ',', '.'),
            'da_huy' => $daHuyCount,
        ];

        return view('admin.ho-so.index', compact('records', 'metrics', 'programsList'));
    }
}
