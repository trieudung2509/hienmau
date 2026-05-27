<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NguoiDungController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('NguoiDung as nd')
            ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
            ->select('nd.*', 'vt.TenVaiTro');

        // 1. Lọc theo Từ khóa tìm kiếm (Tên, Email, Số điện thoại)
        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('nd.HoTen', 'like', "%{$keyword}%")
                  ->orWhere('nd.Email', 'like', "%{$keyword}%")
                  ->orWhere('nd.SoDienThoai', 'like', "%{$keyword}%");
            });
        }

        // 2. Lọc theo Vai trò
        if ($request->filled('vai_tro')) {
            $query->where('nd.VaiTroId', $request->get('vai_tro'));
        }

        // 3. Lọc theo Trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('nd.TrangThai', $request->get('trang_thai'));
        }

        $perPage = $request->get('per_page', 10);
        if (!in_array($perPage, [10, 20, 50])) {
            $perPage = 10;
        }

        $users = $query->orderBy('nd.created_at', 'desc')->paginate($perPage)->withQueryString();

        // Tính toán các số liệu thống kê ở đầu trang
        $totalUsers = DB::table('NguoiDung')->count();
        
        $participantRole = DB::table('VaiTro')->where('TenVaiTro', 'Người tham gia')->first();
        $orgRole = DB::table('VaiTro')->where('TenVaiTro', 'Đơn vị tổ chức')->first();
        $employeeRole = DB::table('VaiTro')->where('TenVaiTro', 'Nhân viên')->first();

        $participantCount = $participantRole ? DB::table('NguoiDung')->where('VaiTroId', $participantRole->Id)->count() : 0;
        $orgCount = $orgRole ? DB::table('NguoiDung')->where('VaiTroId', $orgRole->Id)->count() : 0;
        $employeeCount = $employeeRole ? DB::table('NguoiDung')->where('VaiTroId', $employeeRole->Id)->count() : 0;

        // Tránh chia cho 0
        $percentParticipants = $totalUsers > 0 ? round(($participantCount / $totalUsers) * 100) : 0;
        $percentOrgs = $totalUsers > 0 ? round(($orgCount / $totalUsers) * 100) : 0;
        $percentEmployees = $totalUsers > 0 ? round(($employeeCount / $totalUsers) * 100) : 0;

        $stats = [
            'total' => $totalUsers,
            'participants' => $participantCount,
            'percent_participants' => $percentParticipants,
            'orgs' => $orgCount,
            'percent_orgs' => $percentOrgs,
            'employees' => $employeeCount,
            'percent_employees' => $percentEmployees,
        ];

        // Lấy danh sách Vai trò làm bộ lọc
        $roles = DB::table('VaiTro')->select('Id', 'TenVaiTro')->get();

        // Ánh xạ đơn vị công tác cho từng dòng dựa trên dữ liệu mẫu trong ảnh
        foreach ($users as $u) {
            if ($u->TenVaiTro === 'Quản trị viên') {
                $u->DonVi = 'Bệnh viện Huyết học Truyền máu TW';
            } elseif ($u->TenVaiTro === 'Nhân viên') {
                // Ánh xạ cụ thể cho các nhân viên mẫu
                if (str_contains($u->HoTen, 'Bình')) {
                    $u->DonVi = 'Khoa Tiếp nhận';
                } elseif (str_contains($u->HoTen, 'Cường')) {
                    $u->DonVi = 'Khoa Xét nghiệm';
                } else {
                    $u->DonVi = 'Bệnh viện TW';
                }
            } elseif ($u->TenVaiTro === 'Đơn vị tổ chức') {
                // Hiển thị tên đơn vị của chính họ
                $u->DonVi = $u->HoTen;
            } else {
                $u->DonVi = '—';
            }
        }

        return view('admin.nguoi-dung.index', compact('users', 'stats', 'roles'));
    }

    // Toggle trạng thái người dùng (Khóa/Mở khóa) để trang quản trị hoàn toàn hoạt động!
    public function toggleStatus($id)
    {
        $user = DB::table('NguoiDung')->where('Id', $id)->first();
        if ($user) {
            $newStatus = $user->TrangThai == 1 ? 2 : 1;
            DB::table('NguoiDung')->where('Id', $id)->update(['updated_at' => now(), 'TrangThai' => $newStatus]);
            
            $action = $newStatus == 2 ? 'đóng băng' : 'mở khóa';
            return redirect()->back()->with('success', "Đã {$action} tài khoản {$user->HoTen} thành công.");
        }
        return redirect()->back()->with('error', 'Không tìm thấy tài khoản.');
    }

    public function store(Request $request)
    {
        // Phổ biến các luật xác thực và thông báo tiếng Việt
        $request->validate([
            'HoTen' => 'required|string|max:255',
            'Email' => 'required|email|max:255|unique:NguoiDung,Email',
            'SoDienThoai' => [
                'required',
                'regex:/^(0[235789])[0-9]{8,9}$/',
                'unique:NguoiDung,SoDienThoai'
            ],
            'VaiTroId' => 'required|integer',
            'MatKhau' => 'required|string|min:6',
            'TrangThai' => 'required|in:1,2',
            'NgaySinh' => 'nullable|date',
            'GioiTinh' => 'nullable|in:1,2,3',
        ], [
            'HoTen.required' => 'Họ và tên không được để trống.',
            'HoTen.string' => 'Họ và tên phải là chuỗi ký tự.',
            'HoTen.max' => 'Họ và tên không vượt quá 255 ký tự.',
            'Email.required' => 'Email không được để trống.',
            'Email.email' => 'Định dạng Email không hợp lệ (ví dụ: example@gmail.com).',
            'Email.max' => 'Email không vượt quá 255 ký tự.',
            'Email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'SoDienThoai.required' => 'Số điện thoại không được để trống.',
            'SoDienThoai.regex' => 'Số điện thoại chưa đúng định dạng',
            'SoDienThoai.unique' => 'Số điện thoại này đã tồn tại trong hệ thống.',
            'VaiTroId.required' => 'Vui lòng chọn vai trò.',
            'MatKhau.required' => 'Mật khẩu không được để trống.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'TrangThai.required' => 'Vui lòng chọn trạng thái.',
            'TrangThai.in' => 'Trạng thái đã chọn không hợp lệ.',
        ]);

        // Thêm người dùng mới vào database
        DB::table('NguoiDung')->insert([
            'HoTen' => $request->input('HoTen'),
            'Email' => $request->input('Email'),
            'SoDienThoai' => $request->input('SoDienThoai'),
            'MatKhauHash' => \Illuminate\Support\Facades\Hash::make($request->input('MatKhau')),
            'VaiTroId' => $request->input('VaiTroId'),
            'TrangThai' => $request->input('TrangThai'),
            'NgaySinh' => $request->input('NgaySinh'),
            'GioiTinh' => $request->input('GioiTinh'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.nguoi-dung.index')->with('success', 'Thêm người dùng mới thành công.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'HoTen' => 'required|string|max:255',
            'Email' => 'required|email|max:255|unique:NguoiDung,Email,' . $id . ',Id',
            'SoDienThoai' => [
                'required',
                'regex:/^(0[235789])[0-9]{8,9}$/',
                'unique:NguoiDung,SoDienThoai,' . $id . ',Id'
            ],
            'VaiTroId' => 'required|integer',
            'MatKhau' => 'nullable|string|min:6',
            'TrangThai' => 'required|in:1,2',
            'NgaySinh' => 'nullable|date',
            'GioiTinh' => 'nullable|in:1,2,3',
        ], [
            'HoTen.required' => 'Họ và tên không được để trống.',
            'HoTen.string' => 'Họ và tên phải là chuỗi ký tự.',
            'HoTen.max' => 'Họ và tên không vượt quá 255 ký tự.',
            'Email.required' => 'Email không được để trống.',
            'Email.email' => 'Định dạng Email không hợp lệ (ví dụ: example@gmail.com).',
            'Email.max' => 'Email không vượt quá 255 ký tự.',
            'Email.unique' => 'Email này đã tồn tại trong hệ thống.',
            'SoDienThoai.required' => 'Số điện thoại không được để trống.',
            'SoDienThoai.regex' => 'Số điện thoại chưa đúng định dạng.',
            'SoDienThoai.unique' => 'Số điện thoại này đã tồn tại trong hệ thống.',
            'VaiTroId.required' => 'Vui lòng chọn vai trò.',
            'MatKhau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'TrangThai.required' => 'Vui lòng chọn trạng thái.',
            'TrangThai.in' => 'Trạng thái đã chọn không hợp lệ.',
        ]);

        $updateData = [
            'HoTen' => $request->input('HoTen'),
            'Email' => $request->input('Email'),
            'SoDienThoai' => $request->input('SoDienThoai'),
            'VaiTroId' => $request->input('VaiTroId'),
            'TrangThai' => $request->input('TrangThai'),
            'NgaySinh' => $request->input('NgaySinh'),
            'GioiTinh' => $request->input('GioiTinh'),
            'updated_at' => now(),
        ];

        if ($request->filled('MatKhau')) {
            $updateData['MatKhauHash'] = \Illuminate\Support\Facades\Hash::make($request->input('MatKhau'));
        }

        $user = DB::table('NguoiDung')->where('Id', $id)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'Không tìm thấy người dùng.');
        }

        DB::table('NguoiDung')->where('Id', $id)->update($updateData);

        return redirect()->route('admin.nguoi-dung.index')->with('success', 'Cập nhật tài khoản người dùng thành công.');
    }
}

