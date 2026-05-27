<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email_or_phone' => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ], [
            'email_or_phone.required' => 'Vui long nhap email hoac so dien thoai.',
            'email_or_phone.string' => 'Email hoac so dien thoai khong hop le.',
            'email_or_phone.max' => 'Email hoac so dien thoai vuot qua 255 ky tu.',
            'password.required' => 'Vui long nhap mat khau.',
            'password.string' => 'Mat khau khong hop le.',
            'password.min' => 'Mat khau phai co it nhat 6 ky tu.',
        ]);

        $identity = $validated['email_or_phone'];

        $user = DB::table('NguoiDung as nd')
            ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
            ->select('nd.Id', 'nd.HoTen', 'nd.MatKhauHash', 'nd.TrangThai', 'vt.TenVaiTro')
            ->where('nd.Email', $identity)
            ->orWhere('nd.SoDienThoai', $identity)
            ->first();

        if (!$user || !Hash::check($validated['password'], $user->MatKhauHash)) {
            return back()
                ->withErrors(['email_or_phone' => 'Thong tin dang nhap khong dung.'])
                ->withInput();
        }

        if ($user->TrangThai != 1) {
            return back()
                ->withErrors(['email_or_phone' => 'Tai khoan dang bi khoa hoac tam ngung.'])
                ->withInput();
        }

        if ($user->TenVaiTro !== 'Quản trị viên' && $user->TenVaiTro !== 'Nhân viên') {
            return back()
                ->withErrors(['email_or_phone' => 'Tai khoan khong co quyen quan tri.'])
                ->withInput();
        }

        $request->session()->regenerate();
        $request->session()->put('admin_user', [
            'id' => $user->Id,
            'name' => $user->HoTen,
            'role' => $user->TenVaiTro,
        ]);

        if ($user->TenVaiTro === 'Nhân viên') {
            return redirect()->route('nhan-vien.index');
        }

        $redirectTo = $request->session()->pull('admin_intended', route('admin.home'));

        return redirect()->to($redirectTo);
    }

    public function logout(Request $request)
    {
        $role = null;
        if ($request->session()->has('admin_user')) {
            $role = $request->session()->get('admin_user.role');
        }

        $request->session()->forget('admin_user');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'Đơn vị tổ chức' || $role === 'Người tham gia') {
            return redirect()->to('/');
        }

        return redirect()->route('admin.login');
    }
}
