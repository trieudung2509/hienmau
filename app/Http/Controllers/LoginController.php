<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.dangnhap');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email_or_phone' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ], [
            'email_or_phone.required' => 'Vui lòng nhập email hoặc số điện thoại.',
            'email_or_phone.string' => 'Email hoặc số điện thoại không hợp lệ.',
            'email_or_phone.max' => 'Email hoặc số điện thoại vượt quá 255 ký tự.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.string' => 'Mật khẩu không hợp lệ.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $identity = $validated['email_or_phone'];

        $user = DB::table('NguoiDung as nd')
            ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
            ->select('nd.Id', 'nd.HoTen', 'nd.MatKhauHash', 'nd.TrangThai', 'vt.TenVaiTro', 'nd.Email', 'nd.SoDienThoai')
            ->where('nd.Email', $identity)
            ->orWhere('nd.SoDienThoai', $identity)
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->MatKhauHash)) {
            return back()
                ->withErrors(['email_or_phone' => 'Thông tin đăng nhập không chính xác.'])
                ->withInput();
        }

        if ($user->TrangThai != 1) {
            return back()
                ->withErrors(['email_or_phone' => 'Tài khoản của bạn đã bị khóa hoặc đang chờ phê duyệt.'])
                ->withInput();
        }

        if ($user->TenVaiTro === 'Đơn vị tổ chức') {
            $donVi = DB::table('DonViToChuc')
                ->where('OwnerUserId', $user->Id)
                ->orWhere('Email', $user->Email)
                ->orWhere('SoDienThoai', $user->SoDienThoai)
                ->first();

            if (!$donVi) {
                return back()
                    ->withErrors(['email_or_phone' => 'Tài khoản đơn vị tổ chức không thuộc về bất kỳ đơn vị nào.'])
                    ->withInput();
            }
        }

        $request->session()->regenerate();
        $request->session()->put('admin_user', [
            'id' => $user->Id,
            'name' => $user->HoTen,
            'role' => $user->TenVaiTro,
        ]);

        if ($user->TenVaiTro === 'Quản trị viên') {
            return redirect()->route('admin.home');
        }

        if ($user->TenVaiTro === 'Nhân viên') {
            return redirect()->route('nhan-vien.index');
        }

        if ($user->TenVaiTro === 'Đơn vị tổ chức') {
            return redirect()->route('don-vi-to-chuc.index');
        }

        return redirect()->route('home');
    }
}
