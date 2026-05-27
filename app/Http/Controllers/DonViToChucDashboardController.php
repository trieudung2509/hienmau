<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DonViToChucDashboardController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('admin_user')) {
            return redirect()->route('dang-nhap');
        }

        $adminSession = $request->session()->get('admin_user');
        $userId = $adminSession['id'];

        $user = DB::table('NguoiDung as nd')
            ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
            ->select('nd.*', 'vt.TenVaiTro')
            ->where('nd.Id', $userId)
            ->first();

        if (!$user || $user->TenVaiTro !== 'Đơn vị tổ chức') {
            return redirect()->route('dang-nhap')->withErrors(['email_or_phone' => 'Tài khoản không có quyền truy cập vai trò tổ chức.']);
        }

        // Find organization linked to the logged in user
        $donVi = DB::table('DonViToChuc')->where('OwnerUserId', $user->Id)->first();
        if (!$donVi) {
            $donVi = DB::table('DonViToChuc')->where('Email', $user->Email)->first();
        }
        if (!$donVi) {
            $donVi = DB::table('DonViToChuc')->where('SoDienThoai', $user->SoDienThoai)->first();
        }

        if (!$donVi) {
            $request->session()->forget('admin_user');
            return redirect()->route('dang-nhap')->withErrors(['email_or_phone' => 'Tài khoản đơn vị tổ chức không thuộc về bất kỳ đơn vị nào.']);
        }

        // Calculate dynamic stats
        $choDuyetCount = DB::table('ChuongTrinhHienMau')
            ->where('DonViToChucId', $donVi->Id)
            ->whereNull('deleted_at')
            ->where('TrangThai', 1)
            ->count();

        $sapDienRaCount = DB::table('ChuongTrinhHienMau')
            ->where('DonViToChucId', $donVi->Id)
            ->whereNull('deleted_at')
            ->whereIn('TrangThai', [2, 3])
            ->count();

        $registeredCount = DB::table('DangKyHienMau')
            ->join('ChuongTrinhHienMau', 'DangKyHienMau.ChuongTrinhId', '=', 'ChuongTrinhHienMau.Id')
            ->where('ChuongTrinhHienMau.DonViToChucId', $donVi->Id)
            ->whereNull('DangKyHienMau.deleted_at')
            ->count();

        $stats = [
            'cho_duyet' => $choDuyetCount,
            'sap_dien_ra' => $sapDienRaCount,
            'dang_ky' => $registeredCount,
        ];

        // Query organization programs
        $query = DB::table('ChuongTrinhHienMau as ct')
            ->where('ct.DonViToChucId', $donVi->Id)
            ->whereNull('ct.deleted_at');

        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('ct.TenChuongTrinh', 'like', "%{$keyword}%")
                  ->orWhere('ct.DiaChi', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('trang_thai')) {
            $status = $request->get('trang_thai');
            if ($status === 'Đã duyệt') {
                $query->where('ct.TrangThai', 2);
            } elseif ($status === 'Chờ duyệt') {
                $query->where('ct.TrangThai', 1);
            } elseif ($status === 'Đang diễn ra') {
                $query->where('ct.TrangThai', 3);
            }
        }

        if ($request->filled('thoi_gian')) {
            $time = $request->get('thoi_gian');
            if ($time === 'Tháng này') {
                $query->whereMonth('ct.ThoiGianBatDau', now()->month)
                      ->whereYear('ct.ThoiGianBatDau', now()->year);
            } elseif ($time === 'Tháng sau') {
                $nextMonth = now()->addMonth();
                $query->whereMonth('ct.ThoiGianBatDau', $nextMonth->month)
                      ->whereYear('ct.ThoiGianBatDau', $nextMonth->year);
            }
        }

        $programs = $query->orderBy('ct.ThoiGianBatDau', 'asc')->get();

        // Mock items if no actual db entries found
        if ($programs->isEmpty() && !$request->filled('keyword') && !$request->filled('trang_thai') && !$request->filled('thoi_gian')) {
            $programs = collect([
                (object)[
                    'Id' => 101,
                    'TenChuongTrinh' => 'Giọt hồng yêu thương 2025',
                    'DiaChi' => 'Viện Huyết học - Truyền máu TW, Hà Nội',
                    'ThoiGianBatDau' => '2025-06-20 08:00:00',
                    'ThoiGianKetThuc' => '2025-06-20 16:00:00',
                    'TrangThai' => 3,
                    'RegisteredCount' => 180,
                    'Banner' => 'https://images.unsplash.com/photo-1615461066841-6116ecdccd04?q=80&w=200&auto=format&fit=crop'
                ],
                (object)[
                    'Id' => 102,
                    'TenChuongTrinh' => 'Nối dài sự sống',
                    'DiaChi' => 'Trường Đại học Bách Khoa Hà Nội',
                    'ThoiGianBatDau' => '2025-07-15 07:30:00',
                    'ThoiGianKetThuc' => '2025-07-15 12:00:00',
                    'TrangThai' => 1,
                    'RegisteredCount' => null,
                    'Banner' => 'https://images.unsplash.com/photo-1579684389782-64d84b5e901a?q=80&w=200&auto=format&fit=crop'
                ],
                (object)[
                    'Id' => 103,
                    'TenChuongTrinh' => 'Hiến máu nhân đạo đợt 3',
                    'DiaChi' => $donVi->TenDonVi . ', Hà Nội',
                    'ThoiGianBatDau' => '2025-07-25 08:00:00',
                    'ThoiGianKetThuc' => '2025-07-25 15:00:00',
                    'TrangThai' => 2,
                    'RegisteredCount' => 50,
                    'Banner' => 'https://images.unsplash.com/photo-1584515933487-780216b26b5d?q=80&w=200&auto=format&fit=crop'
                ]
            ]);
        } else {
            foreach ($programs as $prog) {
                $prog->RegisteredCount = DB::table('DangKyHienMau')
                    ->where('ChuongTrinhId', $prog->Id)
                    ->whereNull('deleted_at')
                    ->count();
                $prog->Banner = 'https://images.unsplash.com/photo-1584515933487-780216b26b5d?q=80&w=200&auto=format&fit=crop';
            }
        }

        $nextProgram = DB::table('ChuongTrinhHienMau')
            ->where('DonViToChucId', $donVi->Id)
            ->whereNull('deleted_at')
            ->whereIn('TrangThai', [2, 3])
            ->where('ThoiGianBatDau', '>=', now())
            ->orderBy('ThoiGianBatDau', 'asc')
            ->first();

        if (!$nextProgram) {
            $nextProgram = (object)[
                'ThoiGianBatDau' => '2025-06-20 08:00:00',
                'ThoiGianKetThuc' => '2025-06-20 16:00:00',
                'DiaChi' => 'Viện Huyết học - Truyền máu TW',
            ];
        }

        return view('frontend.don-vi-to-chuc.dashboard', compact('user', 'donVi', 'stats', 'programs', 'nextProgram'));
    }

    public function chuongTrinh(Request $request)
    {
        if (!$request->session()->has('admin_user')) {
            return redirect()->route('dang-nhap');
        }

        $adminSession = $request->session()->get('admin_user');
        $userId = $adminSession['id'];

        $user = DB::table('NguoiDung as nd')
            ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
            ->select('nd.*', 'vt.TenVaiTro')
            ->where('nd.Id', $userId)
            ->first();

        if (!$user || $user->TenVaiTro !== 'Đơn vị tổ chức') {
            return redirect()->route('dang-nhap')->withErrors(['email_or_phone' => 'Tài khoản không có quyền truy cập vai trò tổ chức.']);
        }

        $donVi = DB::table('DonViToChuc')->where('OwnerUserId', $user->Id)->first();
        if (!$donVi) {
            $donVi = DB::table('DonViToChuc')->where('Email', $user->Email)->first();
        }
        if (!$donVi) {
            $donVi = DB::table('DonViToChuc')->where('SoDienThoai', $user->SoDienThoai)->first();
        }

        if (!$donVi) {
            $request->session()->forget('admin_user');
            return redirect()->route('dang-nhap')->withErrors(['email_or_phone' => 'Tài khoản đơn vị tổ chức không thuộc về bất kỳ đơn vị nào.']);
        }

        // Calculate dynamic stats
        $choDuyetCount = DB::table('ChuongTrinhHienMau')
            ->where('DonViToChucId', $donVi->Id)
            ->whereNull('deleted_at')
            ->where('TrangThai', 1)
            ->count();

        $sapDienRaCount = DB::table('ChuongTrinhHienMau')
            ->where('DonViToChucId', $donVi->Id)
            ->whereNull('deleted_at')
            ->whereIn('TrangThai', [2, 3])
            ->count();

        $registeredCount = DB::table('DangKyHienMau')
            ->join('ChuongTrinhHienMau', 'DangKyHienMau.ChuongTrinhId', '=', 'ChuongTrinhHienMau.Id')
            ->where('ChuongTrinhHienMau.DonViToChucId', $donVi->Id)
            ->whereNull('DangKyHienMau.deleted_at')
            ->count();

        $stats = [
            'cho_duyet' => $choDuyetCount,
            'sap_dien_ra' => $sapDienRaCount,
            'dang_ky' => $registeredCount,
        ];

        // Query organization programs with filters
        $query = DB::table('ChuongTrinhHienMau as ct')
            ->where('ct.DonViToChucId', $donVi->Id)
            ->whereNull('ct.deleted_at');

        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('ct.TenChuongTrinh', 'like', "%{$keyword}%")
                  ->orWhere('ct.DiaChi', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('trang_thai')) {
            $status = $request->get('trang_thai');
            if ($status === 'Đã duyệt') {
                $query->where('ct.TrangThai', 2);
            } elseif ($status === 'Chờ duyệt') {
                $query->where('ct.TrangThai', 1);
            } elseif ($status === 'Đang diễn ra') {
                $query->where('ct.TrangThai', 3);
            }
        }

        if ($request->filled('thoi_gian')) {
            $time = $request->get('thoi_gian');
            if ($time === 'Tháng này') {
                $query->whereMonth('ct.ThoiGianBatDau', now()->month)
                      ->whereYear('ct.ThoiGianBatDau', now()->year);
            } elseif ($time === 'Tháng sau') {
                $nextMonth = now()->addMonth();
                $query->whereMonth('ct.ThoiGianBatDau', $nextMonth->month)
                      ->whereYear('ct.ThoiGianBatDau', $nextMonth->year);
            }
        }

        $programs = $query->orderBy('ct.ThoiGianBatDau', 'asc')->get();

        foreach ($programs as $prog) {
            $prog->RegisteredCount = DB::table('DangKyHienMau')
                ->where('ChuongTrinhId', $prog->Id)
                ->whereNull('deleted_at')
                ->count();
            if (empty($prog->Banner) || strpos($prog->Banner, 'linear-gradient') !== false) {
                $prog->Banner = 'https://images.unsplash.com/photo-1584515933487-780216b26b5d?q=80&w=200&auto=format&fit=crop';
            }
        }

        return view('frontend.don-vi-to-chuc.chuong-trinh', compact('user', 'donVi', 'stats', 'programs'));
    }

    public function storeChuongTrinh(Request $request)
    {
        if (!$request->session()->has('admin_user')) {
            return redirect()->route('dang-nhap');
        }

        $adminSession = $request->session()->get('admin_user');
        $userId = $adminSession['id'];

        $user = DB::table('NguoiDung as nd')
            ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
            ->select('nd.*', 'vt.TenVaiTro')
            ->where('nd.Id', $userId)
            ->first();

        if (!$user || $user->TenVaiTro !== 'Đơn vị tổ chức') {
            return redirect()->route('dang-nhap')->withErrors(['email_or_phone' => 'Tài khoản không có quyền truy cập vai trò tổ chức.']);
        }

        $donVi = DB::table('DonViToChuc')->where('OwnerUserId', $user->Id)->first();
        if (!$donVi) {
            $donVi = DB::table('DonViToChuc')->where('Email', $user->Email)->first();
        }
        if (!$donVi) {
            $donVi = DB::table('DonViToChuc')->where('SoDienThoai', $user->SoDienThoai)->first();
        }

        if (!$donVi) {
            $request->session()->forget('admin_user');
            return redirect()->route('dang-nhap')->withErrors(['email_or_phone' => 'Tài khoản đơn vị tổ chức không thuộc về bất kỳ đơn vị nào.']);
        }

        $request->validate([
            'TenChuongTrinh' => 'required|string|max:500',
            'MoTa' => 'required|string',
            'BannerFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'DiaChi' => 'required|string|max:500',
            'BanDo' => 'nullable|string|max:1000',
            'ThoiGianBatDau' => 'required|date|after_or_equal:today',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiGianMoDangKy' => 'required|date|before:ThoiGianBatDau',
            'SoLuongDuKien' => 'required|integer|min:1',
        ], [
            'TenChuongTrinh.required' => 'Tên chương trình không được để trống.',
            'TenChuongTrinh.string' => 'Tên chương trình phải là chuỗi ký tự.',
            'TenChuongTrinh.max' => 'Tên chương trình không vượt quá 500 ký tự.',
            'MoTa.required' => 'Mô tả không được để trống.',
            'BannerFile.image' => 'Banner phải là định dạng hình ảnh.',
            'BannerFile.mimes' => 'Banner phải có định dạng: jpeg, png, jpg, gif, svg, webp.',
            'BannerFile.max' => 'Kích thước banner không vượt quá 2MB.',
            'DiaChi.required' => 'Địa điểm không được để trống.',
            'DiaChi.max' => 'Địa điểm không vượt quá 500 ký tự.',
            'ThoiGianBatDau.required' => 'Thời gian bắt đầu không được để trống.',
            'ThoiGianBatDau.date' => 'Thời gian bắt đầu không đúng định dạng ngày giờ.',
            'ThoiGianBatDau.after_or_equal' => 'Thời gian bắt đầu không được nhỏ hơn ngày hôm nay.',
            'ThoiGianKetThuc.required' => 'Thời gian kết thúc không được để trống.',
            'ThoiGianKetThuc.date' => 'Thời gian kết thúc không đúng định dạng ngày giờ.',
            'ThoiGianKetThuc.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
            'ThoiGianMoDangKy.required' => 'Thời gian mở đăng ký không được để trống.',
            'ThoiGianMoDangKy.date' => 'Thời gian mở đăng ký không đúng định dạng ngày giờ.',
            'ThoiGianMoDangKy.before' => 'Thời gian mở đăng ký phải trước thời gian bắt đầu.',
            'SoLuongDuKien.required' => 'Số lượng người tham gia dự kiến không được để trống.',
            'SoLuongDuKien.integer' => 'Số lượng người tham gia dự kiến phải là số nguyên.',
            'SoLuongDuKien.min' => 'Số lượng người tham gia dự kiến phải lớn hơn 0.',
        ]);

        $banner = 'https://images.unsplash.com/photo-1584515933487-780216b26b5d?q=80&w=200&auto=format&fit=crop';
        if ($request->hasFile('BannerFile')) {
            $file = $request->file('BannerFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/banners');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            // Capture file metadata before moving the temporary file
            $fileOriginalName = $file->getClientOriginalName();
            $fileMime = $file->getClientMimeType();
            $fileSize = $file->getSize();

            $file->move($destinationPath, $filename);
            $banner = '/uploads/banners/' . $filename;

            // Optional: Insert into TapTin
            DB::table('TapTin')->insert([
                'TenFile' => $fileOriginalName,
                'DuongDan' => $banner,
                'LoaiFile' => $fileMime,
                'KichThuoc' => $fileSize,
                'NguoiTaiLenId' => $user->Id,
                'NgayTaiLen' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('ChuongTrinhHienMau')->insert([
            'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
            'TenChuongTrinh' => $request->input('TenChuongTrinh'),
            'MoTa' => $request->input('MoTa'),
            'Banner' => $banner,
            'DonViToChucId' => $donVi->Id,
            'DiaChi' => $request->input('DiaChi'),
            'BanDo' => $request->input('BanDo'),
            'ThoiGianBatDau' => $request->input('ThoiGianBatDau'),
            'ThoiGianKetThuc' => $request->input('ThoiGianKetThuc'),
            'ThoiGianMoDangKy' => $request->input('ThoiGianMoDangKy'),
            'DangDienRa' => 0,
            'SoLuongDuKien' => $request->input('SoLuongDuKien'),
            'TrangThai' => 1, // Chờ duyệt
            'NguoiTaoId' => $user->Id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('don-vi-to-chuc.chuong-trinh')->with('success', 'Đề xuất chương trình hiến máu thành công.');
    }
}
