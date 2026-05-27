<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonViToChucController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('DonViToChuc as dv')
            ->leftJoin('NguoiDung as nd', 'dv.OwnerUserId', '=', 'nd.Id')
            ->select('dv.*', 'nd.HoTen as ChuSoHuu');

        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('dv.TenDonVi', 'like', "%{$keyword}%")
                    ->orWhere('dv.MaDonVi', 'like', "%{$keyword}%")
                    ->orWhere('dv.Email', 'like', "%{$keyword}%")
                    ->orWhere('dv.SoDienThoai', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('trang_thai')) {
            $query->where('dv.TrangThai', $request->get('trang_thai'));
        }

        $donVis = $query->orderBy('dv.created_at', 'desc')->paginate(10)->withQueryString();

        $editDonVi = null;
        if ($request->filled('edit_id')) {
            $editDonVi = DB::table('DonViToChuc')->where('Id', $request->get('edit_id'))->first();
        }

        $owners = DB::table('NguoiDung as nd')
            ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
            ->select('nd.Id', 'nd.HoTen', 'vt.TenVaiTro')
            ->where('vt.TenVaiTro', 'Đơn vị tổ chức')
            ->orderBy('nd.HoTen')
            ->get();

        return view('admin.don-vi-to-chuc.index', compact('donVis', 'owners', 'editDonVi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenDonVi' => 'required|string|max:255',
            'MaDonVi' => 'required|string|max:50|unique:DonViToChuc,MaDonVi',
            'Loai' => 'required|string|max:100',
            'Email' => 'required|email|max:255|unique:DonViToChuc,Email',
            'SoDienThoai' => [
                'required',
                'regex:/^(0[235789])[0-9]{8,9}$/',
                'unique:DonViToChuc,SoDienThoai'
            ],
            'DiaChi' => 'required|string|max:500',
            'MoTa' => 'required|string',
            'NguoiDaiDien' => 'required|string|max:255',
            'TrangThai' => 'required|in:1,2',
            'OwnerUserId' => 'required|integer|exists:NguoiDung,Id',
            'HinhAnh' => 'nullable|string|max:500',
            'HinhAnhFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'TenDonVi.required' => 'Ten don vi khong duoc de trong.',
            'TenDonVi.string' => 'Ten don vi khong hop le.',
            'TenDonVi.max' => 'Ten don vi khong vuot qua 255 ky tu.',
            'MaDonVi.required' => 'Ma don vi khong duoc de trong.',
            'MaDonVi.max' => 'Ma don vi khong vuot qua 50 ky tu.',
            'MaDonVi.unique' => 'Ma don vi da ton tai.',
            'Loai.required' => 'Loai don vi khong duoc de trong.',
            'Loai.max' => 'Loai don vi khong vuot qua 100 ky tu.',
            'Email.required' => 'Email khong duoc de trong.',
            'Email.email' => 'Email khong hop le.',
            'Email.max' => 'Email khong vuot qua 255 ky tu.',
            'Email.unique' => 'Email da duoc su dung.',
            'SoDienThoai.required' => 'So dien thoai khong duoc de trong.',
            'SoDienThoai.regex' => 'So dien thoai chua dung dinh dang.',
            'SoDienThoai.unique' => 'So dien thoai da duoc su dung.',
            'DiaChi.required' => 'Dia chi khong duoc de trong.',
            'DiaChi.max' => 'Dia chi khong vuot qua 500 ky tu.',
            'MoTa.required' => 'Mo ta khong duoc de trong.',
            'NguoiDaiDien.required' => 'Nguoi dai dien khong duoc de trong.',
            'NguoiDaiDien.max' => 'Nguoi dai dien khong vuot qua 255 ky tu.',
            'TrangThai.required' => 'Vui long chon trang thai.',
            'TrangThai.in' => 'Trang thai khong hop le.',
            'OwnerUserId.required' => 'Vui long chon tai khoan chu so huu.',
            'OwnerUserId.integer' => 'Tai khoan chu so huu khong hop le.',
            'OwnerUserId.exists' => 'Tai khoan chu so huu khong ton tai.',
        ]);

        $hinhAnh = $request->input('HinhAnh');
        if ($request->hasFile('HinhAnhFile')) {
            $file = $request->file('HinhAnhFile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/don-vi'), $fileName);
            $hinhAnh = '/uploads/don-vi/' . $fileName;
        }

        DB::table('DonViToChuc')->insert([
            'TenDonVi' => $request->input('TenDonVi'),
            'MaDonVi' => $request->input('MaDonVi'),
            'Loai' => $request->input('Loai'),
            'Email' => $request->input('Email'),
            'SoDienThoai' => $request->input('SoDienThoai'),
            'DiaChi' => $request->input('DiaChi'),
            'MoTa' => $request->input('MoTa'),
            'NguoiDaiDien' => $request->input('NguoiDaiDien'),
            'TrangThai' => $request->input('TrangThai'),
            'OwnerUserId' => $request->input('OwnerUserId'),
            'HinhAnh' => $hinhAnh,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.don-vi-to-chuc.index')->with('success', 'Them don vi to chuc thanh cong.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenDonVi' => 'required|string|max:255',
            'MaDonVi' => 'required|string|max:50|unique:DonViToChuc,MaDonVi,' . $id . ',Id',
            'Loai' => 'required|string|max:100',
            'Email' => 'required|email|max:255|unique:DonViToChuc,Email,' . $id . ',Id',
            'SoDienThoai' => [
                'required',
                'regex:/^(0[235789])[0-9]{8,9}$/',
                'unique:DonViToChuc,SoDienThoai,' . $id . ',Id'
            ],
            'DiaChi' => 'required|string|max:500',
            'MoTa' => 'required|string',
            'NguoiDaiDien' => 'required|string|max:255',
            'TrangThai' => 'required|in:1,2',
            'OwnerUserId' => 'required|integer|exists:NguoiDung,Id',
            'HinhAnh' => 'nullable|string|max:500',
            'HinhAnhFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ], [
            'TenDonVi.required' => 'Ten don vi khong duoc de trong.',
            'TenDonVi.string' => 'Ten don vi khong hop le.',
            'TenDonVi.max' => 'Ten don vi khong vuot qua 255 ky tu.',
            'MaDonVi.required' => 'Ma don vi khong duoc de trong.',
            'MaDonVi.max' => 'Ma don vi khong vuot qua 50 ky tu.',
            'MaDonVi.unique' => 'Ma don vi da ton tai.',
            'Loai.required' => 'Loai don vi khong duoc de trong.',
            'Loai.max' => 'Loai don vi khong vuot qua 100 ky tu.',
            'Email.required' => 'Email khong duoc de trong.',
            'Email.email' => 'Email khong hop le.',
            'Email.max' => 'Email khong vuot qua 255 ky tu.',
            'Email.unique' => 'Email da duoc su dung.',
            'SoDienThoai.required' => 'So dien thoai khong duoc de trong.',
            'SoDienThoai.regex' => 'So dien thoai chua dung dinh dang.',
            'SoDienThoai.unique' => 'So dien thoai da duoc su dung.',
            'DiaChi.required' => 'Dia chi khong duoc de trong.',
            'DiaChi.max' => 'Dia chi khong vuot qua 500 ky tu.',
            'MoTa.required' => 'Mo ta khong duoc de trong.',
            'NguoiDaiDien.required' => 'Nguoi dai dien khong duoc de trong.',
            'NguoiDaiDien.max' => 'Nguoi dai dien khong vuot qua 255 ky tu.',
            'TrangThai.required' => 'Vui long chon trang thai.',
            'TrangThai.in' => 'Trang thai khong hop le.',
            'OwnerUserId.required' => 'Vui long chon tai khoan chu so huu.',
            'OwnerUserId.integer' => 'Tai khoan chu so huu khong hop le.',
            'OwnerUserId.exists' => 'Tai khoan chu so huu khong ton tai.',
        ]);

        $hinhAnh = $request->input('HinhAnh');
        if ($request->hasFile('HinhAnhFile')) {
            $file = $request->file('HinhAnhFile');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/don-vi'), $fileName);
            $hinhAnh = '/uploads/don-vi/' . $fileName;
        }

        DB::table('DonViToChuc')
            ->where('Id', $id)
            ->update([
                'TenDonVi' => $request->input('TenDonVi'),
                'MaDonVi' => $request->input('MaDonVi'),
                'Loai' => $request->input('Loai'),
                'Email' => $request->input('Email'),
                'SoDienThoai' => $request->input('SoDienThoai'),
                'DiaChi' => $request->input('DiaChi'),
                'MoTa' => $request->input('MoTa'),
                'NguoiDaiDien' => $request->input('NguoiDaiDien'),
                'TrangThai' => $request->input('TrangThai'),
                'OwnerUserId' => $request->input('OwnerUserId'),
                'HinhAnh' => $hinhAnh,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.don-vi-to-chuc.index')->with('success', 'Cap nhat don vi to chuc thanh cong.');
    }

    public function destroy($id)
    {
        DB::table('DonViToChuc')->where('Id', $id)->delete();

        return redirect()->route('admin.don-vi-to-chuc.index')->with('success', 'Xoa don vi to chuc thanh cong.');
    }
}
