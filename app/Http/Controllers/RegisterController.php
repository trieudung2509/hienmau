<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('frontend.dangkytaikhoan');
    }

    public function register(Request $request)
    {
        $roleType = $request->input('role_type', 'donor');

        if ($roleType === 'donor') {
            $request->validate([
                'HoTen' => 'required|string|max:255',
                'SoDienThoai' => [
                    'required',
                    'regex:/^(0[235789])[0-9]{8,9}$/',
                    'unique:NguoiDung,SoDienThoai'
                ],
                'Email' => 'required|email|max:255|unique:NguoiDung,Email',
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
                // 1. Lấy Role Người tham gia
                $role = DB::table('VaiTro')->where('TenVaiTro', 'Người tham gia')->first();
                if (!$role) {
                    throw new \Exception("Vai trò Người tham gia không tồn tại.");
                }

                // 2. Tạo NguoiDung
                $userId = DB::table('NguoiDung')->insertGetId([
                    'HoTen' => $request->input('HoTen'),
                    'Email' => $request->input('Email'),
                    'SoDienThoai' => $request->input('SoDienThoai'),
                    'MatKhauHash' => Hash::make($request->input('MatKhau')),
                    'VaiTroId' => $role->Id,
                    'TrangThai' => 1, // Hoạt động
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 3. Tạo NguoiHienMau
                $gioiTinhMap = ['Nam' => 1, 'Nữ' => 2, 'Khác' => 3];
                DB::table('NguoiHienMau')->insert([
                    'PublicId' => Str::uuid()->toString(),
                    'NguoiDungId' => $userId,
                    'CCCD' => '001099' . str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT),
                    'NgaySinh' => $request->input('NgaySinh'),
                    'GioiTinh' => $gioiTinhMap[$request->input('GioiTinh')],
                    'NhomMau' => '—',
                    'DiaChi' => 'Hà Nội',
                    'CanNang' => 55.0,
                    'NgheNghiep' => 'Tự do',
                    'SoLanDaHien' => 0,
                    'TrangThaiSucKhoe' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit();
                return redirect()->route('dang-nhap')->with('success', 'Đăng ký tài khoản người hiến máu thành công! Vui lòng đăng nhập.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Đã xảy ra lỗi khi đăng ký: ' . $e->getMessage()])->withInput();
            }

        } else {
            // Đăng ký đơn vị tổ chức
            $request->validate([
                'TenDonVi' => 'required|string|max:255',
                'Email' => 'required|email|max:255|unique:NguoiDung,Email',
                'SoDienThoai' => [
                    'required',
                    'regex:/^(0[235789])[0-9]{8,9}$/',
                    'unique:NguoiDung,SoDienThoai'
                ],
                'NguoiDaiDien' => 'required|string|max:255',
                'Loai' => 'required|in:Trường học,Bệnh viện,Doanh nghiệp,Khác',
                'DiaChi' => 'required|string|max:500',
                'MoTa' => 'nullable|string',
                'MatKhau' => 'required|string|min:6|confirmed',
            ], [
                'TenDonVi.required' => 'Tên tổ chức không được để trống.',
                'Email.required' => 'Email không được để trống.',
                'Email.email' => 'Email không đúng định dạng.',
                'Email.unique' => 'Email này đã được sử dụng.',
                'SoDienThoai.required' => 'Số điện thoại không được để trống.',
                'SoDienThoai.regex' => 'Số điện thoại chưa đúng định dạng.',
                'SoDienThoai.unique' => 'Số điện thoại này đã được sử dụng.',
                'NguoiDaiDien.required' => 'Người đại diện không được để trống.',
                'Loai.required' => 'Vui lòng chọn loại tổ chức.',
                'Loai.in' => 'Loại tổ chức không hợp lệ.',
                'DiaChi.required' => 'Địa chỉ không được để trống.',
                'MatKhau.required' => 'Mật khẩu không được để trống.',
                'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
                'MatKhau.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]);

            DB::beginTransaction();
            try {
                // 1. Lấy Role Đơn vị tổ chức
                $role = DB::table('VaiTro')->where('TenVaiTro', 'Đơn vị tổ chức')->first();
                if (!$role) {
                    throw new \Exception("Vai trò Đơn vị tổ chức không tồn tại.");
                }

                // 2. Tạo NguoiDung (Bắt đầu với TrangThai = 2 vì admin duyệt trước)
                $userId = DB::table('NguoiDung')->insertGetId([
                    'HoTen' => $request->input('TenDonVi'),
                    'Email' => $request->input('Email'),
                    'SoDienThoai' => $request->input('SoDienThoai'),
                    'MatKhauHash' => Hash::make($request->input('MatKhau')),
                    'VaiTroId' => $role->Id,
                    'TrangThai' => 2, // Đã đóng băng (chờ duyệt)
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // 3. Tạo DonViToChuc
                DB::table('DonViToChuc')->insert([
                    'TenDonVi' => $request->input('TenDonVi'),
                    'MaDonVi' => strtoupper(Str::slug($request->input('TenDonVi')) . '-' . rand(100, 999)),
                    'Loai' => $request->input('Loai'),
                    'Email' => $request->input('Email'),
                    'SoDienThoai' => $request->input('SoDienThoai'),
                    'DiaChi' => $request->input('DiaChi'),
                    'MoTa' => $request->input('MoTa') ?? '',
                    'NguoiDaiDien' => $request->input('NguoiDaiDien'),
                    'TrangThai' => 2, // Chờ duyệt
                    'OwnerUserId' => $userId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                DB::commit();
                return redirect()->route('dang-nhap')->with('success', 'Đăng ký tài khoản tổ chức thành công! Vui lòng chờ admin xét duyệt để kích hoạt tài khoản.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Đã xảy ra lỗi khi đăng ký tổ chức: ' . $e->getMessage()])->withInput();
            }
        }
    }
}
