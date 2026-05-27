<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $roleKey = $this->resolveRoleKey($request);
        $user = $this->resolveUser($roleKey, $request);

        if (!$user) {
            abort(404);
        }

        $healthRecord = DB::table('HoSoSucKhoe as hssk')
            ->join('DangKyHienMau as dkhm', 'hssk.DangKyId', '=', 'dkhm.Id')
            ->join('NguoiHienMau as nhm', 'dkhm.NguoiHienMauId', '=', 'nhm.Id')
            ->select('hssk.*')
            ->where('nhm.NguoiDungId', $user->Id)
            ->orderBy('hssk.ThoiGianKham', 'desc')
            ->first();

        if (!$healthRecord) {
            $healthRecord = (object) [
                'HuyetAp' => '120/80',
                'NhipTim' => 75,
                'NhietDo' => 36.5,
                'CanNang' => 68.0,
                'Hemoglobin' => 14.5,
                'KetQua' => 1,
                'LyDoTuChoi' => '—',
                'Nhommau' => 'O (Rh+)',
                'NguoiKham' => 'ThS. BS. Nguyễn Văn Nghĩa',
                'ThoiGianKham' => now()->subDays(3)->format('Y-m-d H:i:s')
            ];
        } else {
            $healthRecord->ThoiGianKham = \Carbon\Carbon::parse($healthRecord->ThoiGianKham)->format('Y-m-d H:i:s');
        }

        $registrations = [];
        if ($roleKey === 'donor') {
            $donor = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
            if ($donor) {
                $registrations = DB::table('DangKyHienMau as dk')
                    ->join('ChuongTrinhHienMau as ct', 'dk.ChuongTrinhId', '=', 'ct.Id')
                    ->join('DonViToChuc as dv', 'ct.DonViToChucId', '=', 'dv.Id')
                    ->leftJoin('HoSoHienMau as hs', function($join) {
                        $join->on('hs.NguoiHienMauId', '=', 'dk.NguoiHienMauId')
                             ->on('hs.ChuongTrinhId', '=', 'dk.ChuongTrinhId')
                             ->whereNull('hs.deleted_at');
                    })
                    ->select('dk.*', 'ct.TenChuongTrinh', 'ct.ThoiGianBatDau', 'ct.ThoiGianKetThuc', 'ct.DiaChi', 'dv.TenDonVi', 'hs.Id as HoSoId')
                    ->where('dk.NguoiHienMauId', $donor->Id)
                    ->whereNull('dk.deleted_at')
                    ->orderBy('dk.ThoiGianDangKy', 'desc')
                    ->get();
            }
        }

        return view('admin.profile.edit', [
            'user' => $user,
            'role' => $roleKey,
            'userName' => $user->HoTen ?? 'Nguoi dung',
            'userRole' => $user->TenVaiTro ?? 'Khach',
            'healthRecord' => $healthRecord,
            'registrations' => $registrations,
        ]);
    }

    public function update(Request $request)
    {
        $roleKey = $this->resolveRoleKey($request);
        $user = $this->resolveUser($roleKey, $request);

        if (!$user) {
            abort(404);
        }

        $validated = $request->validate([
            'HoTen' => 'required|string|max:255',
            'Email' => 'required|email|max:255|unique:NguoiDung,Email,' . $user->Id . ',Id',
            'SoDienThoai' => [
                'required',
                'regex:/^(0[235789])[0-9]{8,9}$/',
                'unique:NguoiDung,SoDienThoai,' . $user->Id . ',Id',
            ],
            'Nhommau' => 'nullable|string|max:100',
            'HuyetAp' => 'nullable|string|max:20',
            'NhipTim' => 'nullable|integer|min:30|max:200',
            'NhietDo' => 'nullable|numeric|min:30|max:45',
            'CanNang' => 'nullable|numeric|min:30|max:200',
            'Hemoglobin' => 'nullable|numeric|min:5|max:25',
            'NguoiKham' => 'nullable|string|max:500',
        ], [
            'HoTen.required' => 'Ho ten khong duoc de trong.',
            'HoTen.string' => 'Ho ten phai la chuoi ky tu.',
            'HoTen.max' => 'Ho ten khong vuot qua 255 ky tu.',
            'Email.required' => 'Email khong duoc de trong.',
            'Email.email' => 'Email khong hop le.',
            'Email.max' => 'Email khong vuot qua 255 ky tu.',
            'Email.unique' => 'Email nay da duoc su dung.',
            'SoDienThoai.required' => 'So dien thoai khong duoc de trong.',
            'SoDienThoai.regex' => 'So dien thoai chua dung dinh dang.',
            'SoDienThoai.unique' => 'So dien thoai nay da duoc su dung.',
            'NhipTim.integer' => 'Nhip tim phai la so nguyen.',
            'NhipTim.min' => 'Nhip tim qua thap.',
            'NhipTim.max' => 'Nhip tim qua cao.',
            'NhietDo.numeric' => 'Nhiet do phai la so.',
            'CanNang.numeric' => 'Can nang phai la so.',
            'Hemoglobin.numeric' => 'Chi so Hemoglobin phai la so.',
        ]);

        DB::table('NguoiDung')
            ->where('Id', $user->Id)
            ->update([
                'HoTen' => $validated['HoTen'],
                'Email' => $validated['Email'],
                'SoDienThoai' => $validated['SoDienThoai'],
                'updated_at' => now(),
            ]);

        // Dynamically locate or insert NguoiHienMau record matching NguoiDungId
        $nhmId = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->value('Id');
        if (!$nhmId) {
            $nhmId = DB::table('NguoiHienMau')->insertGetId([
                'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
                'NguoiDungId' => $user->Id,
                'CCCD' => '001099' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                'NgaySinh' => '1995-01-01',
                'GioiTinh' => 1,
                'NhomMau' => $request->input('Nhommau', 'O'),
                'DiaChi' => 'Hà Nội',
                'CanNang' => $request->input('CanNang', 68.0),
                'NgheNghiep' => 'Tự do',
                'SoLanDaHien' => 0,
                'TrangThaiSucKhoe' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ], 'Id');
        }

        // Dynamically locate or insert DangKyHienMau record
        $dangKyId = DB::table('DangKyHienMau')->where('NguoiHienMauId', $nhmId)->value('Id');
        if (!$dangKyId) {
            $campaignId = DB::table('ChuongTrinhHienMau')->value('Id') ?? 1;
            $dangKyId = DB::table('DangKyHienMau')->insertGetId([
                'NguoiHienMauId' => $nhmId,
                'ChuongTrinhId' => $campaignId,
                'ThoiGianDangKy' => now(),
                'TrangThai' => 1,
                'GhiChu' => 'Hồ sơ tự cập nhật',
                'created_at' => now(),
                'updated_at' => now(),
            ], 'Id');
        }

        // Locate or insert/update HoSoSucKhoe data
        $hssk = DB::table('HoSoSucKhoe')->where('DangKyId', $dangKyId)->first();
        $hsskData = [
            'HuyetAp' => $request->input('HuyetAp', '120/80'),
            'NhipTim' => $request->input('NhipTim', 75),
            'NhietDo' => $request->input('NhietDo', 36.5),
            'CanNang' => $request->input('CanNang', 68.0),
            'Hemoglobin' => $request->input('Hemoglobin', 14.5),
            'KetQua' => 1,
            'LyDoTuChoi' => '—',
            'Nhommau' => $request->input('Nhommau', 'O (Rh+)'),
            'NguoiKham' => $request->input('NguoiKham', 'ThS. BS. Nguyễn Văn Nghĩa'),
            'ThoiGianKham' => now(),
            'updated_at' => now(),
        ];

        if ($hssk) {
            DB::table('HoSoSucKhoe')->where('Id', $hssk->Id)->update($hsskData);
        } else {
            $hsskData['DangKyId'] = $dangKyId;
            $hsskData['created_at'] = now();
            DB::table('HoSoSucKhoe')->insert($hsskData);
        }

        $adminSession = $request->session()->get('admin_user');
        if ($adminSession && (int) $adminSession['id'] === (int) $user->Id) {
            $request->session()->put('admin_user', [
                'id' => $user->Id,
                'name' => $validated['HoTen'],
                'role' => $adminSession['role'],
            ]);
        }

        return redirect()
            ->route('profile.edit', ['role' => $roleKey])
            ->with('success', 'Cập nhật thông tin cá nhân và hồ sơ sức khỏe thành công.');
    }

    public function updatePassword(Request $request)
    {
        $roleKey = $this->resolveRoleKey($request);
        $user = $this->resolveUser($roleKey, $request);

        if (!$user) {
            abort(404);
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui long nhap mat khau hien tai.',
            'current_password.string' => 'Mat khau hien tai khong hop le.',
            'new_password.required' => 'Vui long nhap mat khau moi.',
            'new_password.string' => 'Mat khau moi khong hop le.',
            'new_password.min' => 'Mat khau moi phai co it nhat 6 ky tu.',
            'new_password.confirmed' => 'Xac nhan mat khau moi khong khop.',
        ]);

        if (!Hash::check($validated['current_password'], $user->MatKhauHash)) {
            return back()
                ->withErrors(['current_password' => 'Mat khau hien tai khong dung.'])
                ->withInput();
        }

        DB::table('NguoiDung')
            ->where('Id', $user->Id)
            ->update([
                'MatKhauHash' => Hash::make($validated['new_password']),
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('profile.edit', ['role' => $roleKey])
            ->with('success_password', 'Doi mat khau thanh cong.');
    }

    public function registrationHistory(Request $request)
    {
        if (!$request->session()->has('admin_user')) {
            return redirect()->route('dang-nhap')->withErrors(['email_or_phone' => 'Vui lòng đăng nhập để xem lịch sử đăng ký.']);
        }

        $roleKey = $this->resolveRoleKey($request);
        $user = $this->resolveUser($roleKey, $request);

        if (!$user || $roleKey !== 'donor') {
            abort(403, 'Tài khoản không có quyền truy cập trang này.');
        }

        $registrations = [];
        $donor = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
        if ($donor) {
            $registrations = DB::table('DangKyHienMau as dk')
                ->join('ChuongTrinhHienMau as ct', 'dk.ChuongTrinhId', '=', 'ct.Id')
                ->join('DonViToChuc as dv', 'ct.DonViToChucId', '=', 'dv.Id')
                ->leftJoin('HoSoHienMau as hs', function($join) {
                    $join->on('hs.NguoiHienMauId', '=', 'dk.NguoiHienMauId')
                         ->on('hs.ChuongTrinhId', '=', 'dk.ChuongTrinhId')
                         ->whereNull('hs.deleted_at');
                })
                ->select('dk.*', 'ct.TenChuongTrinh', 'ct.ThoiGianBatDau', 'ct.ThoiGianKetThuc', 'ct.DiaChi', 'dv.TenDonVi', 'hs.Id as HoSoId')
                ->where('dk.NguoiHienMauId', $donor->Id)
                ->whereNull('dk.deleted_at')
                ->orderBy('dk.ThoiGianDangKy', 'desc')
                ->get();
        }

        return view('frontend.lich-su-dang-ky', [
            'user' => $user,
            'role' => $roleKey,
            'userName' => $user->HoTen ?? 'Người dùng',
            'userRole' => $user->TenVaiTro ?? 'Khách',
            'registrations' => $registrations,
        ]);
    }

    public function cancelRegistration(Request $request, $id)
    {
        if (!$request->session()->has('admin_user')) {
            return redirect()->route('dang-nhap');
        }

        $roleKey = $this->resolveRoleKey($request);
        $user = $this->resolveUser($roleKey, $request);

        if (!$user || $roleKey !== 'donor') {
            abort(403);
        }

        $donor = DB::table('NguoiHienMau')->where('NguoiDungId', $user->Id)->first();
        if (!$donor) {
            abort(404, 'Không tìm thấy hồ sơ người hiến máu.');
        }

        $registration = DB::table('DangKyHienMau')
            ->where('Id', $id)
            ->where('NguoiHienMauId', $donor->Id)
            ->whereNull('deleted_at')
            ->first();

        if (!$registration) {
            abort(404, 'Không tìm thấy thông tin đăng ký.');
        }

        DB::table('DangKyHienMau')
            ->where('Id', $id)
            ->update([
                'TrangThai' => 0,
                'updated_at' => now(),
            ]);

        return redirect()
            ->route('frontend.lich-su-dang-ky')
            ->with('success', 'Hủy đăng ký chương trình hiến máu thành công.');
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

            $role = $adminSession['role'] ?? 'donor';
            if ($role === 'Quản trị viên') {
                return 'admin';
            } elseif ($role === 'Nhân viên') {
                return 'nhan-vien';
            } else {
                return 'donor';
            }
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
}
