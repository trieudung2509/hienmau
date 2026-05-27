<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class NhanVienController extends Controller
{
    public function index(Request $request)
    {
        // Resolve logged-in staff from session with demo fallback
        $sessionUser = session('admin_user');
        $staff = null;
        if ($sessionUser && isset($sessionUser['id'])) {
            $staff = DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('nd.Id', $sessionUser['id'])
                ->first();
        }

        if (!$staff) {
            $staff = DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('nd.Email', 'tranthibinh@gmail.com')
                ->first();
        }

        if (!$staff) {
            $staff = DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('vt.TenVaiTro', 'Nhân viên')
                ->first();
        }

        // Lấy chương trình hôm nay từ cơ sở dữ liệu
        $now = Carbon::now(config('app.timezone'));
        $today = $now->copy()->startOfDay();
        $todayProgram = DB::table('ChuongTrinhHienMau as ct')
            ->join('DonViToChuc as dv', 'ct.DonViToChucId', '=', 'dv.Id')
            ->select('ct.*', 'dv.TenDonVi')
            ->where('ct.ThoiGianBatDau', '<=', $now)
            ->where('ct.ThoiGianKetThuc', '>=', $now)
            ->whereNull('ct.deleted_at')
            ->orderBy('ct.ThoiGianBatDau')
            ->first();

        $participants = new LengthAwarePaginator([], 0, 10, 1, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        $todayParticipantsCount = 0;
        $todayDonatedCount = 0;
        $todayBlood = 0;

        if ($todayProgram) {
            $todayParticipantsCount = DB::table('DangKyHienMau')
                ->where('ChuongTrinhId', $todayProgram->Id)
                ->whereNull('deleted_at')
                ->count();

            $donations = DB::table('HoSoHienMau')
                ->where('ChuongTrinhId', $todayProgram->Id)
                ->whereNull('deleted_at')
                ->selectRaw('COUNT(*) as da_hien, SUM(LuongMau) as luong_mau')
                ->first();

            $todayDonatedCount = $donations ? (int) $donations->da_hien : 0;
            $todayBlood = $donations ? (float) $donations->luong_mau : 0;
        }

        $metrics = [
            'tong_nguoi_tg' => $todayParticipantsCount,
            'da_hien' => $todayDonatedCount,
            'luong_mau' => number_format($todayBlood, 0, ',', '.'),
            'dang_cho' => max(0, $todayParticipantsCount - $todayDonatedCount),
        ];

        $participantsBaseQuery = DB::table('DangKyHienMau as dk')
            ->join('NguoiHienMau as nhm', 'dk.NguoiHienMauId', '=', 'nhm.Id')
            ->join('NguoiDung as nd', 'nhm.NguoiDungId', '=', 'nd.Id')
            ->join('ChuongTrinhHienMau as ct', 'dk.ChuongTrinhId', '=', 'ct.Id')
            ->leftJoin('HoSoHienMau as hs', function ($join) {
                $join->on('hs.NguoiHienMauId', '=', 'nhm.Id')
                    ->on('hs.ChuongTrinhId', '=', 'dk.ChuongTrinhId')
                    ->whereNull('hs.deleted_at');
            })
            ->whereNull('dk.deleted_at')
            ->whereNull('nhm.deleted_at')
            ->whereNull('nd.deleted_at')
            ->whereNull('ct.deleted_at');

        if ($todayProgram) {
            $participantsBaseQuery->where('dk.ChuongTrinhId', $todayProgram->Id);
        } else {
            // Nếu không có chương trình nào, không lấy ai
            $participantsBaseQuery->where('dk.Id', '<', 0);
        }

        $participantsQuery = (clone $participantsBaseQuery)
            ->select(
                'nd.HoTen',
                'nd.SoDienThoai',
                'nhm.NgaySinh',
                'nhm.GioiTinh',
                'nhm.NhomMau',
                'dk.TrangThai',
                'hs.Id as HoSoId',
                'hs.LuongMau'
            );

        if ($request->filled('search')) {
            $search = $request->get('search');
            $participantsQuery->where(function ($q) use ($search) {
                $q->where('nd.HoTen', 'like', "%{$search}%")
                    ->orWhere('nd.SoDienThoai', 'like', "%{$search}%");
            });
        }

        if ($request->filled('phone')) {
            $phone = $request->get('phone');
            $participantsQuery->where('nd.SoDienThoai', 'like', "%{$phone}%");
        }

        $perPage = (int) $request->get('per_page', 10);
        $perPage = $perPage > 0 ? $perPage : 10;

        $rows = $participantsQuery
            ->orderBy('dk.ThoiGianDangKy', 'desc')
            ->paginate($perPage)
            ->appends($request->query());

        $participants = $rows->through(function ($row, $index) use ($rows) {
            $genderMap = [
                1 => 'Nam',
                2 => 'Nữ',
                3 => 'Khác',
            ];

            $status = 'Chờ hiến';
            $statusClass = 'badge-warning';

            if ($row->HoSoId) {
                $status = 'Đã hiến';
                $statusClass = 'badge-success';
            } elseif ((int) $row->TrangThai === 2) {
                $status = 'Đang hiến';
                $statusClass = 'badge-primary';
            } elseif ((int) $row->TrangThai === 0) {
                $status = 'Hủy đăng ký';
                $statusClass = 'badge-danger';
            }

            $ngaySinh = $row->NgaySinh
                ? Carbon::parse($row->NgaySinh)->timezone(config('app.timezone'))->format('d/m/Y')
                : '';

            $offset = $rows->firstItem() ?? 0;

            return [
                'STT' => $offset + $index,
                'HoTen' => $row->HoTen,
                'NgaySinh' => $ngaySinh,
                'SDT' => $row->SoDienThoai,
                'GioiTinh' => $genderMap[(int) $row->GioiTinh] ?? 'Khác',
                'NhomMau' => $row->NhomMau,
                'TinhTrang' => $status,
                'LuongMau' => $row->LuongMau ? $row->LuongMau . ' ml' : null,
                'TinhTrangClass' => $statusClass,
            ];
        });

        return view('admin.nhan-vien.index', compact('staff', 'todayProgram', 'participants', 'metrics'));
    }

    public function hoSo(Request $request)
    {
        // 1. Resolve logged-in staff from session with demo fallback
        $sessionUser = session('admin_user');
        $staff = null;
        if ($sessionUser && isset($sessionUser['id'])) {
            $staff = DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('nd.Id', $sessionUser['id'])
                ->first();
        }

        if (!$staff) {
            $staff = DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('nd.Email', 'tranthibinh@gmail.com')
                ->first();
        }

        if (!$staff) {
            $staff = DB::table('NguoiDung as nd')
                ->join('VaiTro as vt', 'nd.VaiTroId', '=', 'vt.Id')
                ->select('nd.*', 'vt.TenVaiTro')
                ->where('vt.TenVaiTro', 'Nhân viên')
                ->first();
        }

        // 2. Fetch all programs for select dropdown filter
        $programsList = DB::table('ChuongTrinhHienMau')
            ->whereNull('deleted_at')
            ->select('Id', 'TenChuongTrinh')
            ->get();

        // 3. Query DangKyHienMau and left join HoSoHienMau to show registrations even without dossiers
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

        // 4. Apply search filter (name, email, phone)
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nd.HoTen', 'like', "%{$search}%")
                  ->orWhere('nd.Email', 'like', "%{$search}%")
                  ->orWhere('nd.SoDienThoai', 'like', "%{$search}%");
            });
        }

        // 5. Apply program filter
        if ($request->filled('chuong_trinh_id')) {
            $query->where('dk.ChuongTrinhId', $request->get('chuong_trinh_id'));
        }

        $records = $query->orderBy('dk.ThoiGianDangKy', 'desc')->get();

        // 6. Calculate some stats for dossier summary
        $totalHoSo = $records->whereNotNull('HoSoId')->count();
        $totalVol = $records->sum('LuongMau');
        $successCount = $records->where('KetQuaSauHien', 1)->count();
        // Count registrations that are cancelled (TrangThai = 0)
        $daHuyCount = $records->where('DangKyTrangThai', 0)->count();
        
        $metrics = [
            'tong_ho_so' => $totalHoSo,
            'thanh_cong' => $successCount,
            'tong_luong_mau' => number_format($totalVol, 0, ',', '.'),
            'da_huy' => $daHuyCount,
        ];

        return view('admin.nhan-vien.ho-so', compact('staff', 'programsList', 'records', 'metrics'));
    }

    public function storeHoSo(Request $request)
    {
        $validated = $request->validate([
            'hoten' => 'required|string|max:255',
            'sodienthoai' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'cccd' => 'required|string|max:20',
            'ngaysinh' => 'required|date|before:today',
            'gioitinh' => 'required|in:1,2,3',
            'chuong_trinh_id' => 'required|exists:ChuongTrinhHienMau,Id',
            'nhom_mau' => 'required|string|max:10',
            'huyet_ap' => 'required|string|max:20',
            'nhip_tim' => 'required|integer|min:0|max:200',
            'nhiet_do' => 'required|numeric|min:0|max:44',
            'can_nang' => 'required|numeric|min:0|max:200',
            'hemoglobin' => 'required|numeric|min:0|max:25',
            'nguoi_kham' => 'required|string|max:255',
            'luong_mau' => 'required|integer',
            'ket_qua_sau_hien' => 'required|integer|in:1,2',
            'ghi_chu' => 'nullable|string|max:500',
            'diachi' => 'required|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Find existing user by Phone or Email
            $existingUser = DB::table('NguoiDung')
                ->where('Email', $validated['email'])
                ->orWhere('SoDienThoai', $validated['sodienthoai'])
                ->first();

            if ($existingUser) {
                $nhm = DB::table('NguoiHienMau')->where('NguoiDungId', $existingUser->Id)->first();
                if ($nhm) {
                    $nguoiHienMauId = $nhm->Id;
                } else {
                    $nguoiHienMauId = DB::table('NguoiHienMau')->insertGetId([
                        'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
                        'NguoiDungId' => $existingUser->Id,
                        'CCCD' => $validated['cccd'],
                        'NgaySinh' => $validated['ngaysinh'],
                        'GioiTinh' => $validated['gioitinh'],
                        'NhomMau' => $validated['nhom_mau'],
                        'DiaChi' => $validated['diachi'],
                        'CanNang' => $validated['can_nang'],
                        'NgheNghiep' => 'Tự do',
                        'SoLanDaHien' => 0,
                        'TrangThaiSucKhoe' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            } else {
                $role = DB::table('VaiTro')->where('TenVaiTro', 'Người tham gia')->first();
                $roleId = $role ? $role->Id : 4;

                $userId = DB::table('NguoiDung')->insertGetId([
                    'HoTen' => $validated['hoten'],
                    'Email' => $validated['email'],
                    'SoDienThoai' => $validated['sodienthoai'],
                    'MatKhauHash' => \Illuminate\Support\Facades\Hash::make($validated['sodienthoai']),
                    'VaiTroId' => $roleId,
                    'TrangThai' => 1,
                    'NgaySinh' => $validated['ngaysinh'],
                    'GioiTinh' => $validated['gioitinh'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $nguoiHienMauId = DB::table('NguoiHienMau')->insertGetId([
                    'PublicId' => \Illuminate\Support\Str::uuid()->toString(),
                    'NguoiDungId' => $userId,
                    'CCCD' => $validated['cccd'],
                    'NgaySinh' => $validated['ngaysinh'],
                    'GioiTinh' => $validated['gioitinh'],
                    'NhomMau' => $validated['nhom_mau'],
                    'DiaChi' => $validated['diachi'],
                    'CanNang' => $validated['can_nang'],
                    'NgheNghiep' => 'Tự do',
                    'SoLanDaHien' => 0,
                    'TrangThaiSucKhoe' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Check or insert DangKyHienMau registration
            $dk = DB::table('DangKyHienMau')
                ->where('ChuongTrinhId', $validated['chuong_trinh_id'])
                ->where('NguoiHienMauId', $nguoiHienMauId)
                ->first();

            if ($dk) {
                $dkId = $dk->Id;
            } else {
                $dkId = DB::table('DangKyHienMau')->insertGetId([
                    'ChuongTrinhId' => $validated['chuong_trinh_id'],
                    'NguoiHienMauId' => $nguoiHienMauId,
                    'ThoiGianDangKy' => now(),
                    'TrangThai' => 1,
                    'GhiChu' => 'Đăng ký nhanh từ cổng nhân viên',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Insert HoSoSucKhoe clinical pre-exam
            $hsskId = DB::table('HoSoSucKhoe')->insertGetId([
                'DangKyId' => $dkId,
                'HuyetAp' => $validated['huyet_ap'],
                'NhipTim' => $validated['nhip_tim'],
                'NhietDo' => $validated['nhiet_do'],
                'CanNang' => $validated['can_nang'],
                'Hemoglobin' => $validated['hemoglobin'],
                'KetQua' => 1, // Approved pre-donation
                'LyDoTuChoi' => '',
                'Nhommau' => $validated['nhom_mau'],
                'NguoiKham' => $validated['nguoi_kham'],
                'ThoiGianKham' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert HoSoHienMau dossier
            DB::table('HoSoHienMau')->insert([
                'NguoiHienMauId' => $nguoiHienMauId,
                'ChuongTrinhId' => $validated['chuong_trinh_id'],
                'HoSoSucKhoeId' => $hsskId,
                'LuongMau' => $validated['luong_mau'],
                'ThoiGianHien' => now(),
                'KetQuaSauHien' => $validated['ket_qua_sau_hien'],
                'GhiChu' => $validated['ghi_chu'] ?? 'Khám tuyển lâm sàng bình thường',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::commit();

            Log::info('NhanVien storeHoSo success', [
                'nguoi_hien_mau_id' => $nguoiHienMauId,
                'chuong_trinh_id' => $validated['chuong_trinh_id'],
                'luong_mau' => $validated['luong_mau'],
                'ket_qua_sau_hien' => $validated['ket_qua_sau_hien'],
            ]);

            return back()->with('success', 'Thêm mới hồ sơ sức khỏe thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('NhanVien storeHoSo failed', [
                'error' => $e->getMessage(),
                'chuong_trinh_id' => $request->input('chuong_trinh_id'),
                'email' => $request->input('email'),
                'sodienthoai' => $request->input('sodienthoai'),
            ]);
            return back()->withInput()->withErrors(['error' => 'Đã xảy ra lỗi khi lưu hồ sơ: ' . $e->getMessage()]);
        }
    }

    /**
     * Cập nhật hồ sơ hiến máu từ nhân viên.
     * Route: POST /admin/nhan-vien/ho-so/{id}/update
     * {id} = HoSoHienMau.Id
     */
    public function updateHoSo(Request $request, $id)
    {
        $validated = $request->validate([
            'luong_mau'        => 'required|integer|min:0|max:1000',
            'thoi_gian_hien'   => 'required|date',
            'ket_qua_sau_hien' => 'required|integer|in:1,2',
            'ghi_chu'          => 'nullable|string|max:1000',
            'huyet_ap'         => 'nullable|string|max:20',
            'nhip_tim'         => 'nullable|integer|min:0|max:300',
            'nhiet_do'         => 'nullable|numeric|min:30|max:45',
            'can_nang'         => 'nullable|numeric|min:20|max:300',
            'hemoglobin'       => 'nullable|numeric|min:0|max:30',
            'nguoi_kham'       => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Lấy hồ sơ hiến máu hiện tại
            $hoSo = DB::table('HoSoHienMau')->where('Id', $id)->whereNull('deleted_at')->first();
            if (!$hoSo) {
                return back()->withErrors(['error' => 'Không tìm thấy hồ sơ hiến máu.']);
            }

            // Cập nhật HoSoHienMau
            DB::table('HoSoHienMau')->where('Id', $id)->update([
                'LuongMau'       => $validated['luong_mau'],
                'ThoiGianHien'   => $validated['thoi_gian_hien'],
                'KetQuaSauHien'  => $validated['ket_qua_sau_hien'],
                'GhiChu'         => $validated['ghi_chu'] ?? '',
                'updated_at'     => now(),
            ]);

            // Cập nhật hoặc tạo mới HoSoSucKhoe
            if ($hoSo->HoSoSucKhoeId) {
                DB::table('HoSoSucKhoe')->where('Id', $hoSo->HoSoSucKhoeId)->update([
                    'HuyetAp'    => $validated['huyet_ap'] ?? null,
                    'NhipTim'    => $validated['nhip_tim'] ?? null,
                    'NhietDo'    => $validated['nhiet_do'] ?? null,
                    'CanNang'    => $validated['can_nang'] ?? null,
                    'Hemoglobin' => $validated['hemoglobin'] ?? null,
                    'NguoiKham'  => $validated['nguoi_kham'] ?? null,
                    'updated_at' => now(),
                ]);
            } else {
                // Tạo mới nếu chưa có
                $hsskId = DB::table('HoSoSucKhoe')->insertGetId([
                    'HuyetAp'       => $validated['huyet_ap'] ?? null,
                    'NhipTim'       => $validated['nhip_tim'] ?? null,
                    'NhietDo'       => $validated['nhiet_do'] ?? null,
                    'CanNang'       => $validated['can_nang'] ?? null,
                    'Hemoglobin'    => $validated['hemoglobin'] ?? null,
                    'NguoiKham'     => $validated['nguoi_kham'] ?? null,
                    'KetQua'        => 1,
                    'LyDoTuChoi'    => '',
                    'ThoiGianKham'  => now(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
                DB::table('HoSoHienMau')->where('Id', $id)->update(['HoSoSucKhoeId' => $hsskId]);
            }

            DB::commit();
            return back()->with('success', 'Cập nhật hồ sơ sức khỏe thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('NhanVien updateHoSo failed', ['id' => $id, 'error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Lỗi cập nhật hồ sơ: ' . $e->getMessage()]);
        }
    }

    /**
     * Cập nhật thông tin cá nhân của nhân viên.
     * Route: POST /admin/nhan-vien/profile/update
     */
    public function updateProfile(Request $request)
    {
        $sessionUser = session('admin_user');
        if (!$sessionUser || !isset($sessionUser['id'])) {
            return back()->withErrors(['error' => 'Vui lòng đăng nhập lại.']);
        }
        $id = $sessionUser['id'];

        $validated = $request->validate([
            'HoTen'       => 'required|string|max:255',
            'Email'       => 'required|email|max:255|unique:NguoiDung,Email,' . $id . ',Id',
            'SoDienThoai' => [
                'required',
                'regex:/^(0[235789])[0-9]{8,9}$/',
                'unique:NguoiDung,SoDienThoai,' . $id . ',Id'
            ],
            'NgaySinh'    => 'nullable|date',
            'GioiTinh'    => 'nullable|in:1,2,3',
            'MatKhau'     => 'nullable|string|min:6',
        ], [
            'HoTen.required'       => 'Họ và tên không được để trống.',
            'HoTen.string'         => 'Họ và tên phải là chuỗi ký tự.',
            'HoTen.max'            => 'Họ và tên không vượt quá 255 ký tự.',
            'Email.required'       => 'Email không được để trống.',
            'Email.email'          => 'Định dạng Email không hợp lệ (ví dụ: example@gmail.com).',
            'Email.max'            => 'Email không vượt quá 255 ký tự.',
            'Email.unique'         => 'Email này đã tồn tại trong hệ thống.',
            'SoDienThoai.required' => 'Số điện thoại không được để trống.',
            'SoDienThoai.regex'    => 'Số điện thoại chưa đúng định dạng.',
            'SoDienThoai.unique'   => 'Số điện thoại này đã tồn tại trong hệ thống.',
            'MatKhau.min'          => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $updateData = [
            'HoTen'       => $validated['HoTen'],
            'Email'       => $validated['Email'],
            'SoDienThoai' => $validated['SoDienThoai'],
            'NgaySinh'    => $validated['NgaySinh'],
            'GioiTinh'    => $validated['GioiTinh'],
            'updated_at'  => now(),
        ];

        if ($request->filled('MatKhau')) {
            $updateData['MatKhauHash'] = \Illuminate\Support\Facades\Hash::make($request->input('MatKhau'));
        }

        try {
            DB::table('NguoiDung')->where('Id', $id)->update($updateData);

            // Cập nhật session
            $sessionUser['name'] = $validated['HoTen'];
            $sessionUser['email'] = $validated['Email'];
            session(['admin_user' => $sessionUser]);

            return back()->with('success', 'Cập nhật thông tin cá nhân thành công!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Lỗi cập nhật thông tin cá nhân: ' . $e->getMessage()]);
        }
    }
}
