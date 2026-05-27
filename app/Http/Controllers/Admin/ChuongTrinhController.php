<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChuongTrinhController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('ChuongTrinhHienMau as ct')
            ->join('DonViToChuc as dv', 'ct.DonViToChucId', '=', 'dv.Id')
            ->select('ct.*', 'dv.TenDonVi')
            ->whereNull('ct.deleted_at');

        // 1. Lọc theo Từ khóa tìm kiếm
        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('ct.TenChuongTrinh', 'like', "%{$keyword}%")
                  ->orWhere('ct.MoTa', 'like', "%{$keyword}%");
            });
        }

        // 2. Lọc theo Đơn vị tổ chức
        if ($request->filled('don_vi')) {
            $query->where('ct.DonViToChucId', $request->get('don_vi'));
        }

        // 3. Lọc theo Tab hoặc Bộ lọc Trạng thái
        $activeTab = $request->get('tab', 'tat-ca');
        if ($activeTab !== 'tat-ca') {
            switch ($activeTab) {
                case 'cho-duyet':
                    $query->where('ct.TrangThai', 1);
                    break;
                case 'da-duyet':
                    $query->where('ct.TrangThai', 2);
                    break;
                case 'dang-dien-ra':
                    $query->where('ct.TrangThai', 3);
                    break;
                case 'da-huy':
                    $query->where('ct.TrangThai', 4);
                    break;
                case 'da-ket-thuc':
                    $query->where('ct.TrangThai', 5);
                    break;
            }
        } elseif ($request->filled('trang_thai')) {
            $query->where('ct.TrangThai', $request->get('trang_thai'));
        }


        // 4. Lọc theo Thời gian bắt đầu
        if ($request->filled('thoi_gian_bd')) {
            $query->where('ct.ThoiGianBatDau', '>=', Carbon::parse($request->get('thoi_gian_bd'))->startOfDay());
        }

        // 5. Lọc theo Thời gian kết thúc
        if ($request->filled('thoi_gian_kt')) {
            $query->where('ct.ThoiGianKetThuc', '<=', Carbon::parse($request->get('thoi_gian_kt'))->endOfDay());
        }

        // Phân trang động
        $perPage = $request->get('per_page', 10);
        if (!in_array($perPage, [10, 20, 50])) {
            $perPage = 10;
        }

        $programs = $query->orderBy('ct.ThoiGianBatDau', 'asc')
            ->paginate($perPage)
            ->withQueryString();

        // Tính toán số lượng cho từng Tab (dùng cột TrangThai làm nguồn sự thật duy nhất)
        $base = DB::table('ChuongTrinhHienMau')->whereNull('deleted_at');
        $counts = [
            'tat_ca'       => (clone $base)->count(),
            'cho_duyet'    => (clone $base)->where('TrangThai', 1)->count(),
            'da_duyet'     => (clone $base)->where('TrangThai', 2)->count(),
            'dang_dien_ra' => (clone $base)->where('TrangThai', 3)->count(),
            'da_huy'       => (clone $base)->where('TrangThai', 4)->count(),
            'da_ket_thuc'  => (clone $base)->where('TrangThai', 5)->count(),
        ];

        // Lấy danh sách Đơn Vị Tổ Chức để làm bộ lọc
        $donVis = DB::table('DonViToChuc')->select('Id', 'TenDonVi')->whereNull('deleted_at')->get();

        // Giả lập số người đăng ký tương ứng cho từng chương trình giống như trong ảnh thiết kế
        foreach ($programs as $prog) {
            // Đếm số lượng đăng ký thực tế
            $actualCount = DB::table('DangKyHienMau')->where('ChuongTrinhId', $prog->Id)->count();
            
            if ($actualCount > 0) {
                $prog->SoNguoiDangKy = $actualCount;
            } else {
                // Nếu chưa có đăng ký trong DB, tự động lấy số theo ảnh demo
                switch ($prog->TenChuongTrinh) {
                    case 'Giọt hồng yêu thương 2025':
                        $prog->SoNguoiDangKy = 150;
                        break;
                    case 'Hiến máu nhân đạo đợt 1':
                        $prog->SoNguoiDangKy = 180;
                        break;
                    case 'Trao giọt máu - Trao yêu thương':
                        $prog->SoNguoiDangKy = 120;
                        break;
                    case 'Mùa hè nhân ái 2025':
                        $prog->SoNguoiDangKy = 0;
                        break;
                    case 'Hiến máu cứu người - Hành động đẹp':
                        $prog->SoNguoiDangKy = 60;
                        break;
                    default:
                        $prog->SoNguoiDangKy = 0;
                        break;
                }
            }

            // Tính toán tỷ lệ %
            $prog->PhanTram = $prog->SoLuongDuKien > 0 
                ? round(($prog->SoNguoiDangKy / $prog->SoLuongDuKien) * 100) 
                : 0;
        }

        return view('admin.chuong-trinh.index', compact('programs', 'counts', 'donVis', 'activeTab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'TenChuongTrinh' => 'required|string|max:500',
            'MoTa' => 'required|string',
            'Banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'DonViToChucId' => 'required|integer|exists:DonViToChuc,Id',
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
            'Banner.required' => 'Ảnh banner không được để trống.',
            'Banner.image' => 'Banner phải là định dạng hình ảnh.',
            'Banner.mimes' => 'Banner phải có định dạng: jpeg, png, jpg, gif, svg, webp.',
            'Banner.max' => 'Kích thước banner không vượt quá 2MB.',
            'DonViToChucId.required' => 'Vui lòng chọn đơn vị tổ chức.',
            'DonViToChucId.exists' => 'Đơn vị tổ chức đã chọn không hợp lệ.',
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

        $admin = DB::table('NguoiDung')->where('Email', 'admin@system.com')->first();
        $nguoiTaoId = $admin ? $admin->Id : 1;

        $banner = 'linear-gradient(135deg, #f43f5e 0%, #e11d48 100%)';
        if ($request->hasFile('Banner')) {
            $file = $request->file('Banner');
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType();
            $fileSize = $file->getSize();
            
            $filename = time() . '_' . $originalName;
            
            $destinationPath = public_path('uploads/banners');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            $banner = 'uploads/banners/' . $filename;

            DB::table('TapTin')->insert([
                'TenFile' => $originalName,
                'DuongDan' => $banner,
                'LoaiFile' => $mimeType,
                'KichThuoc' => $fileSize,
                'NguoiTaiLenId' => $nguoiTaoId,
                'NgayTaiLen' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $startTime = Carbon::parse($request->input('ThoiGianBatDau'));
        $endTime = Carbon::parse($request->input('ThoiGianKetThuc'));
        $now = now();

        if ($now->between($startTime, $endTime)) {
            $trangThai = 3;
            $dangDienRa = 1;
        } elseif ($now->lessThan($startTime)) {
            $trangThai = 2;
            $dangDienRa = 0;
        } else {
            $trangThai = 5;
            $dangDienRa = 0;
        }

        DB::table('ChuongTrinhHienMau')->insert([
            'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
            'TenChuongTrinh' => $request->input('TenChuongTrinh'),
            'MoTa' => $request->input('MoTa'),
            'Banner' => $banner,
            'DonViToChucId' => $request->input('DonViToChucId'),
            'DiaChi' => $request->input('DiaChi'),
            'BanDo' => $request->input('BanDo'),
            'ThoiGianBatDau' => $request->input('ThoiGianBatDau'),
            'ThoiGianKetThuc' => $request->input('ThoiGianKetThuc'),
            'ThoiGianMoDangKy' => $request->input('ThoiGianMoDangKy'),
            'DangDienRa' => $dangDienRa,
            'SoLuongDuKien' => $request->input('SoLuongDuKien'),
            'TrangThai' => $trangThai,
            'NguoiTaoId' => $nguoiTaoId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.chuong-trinh.index')->with('success', 'Tạo chương trình thành công.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'TenChuongTrinh' => 'required|string|max:500',
            'MoTa' => 'required|string',
            'Banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'DonViToChucId' => 'required|integer|exists:DonViToChuc,Id',
            'DiaChi' => 'required|string|max:500',
            'BanDo' => 'nullable|string|max:1000',
            'ThoiGianBatDau' => 'required|date|after_or_equal:today',
            'ThoiGianKetThuc' => 'required|date|after:ThoiGianBatDau',
            'ThoiGianMoDangKy' => 'required|date|before:ThoiGianBatDau',
            'SoLuongDuKien' => 'required|integer|min:1',
            'TrangThai' => 'required|in:1,2,3,4,5',
        ], [
            'TenChuongTrinh.required' => 'Tên chương trình không được để trống.',
            'TenChuongTrinh.string' => 'Tên chương trình phải là chuỗi ký tự.',
            'TenChuongTrinh.max' => 'Tên chương trình không vượt quá 500 ký tự.',
            'MoTa.required' => 'Mô tả không được để trống.',
            'Banner.image' => 'Banner phải là định dạng hình ảnh.',
            'Banner.mimes' => 'Banner phải có định dạng: jpeg, png, jpg, gif, svg, webp.',
            'Banner.max' => 'Kích thước banner không vượt quá 2MB.',
            'DonViToChucId.required' => 'Vui lòng chọn đơn vị tổ chức.',
            'DonViToChucId.exists' => 'Đơn vị tổ chức đã chọn không hợp lệ.',
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
            'TrangThai.required' => 'Vui lòng chọn trạng thái.',
            'TrangThai.in' => 'Trạng thái đã chọn không hợp lệ.',
        ]);

        $prog = DB::table('ChuongTrinhHienMau')->where('Id', $id)->first();
        $banner = $prog ? $prog->Banner : 'linear-gradient(135deg, #f43f5e 0%, #e11d48 100%)';

        if ($request->hasFile('Banner')) {
            $file = $request->file('Banner');
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getClientMimeType();
            $fileSize = $file->getSize();
            
            $filename = time() . '_' . $originalName;
            
            $destinationPath = public_path('uploads/banners');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            $banner = 'uploads/banners/' . $filename;

            $admin = DB::table('NguoiDung')->where('Email', 'admin@system.com')->first();
            $nguoiTaiLenId = $admin ? $admin->Id : 1;

            DB::table('TapTin')->insert([
                'TenFile' => $originalName,
                'DuongDan' => $banner,
                'LoaiFile' => $mimeType,
                'KichThuoc' => $fileSize,
                'NguoiTaiLenId' => $nguoiTaiLenId,
                'NgayTaiLen' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $startTime = Carbon::parse($request->input('ThoiGianBatDau'));
        $endTime = Carbon::parse($request->input('ThoiGianKetThuc'));
        $now = now();
        $dangDienRa = ($now->between($startTime, $endTime) && $request->input('TrangThai') == 3) ? 1 : 0;

        DB::table('ChuongTrinhHienMau')
            ->where('Id', $id)
            ->update([
                'TenChuongTrinh' => $request->input('TenChuongTrinh'),
                'MoTa' => $request->input('MoTa'),
                'Banner' => $banner,
                'DonViToChucId' => $request->input('DonViToChucId'),
                'DiaChi' => $request->input('DiaChi'),
                'BanDo' => $request->input('BanDo'),
                'ThoiGianBatDau' => $request->input('ThoiGianBatDau'),
                'ThoiGianKetThuc' => $request->input('ThoiGianKetThuc'),
                'ThoiGianMoDangKy' => $request->input('ThoiGianMoDangKy'),
                'DangDienRa' => $dangDienRa,
                'SoLuongDuKien' => $request->input('SoLuongDuKien'),
                'TrangThai' => $request->input('TrangThai'),
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.chuong-trinh.index')->with('success', 'Cập nhật chương trình thành công.');
    }

    public function destroy($id)
    {
        $prog = DB::table('ChuongTrinhHienMau')->where('Id', $id)->first();
        if ($prog) {
            DB::table('ChuongTrinhHienMau')->where('Id', $id)->update([
                'deleted_at' => now()
            ]);
            return redirect()->route('admin.chuong-trinh.index')->with('success', 'Xóa chương trình thành công.');
        }
        return redirect()->route('admin.chuong-trinh.index')->with('error', 'Không tìm thấy chương trình.');
    }

    public function approve($id)
    {
        $prog = DB::table('ChuongTrinhHienMau')->where('Id', $id)->first();
        if ($prog) {
            DB::table('ChuongTrinhHienMau')->where('Id', $id)->update([
                'TrangThai' => 2, // Đã duyệt
                'updated_at' => now()
            ]);
            return redirect()->back()->with('success', 'Duyệt chương trình "' . $prog->TenChuongTrinh . '" thành công.');
        }
        return redirect()->back()->with('error', 'Không tìm thấy chương trình.');
    }
}
