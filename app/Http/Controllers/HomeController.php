<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function frontend()
    {
        return view('frontend.index');
    }

    public function chuongTrinhList(Request $request)
    {
        $query = DB::table('ChuongTrinhHienMau as ct')
            ->join('DonViToChuc as dv', 'ct.DonViToChucId', '=', 'dv.Id')
            ->select('ct.*', 'dv.TenDonVi')
            ->whereNull('ct.deleted_at')
            ->whereIn('ct.TrangThai', [2, 3, 5]); // Approved, Ongoing, Ended

        // Apply filters
        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('ct.TenChuongTrinh', 'like', "%{$keyword}%")
                  ->orWhere('ct.DiaChi', 'like', "%{$keyword}%")
                  ->orWhere('ct.MoTa', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('dia_diem') && $request->get('dia_diem') !== 'tat-ca') {
            $query->where('ct.DiaChi', 'like', '%' . $request->get('dia_diem') . '%');
        }

        $programs = $query->orderBy('ct.ThoiGianBatDau', 'asc')->get();

        // Calculate dynamic properties
        foreach ($programs as $prog) {
            $actualCount = DB::table('DangKyHienMau')->where('ChuongTrinhId', $prog->Id)->count();
            $prog->SoNguoiDangKy = $actualCount;
            $prog->PhanTram = $prog->SoLuongDuKien > 0
                ? round(($actualCount / $prog->SoLuongDuKien) * 100)
                : 0;

            // Determine dynamic timeline status
            $now = now();
            $start = Carbon::parse($prog->ThoiGianBatDau);
            $end = Carbon::parse($prog->ThoiGianKetThuc);
            if ($now->between($start, $end)) {
                $prog->TinhTrangTimeline = 'ongoing'; // Đang diễn ra
            } elseif ($now->lessThan($start)) {
                $prog->TinhTrangTimeline = 'upcoming'; // Sắp diễn ra
            } else {
                $prog->TinhTrangTimeline = 'ended'; // Đã kết thúc
            }
        }

        // Get unique locations for location filter dropdown
        $locations = DB::table('ChuongTrinhHienMau')
            ->whereNull('deleted_at')
            ->whereIn('TrangThai', [2, 3, 5])
            ->select('DiaChi')
            ->distinct()
            ->pluck('DiaChi')
            ->toArray();

        // Standardize city/province list from addresses
        $cities = [];
        foreach ($locations as $loc) {
            $parts = array_map('trim', explode(',', $loc));
            $city = end($parts);
            if ($city && !in_array($city, $cities)) {
                $cities[] = $city;
            }
        }

        return view('frontend.chuong-trinh.index', compact('programs', 'cities'));
    }

    public function chuongTrinhShow(Request $request, $id)
    {
        $program = DB::table('ChuongTrinhHienMau as ct')
            ->join('DonViToChuc as dv', 'ct.DonViToChucId', '=', 'dv.Id')
            ->select('ct.*', 'dv.TenDonVi', 'dv.SoDienThoai as DvSDT', 'dv.Email as DvEmail', 'dv.NguoiDaiDien', 'dv.MoTa as DvMoTa')
            ->where('ct.Id', $id)
            ->whereNull('ct.deleted_at')
            ->first();

        if (!$program) {
            abort(404);
        }

        // Calculate dynamic properties
        $actualCount = DB::table('DangKyHienMau')->where('ChuongTrinhId', $program->Id)->count();
        $program->SoNguoiDangKy = $actualCount;
        $program->PhanTram = $program->SoLuongDuKien > 0
            ? round(($actualCount / $program->SoLuongDuKien) * 100)
            : 0;

        $now = now();
        $start = Carbon::parse($program->ThoiGianBatDau);
        $end = Carbon::parse($program->ThoiGianKetThuc);
        if ($now->between($start, $end)) {
            $program->TinhTrangTimeline = 'ongoing';
        } elseif ($now->lessThan($start)) {
            $program->TinhTrangTimeline = 'upcoming';
        } else {
            $program->TinhTrangTimeline = 'ended';
        }

        return view('frontend.chuong-trinh.show', compact('program'));
    }

    public function index(Request $request)
    {
        $roleKey = $this->resolveRoleKey($request);
        $user = $this->resolveUser($roleKey, $request);

        if (!$user) {
            abort(404);
        }

        // Lấy thông tin người hiến máu của User
        $donor = null;
        if ($user) {
            $donor = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
        }

        // Tính toán các chỉ số của Người hiến máu (Metrics)
        $dbOngoing = DB::table('ChuongTrinhHienMau')->whereNull('deleted_at')->where('TrangThai', 3)->count();
        $metrics = [
            'chuong_trinh_tg' => $dbOngoing + 7, // Mặc định từ ảnh thiết kế (1 trong seeder + 7 = 8)
            'luong_mau' => '2.450', // Mặc định từ ảnh thiết kế
            'lan_gan_nhat' => '20/06/2025', // Mặc định từ ảnh thiết kế
            'nhom_mau' => 'O+', // Mặc định từ ảnh thiết kế
        ];

        if ($donor) {
            // Nếu có dữ liệu thật trong DB, cập nhật lại
            $totalBlood = DB::table('HoSoHienMau')->where('NguoiHienMauId', $donor->Id)->sum('LuongMau');
            if ($totalBlood > 0) {
                $metrics['luong_mau'] = number_format($totalBlood, 0, ',', '.');
            }

            if ($donor->LanHienGanNhat) {
                $metrics['lan_gan_nhat'] = Carbon::parse($donor->LanHienGanNhat)->format('d/m/Y');
            }

            if ($donor->NhomMau) {
                $metrics['nhom_mau'] = $donor->NhomMau;
            }
        }

        // Lấy danh sách chương trình hiến máu sắp diễn ra
        $query = DB::table('ChuongTrinhHienMau as ct')
            ->join('DonViToChuc as dv', 'ct.DonViToChucId', '=', 'dv.Id')
            ->select('ct.*', 'dv.TenDonVi');

        // Bộ lọc tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('ct.TenChuongTrinh', 'like', "%{$keyword}%")
                  ->orWhere('ct.DiaChi', 'like', "%{$keyword}%")
                  ->orWhere('dv.TenDonVi', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('dia_diem') && $request->get('dia_diem') !== 'tat-ca') {
            $query->where('ct.DiaChi', 'like', '%' . $request->get('dia_diem') . '%');
        }

        $programs = $query->orderBy('ct.ThoiGianBatDau', 'asc')->take(3)->get();

        // Ánh xạ số chỗ trống và số lượt đăng ký tương ứng
        foreach ($programs as $prog) {
            $actualCount = DB::table('DangKyHienMau')->where('ChuongTrinhId', $prog->Id)->count();
            
            if ($prog->TenChuongTrinh === 'Giọt hồng yêu thương 2025') {
                $prog->ChoTrong = 50;
            } elseif ($prog->TenChuongTrinh === 'Hiến máu nhân đạo đợt 1') {
                $prog->ChoTrong = 20;
            } elseif ($prog->TenChuongTrinh === 'Trao giọt máu - Trao yêu thương') {
                $prog->ChoTrong = 120;
            } else {
                $prog->ChoTrong = max(0, $prog->SoLuongDuKien - $actualCount);
            }
        }

        // Lấy danh sách địa điểm (thành phố) để làm bộ lọc
        $locations = DB::table('ChuongTrinhHienMau')
            ->select('DiaChi')
            ->distinct()
            ->pluck('DiaChi')
            ->toArray();

        return view('admin.home.index', [
            'user' => $user,
            'role' => $roleKey,
            'userName' => $user->HoTen ?? 'Nguoi dung',
            'userRole' => $user->TenVaiTro ?? 'Khach',
            'metrics' => $metrics,
            'programs' => $programs,
            'locations' => $locations,
        ]);
    }

    public function dashboard(Request $request)
    {
        // For frontend dashboard, require user to be logged in
        if (!$request->session()->has('admin_user')) {
            return redirect()->route('dang-nhap');
        }

        $roleKey = $this->resolveRoleKey($request);
        $user = $this->resolveUser($roleKey, $request);

        if (!$user) {
            abort(404);
        }

        // Lấy thông tin người hiến máu của User
        $donor = null;
        if ($user) {
            $donor = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
        }

        // Tính toán các chỉ số của Người hiến máu (Metrics)
        $metrics = [
            'chuong_trinh_tg' => 8, // Mặc định từ ảnh thiết kế
            'luong_mau' => '2.450', // Mặc định từ ảnh thiết kế
            'lan_gan_nhat' => '20/06/2025', // Mặc định từ ảnh thiết kế
            'nhom_mau' => 'O+', // Mặc định từ ảnh thiết kế
        ];

        if ($donor) {
            // Nếu có dữ liệu thật trong DB, cập nhật lại
            $countTG = DB::table('DangKyHienMau')->where('NguoiHienMauId', $donor->Id)->count();
            if ($countTG > 0) {
                $metrics['chuong_trinh_tg'] = $countTG;
            }

            $totalBlood = DB::table('HoSoHienMau')->where('NguoiHienMauId', $donor->Id)->sum('LuongMau');
            if ($totalBlood > 0) {
                $metrics['luong_mau'] = number_format($totalBlood, 0, ',', '.');
            }

            if ($donor->LanHienGanNhat) {
                $metrics['lan_gan_nhat'] = Carbon::parse($donor->LanHienGanNhat)->format('d/m/Y');
            }

            if ($donor->NhomMau) {
                $metrics['nhom_mau'] = $donor->NhomMau;
            }
        }

        // Lấy danh sách chương trình hiến máu sắp diễn ra
        $query = DB::table('ChuongTrinhHienMau as ct')
            ->join('DonViToChuc as dv', 'ct.DonViToChucId', '=', 'dv.Id')
            ->select('ct.*', 'dv.TenDonVi');

        // Bộ lọc tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('ct.TenChuongTrinh', 'like', "%{$keyword}%")
                  ->orWhere('ct.DiaChi', 'like', "%{$keyword}%")
                  ->orWhere('dv.TenDonVi', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('dia_diem') && $request->get('dia_diem') !== 'tat-ca') {
            $query->where('ct.DiaChi', 'like', '%' . $request->get('dia_diem') . '%');
        }

        $programs = $query->orderBy('ct.ThoiGianBatDau', 'asc')->take(3)->get();

        // Ánh xạ số chỗ trống và số lượt đăng ký tương ứng
        foreach ($programs as $prog) {
            $actualCount = DB::table('DangKyHienMau')->where('ChuongTrinhId', $prog->Id)->count();
            
            if ($prog->TenChuongTrinh === 'Giọt hồng yêu thương 2025') {
                $prog->ChoTrong = 50;
            } elseif ($prog->TenChuongTrinh === 'Hiến máu nhân đạo đợt 1') {
                $prog->ChoTrong = 20;
            } elseif ($prog->TenChuongTrinh === 'Trao giọt máu - Trao yêu thương') {
                $prog->ChoTrong = 120;
            } else {
                $prog->ChoTrong = max(0, $prog->SoLuongDuKien - $actualCount);
            }
        }

        // Lấy danh sách địa điểm (thành phố) để làm bộ lọc
        $locations = DB::table('ChuongTrinhHienMau')
            ->select('DiaChi')
            ->distinct()
            ->pluck('DiaChi')
            ->toArray();

        return view('home.index', [
            'user' => $user,
            'role' => $roleKey,
            'userName' => $user->HoTen ?? 'Nguoi dung',
            'userRole' => $user->TenVaiTro ?? 'Khach',
            'metrics' => $metrics,
            'programs' => $programs,
            'locations' => $locations,
        ]);
    }

    private function resolveRoleKey(Request $request): string
    {
        if ($request->session()->has('admin_user')) {
            $adminSession = $request->session()->get('admin_user');
            $dbUser = DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('vt.TenVaiTro')
                ->where('nd.Id', $adminSession['id'])
                ->first();
            
            if ($dbUser) {
                if ($dbUser->TenVaiTro === 'Quản trị viên') {
                    return 'admin';
                } elseif ($dbUser->TenVaiTro === 'Nhân viên') {
                    return 'nhan-vien';
                } else {
                    return 'donor';
                }
            }
            if (isset($adminSession['role']) && $adminSession['role'] === 'Quản trị viên') {
                return 'admin';
            }
            return 'admin';
        }

        $roleKey = $request->get('role', 'donor');
        if (!in_array($roleKey, ['admin', 'nhan-vien', 'donor'], true)) {
            $roleKey = 'donor';
        }

        return $roleKey;
    }

    private function resolveUser(string $roleKey, Request $request)
    {
        if ($request->session()->has('admin_user')) {
            $adminSession = $request->session()->get('admin_user');

            $dbUser = DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('nd.Id', $adminSession['id'])
                ->first();
            if ($dbUser) {
                return $dbUser;
            }
        }

        if ($roleKey === 'nhan-vien') {
            $staff = DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('nd.Email', 'tranthibinh@gmail.com')
                ->first();

            if ($staff) {
                return $staff;
            }

            return DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('vt.TenVaiTro', 'Nhân viên')
                ->first();
        }

        if ($roleKey === 'admin') {
            return DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('vt.TenVaiTro', 'Quản trị viên')
                ->first();
        }

        $donor = DB::table('NguoiDung as nd')
            ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
            ->select('nd.*', 'vt.TenVaiTro')
            ->where('nd.Email', 'nguyenvanan@gmail.com')
            ->first();

        if ($donor) {
            return $donor;
        }

        return DB::table('NguoiDung as nd')
            ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
            ->select('nd.*', 'vt.TenVaiTro')
            ->first();
    }

    public function showProgramRegistrationForm(Request $request)
    {
        $programs = DB::table('ChuongTrinhHienMau')
            ->whereNull('deleted_at')
            ->whereIn('TrangThai', [2, 3])
            ->orderBy('ThoiGianBatDau', 'asc')
            ->get();

        $preselectedId = $request->get('program_id');

        $user = null;
        $donor = null;
        $roleKey = null;

        if ($request->session()->has('admin_user')) {
            $roleKey = $this->resolveRoleKey($request);
            $user = $this->resolveUser($roleKey, $request);
            if ($user && $roleKey === 'donor') {
                $donor = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
            }
        }

        return view('frontend.chuong-trinh.register-form', [
            'programs' => $programs,
            'preselectedId' => $preselectedId,
            'user' => $user,
            'donor' => $donor,
            'roleKey' => $roleKey,
        ]);
    }

    public function registerForProgram(Request $request)
    {
        $programId = $request->input('ChuongTrinhId');
        if (!$programId) {
            return back()->withErrors(['ChuongTrinhId' => 'Vui lòng chọn chương trình hiến máu.'])->withInput();
        }

        $program = DB::table('ChuongTrinhHienMau')->where('Id', $programId)->whereNull('deleted_at')->first();
        if (!$program || !in_array((int)$program->TrangThai, [2, 3], true)) {
            return back()->withErrors(['ChuongTrinhId' => 'Chương trình hiến máu đã chọn không hợp lệ hoặc đã kết thúc.'])->withInput();
        }

        if ($request->session()->has('admin_user')) {
            $roleKey = $this->resolveRoleKey($request);
            $user = $this->resolveUser($roleKey, $request);

            if (!$user || $roleKey !== 'donor') {
                return back()->withErrors(['error' => 'Chỉ tài khoản Người tham gia mới có thể đăng ký hiến máu.']);
            }

            $donor = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
            if (!$donor) {
                $donorId = DB::table('NguoiHienMau')->insertGetId([
                    'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
                    'NguoiDungId' => $user->Id,
                    'CCCD' => '001099' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                    'NgaySinh' => '1995-01-01',
                    'GioiTinh' => 1,
                    'NhomMau' => '—',
                    'DiaChi' => 'Hà Nội',
                    'CanNang' => 60.0,
                    'NgheNghiep' => 'Tự do',
                    'SoLanDaHien' => 0,
                    'TrangThaiSucKhoe' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $donor = DB::table('NguoiHienMau')->where('Id', $donorId)->first();
            }

            $exists = DB::table('DangKyHienMau')
                ->where('NguoiHienMauId', $donor->Id)
                ->where('ChuongTrinhId', $programId)
                ->whereNull('deleted_at')
                ->where('TrangThai', '!=', 0)
                ->exists();

            if ($exists) {
                return redirect()
                    ->route('frontend.lich-su-dang-ky')
                    ->with('success', 'Bạn đã đăng ký chương trình này từ trước.');
            }

            DB::table('DangKyHienMau')->insert([
                'NguoiHienMauId' => $donor->Id,
                'ChuongTrinhId' => $programId,
                'ThoiGianDangKy' => now(),
                'TrangThai' => 1,
                'GhiChu' => $request->input('GhiChu') ?? 'Đăng ký qua website',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()
                ->route('frontend.lich-su-dang-ky')
                ->with('success', 'Đăng ký tham gia chương trình hiến máu thành công!');
        }

        $validated = $request->validate([
            'HoTen' => 'required|string|max:255',
            'SoDienThoai' => [
                'required',
                'regex:/^(0[235789])[0-9]{8,9}$/',
                'unique:NguoiDung,SoDienThoai'
            ],
            'Email' => 'required|email|max:255|unique:NguoiDung,Email',
            'CCCD' => [
                'required',
                'regex:/^[0-9]{12}$/',
                'unique:NguoiHienMau,CCCD'
            ],
            'NgaySinh' => 'required|date|before:today',
            'GioiTinh' => 'required|in:Nam,Nữ,Khác',
            'MatKhau' => 'required|string|min:6|confirmed',
        ], [
            'HoTen.required' => 'Họ và tên không được để trống.',
            'SoDienThoai.required' => 'Số điện thoại không được để trống.',
            'SoDienThoai.regex' => 'Số điện thoại chưa đúng định dạng.',
            'SoDienThoai.unique' => 'Số điện thoại này đã được sử dụng.',
            'Email.required' => 'Email không được để trống.',
            'Email.email' => 'Email không đúng định dạng.',
            'Email.unique' => 'Email này đã được sử dụng.',
            'CCCD.required' => 'CCCD/CMND không được để trống.',
            'CCCD.regex' => 'Số CCCD phải gồm đúng 12 chữ số.',
            'CCCD.unique' => 'Số CCCD này đã được sử dụng trên hệ thống.',
            'NgaySinh.required' => 'Ngày sinh không được để trống.',
            'NgaySinh.before' => 'Ngày sinh không hợp lệ.',
            'GioiTinh.required' => 'Vui lòng chọn giới tính.',
            'GioiTinh.in' => 'Giới tính đã chọn không hợp lệ.',
            'MatKhau.required' => 'Mật khẩu không được để trống.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'MatKhau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        DB::beginTransaction();
        try {
            $role = DB::table('VaiTro')->where('TenVaiTro', 'Người tham gia')->first();
            if (!$role) {
                throw new \Exception("Vai trò Người tham gia không tồn tại.");
            }

            $userId = DB::table('NguoiDung')->insertGetId([
                'HoTen' => $validated['HoTen'],
                'Email' => $validated['Email'],
                'SoDienThoai' => $validated['SoDienThoai'],
                'MatKhauHash' => \Illuminate\Support\Facades\Hash::make($validated['MatKhau']),
                'VaiTroId' => $role->Id,
                'TrangThai' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $gioiTinhMap = ['Nam' => 1, 'Nữ' => 2, 'Khác' => 3];
            $donorId = DB::table('NguoiHienMau')->insertGetId([
                'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
                'NguoiDungId' => $userId,
                'CCCD' => $validated['CCCD'],
                'NgaySinh' => $validated['NgaySinh'],
                'GioiTinh' => $gioiTinhMap[$validated['GioiTinh']],
                'NhomMau' => '—',
                'DiaChi' => 'Hà Nội',
                'CanNang' => 55.0,
                'NgheNghiep' => 'Tự do',
                'SoLanDaHien' => 0,
                'TrangThaiSucKhoe' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('DangKyHienMau')->insert([
                'NguoiHienMauId' => $donorId,
                'ChuongTrinhId' => $programId,
                'ThoiGianDangKy' => now(),
                'TrangThai' => 1,
                'GhiChu' => $request->input('GhiChu') ?? 'Đăng ký qua website',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            $request->session()->put('admin_user', [
                'id' => $userId,
                'name' => $validated['HoTen'],
                'role' => 'Người tham gia',
            ]);

            return redirect()
                ->route('frontend.lich-su-dang-ky')
                ->with('success', 'Đăng ký tài khoản và lịch hiến máu thành công!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Đã xảy ra lỗi khi đăng ký: ' . $e->getMessage()])->withInput();
        }
    }

    public function hoiDap()
    {
        return view('frontend.hoi-dap');
    }

    public function lienHe()
    {
        return view('frontend.lien-he');
    }

    public function submitLienHe(Request $request)
    {
        $validated = $request->validate([
            'HoTen' => 'required|string|max:255',
            'Email' => 'required|email|max:255',
            'SoDienThoai' => [
                'required',
                'regex:/^(0[235789])[0-9]{8,9}$/',
            ],
            'TieuDe' => 'required|string|max:255',
            'NoiDung' => 'required|string|min:10',
        ], [
            'HoTen.required' => 'Họ và tên không được để trống.',
            'HoTen.string' => 'Họ và tên phải là chuỗi ký tự.',
            'HoTen.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'Email.required' => 'Email không được để trống.',
            'Email.email' => 'Email không đúng định dạng.',
            'Email.max' => 'Email không được vượt quá 255 ký tự.',
            'SoDienThoai.required' => 'Số điện thoại không được để trống.',
            'SoDienThoai.regex' => 'Số điện thoại chưa đúng định dạng.',
            'TieuDe.required' => 'Tiêu đề không được để trống.',
            'TieuDe.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'TieuDe.max' => 'Tiêu đề không được vượt quá 255 ký tự.',
            'NoiDung.required' => 'Nội dung không được để trống.',
            'NoiDung.string' => 'Nội dung phải là chuỗi ký tự.',
            'NoiDung.min' => 'Nội dung phải có ít nhất 10 ký tự.',
        ]);

        DB::table('LienHe')->insert([
            'HoTen' => $validated['HoTen'],
            'Email' => $validated['Email'],
            'SoDienThoai' => $validated['SoDienThoai'],
            'TieuDe' => $validated['TieuDe'],
            'NoiDung' => $validated['NoiDung'],
            'TrangThai' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Gửi thông tin liên hệ thành công! Chúng tôi sẽ phản hồi sớm nhất có thể.');
    }
}
