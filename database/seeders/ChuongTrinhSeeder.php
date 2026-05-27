<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ChuongTrinhSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy User ID Admin để làm người tạo/owner mặc định
        $admin = DB::table('NguoiDung')->where('Email', 'admin@system.com')->first();
        if (!$admin) {
            return;
        }

        $userId = $admin->Id;

        // 1. Tạo các Đơn Vị Tổ Chức
        $donVis = [
            [
                'TenDonVi' => 'Bệnh viện Huyết học Truyền máu TW',
                'MaDonVi' => 'BVHH-TW',
                'Loai' => 'Bệnh viện',
                'Email' => 'contact@nihbt.org.vn',
                'SoDienThoai' => '02438686009',
                'DiaChi' => 'Phố Phạm Văn Bạch, Yên Hòa, Cầu Giấy, Hà Nội',
                'MoTa' => 'Viện Huyết học - Truyền máu Trung ương là Viện đầu ngành về chuyên khoa Huyết học và Truyền máu.',
                'NguoiDaiDien' => 'Nguyễn Hà Thanh',
                'TrangThai' => 1,
                'HinhAnh' => 'https://images.unsplash.com/photo-1586773860418-d3b978b736e1?auto=format&fit=crop&q=80&w=600',
                'OwnerUserId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TenDonVi' => 'Đoàn Thanh niên ĐH Y Hà Nội',
                'MaDonVi' => 'DTN-DHYHN',
                'Loai' => 'Đoàn thể',
                'Email' => 'doanthanhnien@hmu.edu.vn',
                'SoDienThoai' => '02438523798',
                'DiaChi' => 'Số 1 Tôn Thất Tùng, Kim Liên, Đống Đa, Hà Nội',
                'MoTa' => 'Đoàn Thanh niên Cộng sản Hồ Chí Minh Trường Đại học Y Hà Nội.',
                'NguoiDaiDien' => 'Vũ Quốc Đạt',
                'TrangThai' => 1,
                'HinhAnh' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&q=80&w=600',
                'OwnerUserId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TenDonVi' => 'Hội Chữ thập đỏ TP. Hồ Chí Minh',
                'MaDonVi' => 'HCTD-TPHCM',
                'Loai' => 'Hội từ thiện',
                'Email' => 'info@redcrossthcm.org.vn',
                'SoDienThoai' => '02838291583',
                'DiaChi' => '201 Nguyễn Thị Minh Khai, Quận 1, TP. Hồ Chí Minh',
                'MoTa' => 'Hội Chữ thập đỏ Thành phố Hồ Chí Minh là tổ chức xã hội nhân đạo của quần chúng.',
                'NguoiDaiDien' => 'Trần Trường Sơn',
                'TrangThai' => 1,
                'HinhAnh' => 'https://images.unsplash.com/photo-1504159506876-f8338247a14a?auto=format&fit=crop&q=80&w=600',
                'OwnerUserId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TenDonVi' => 'Trường Cao đẳng Y tế Cần Thơ',
                'MaDonVi' => 'CDYT-CT',
                'Loai' => 'Trường học',
                'Email' => 'cdytct@mcc.edu.vn',
                'SoDienThoai' => '02923831565',
                'DiaChi' => '340 Nguyễn Văn Cừ, An Khánh, Ninh Kiều, Cần Thơ',
                'MoTa' => 'Trường đào tạo cán bộ y tế có chất lượng và uy tín tại Đồng bằng sông Cửu Long.',
                'NguoiDaiDien' => 'Nguyễn Minh Phương',
                'TrangThai' => 1,
                'HinhAnh' => 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&q=80&w=600',
                'OwnerUserId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'TenDonVi' => 'Công ty TNHH Dược phẩm ABC',
                'MaDonVi' => 'CT-DPABC',
                'Loai' => 'Doanh nghiệp',
                'Email' => 'contact@abcpharma.vn',
                'SoDienThoai' => '02253842910',
                'DiaChi' => 'KCN Đình Vũ, Đông Hải 2, Hải An, Hải Phòng',
                'MoTa' => 'Đơn vị sản xuất và phân phối các chế phẩm y tế hàng đầu.',
                'NguoiDaiDien' => 'Trần Văn B',
                'TrangThai' => 1,
                'HinhAnh' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80&w=600',
                'OwnerUserId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($donVis as $dv) {
            DB::table('DonViToChuc')->updateOrInsert(
                ['MaDonVi' => $dv['MaDonVi']],
                $dv
            );
        }

        // Lấy lại danh sách đơn vị để lấy Id chính xác
        $dbDonVis = DB::table('DonViToChuc')->pluck('Id', 'MaDonVi')->toArray();

        // 2. Tạo các Chương Trình Hiến Máu
        $chuongTrinhs = [
            [
                'PublicId' => (string) Str::uuid(),
                'TenChuongTrinh' => 'Giọt hồng yêu thương 2025',
                'MoTa' => 'Chung tay chia sẻ giọt máu - Kết nối yêu thương',
                'Banner' => 'linear-gradient(135deg, #FF512F 0%, #DD2476 100%)',
                'DonViToChucId' => $dbDonVis['BVHH-TW'],
                'DiaChi' => 'Hà Nội',
                'BanDo' => 'https://maps.app.goo.gl/tS7p2h9Lp1V7yQn48',
                'ThoiGianBatDau' => Carbon::create(2025, 6, 20, 8, 0, 0),
                'ThoiGianKetThuc' => Carbon::create(2025, 6, 20, 16, 0, 0),
                'ThoiGianMoDangKy' => Carbon::create(2025, 6, 1, 0, 0, 0),
                'DangDienRa' => false,
                'SoLuongDuKien' => 200,
                'TrangThai' => 2, // Đã duyệt
                'NguoiTaoId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PublicId' => (string) Str::uuid(),
                'TenChuongTrinh' => 'Hiến máu nhân đạo đợt 1',
                'MoTa' => 'Hiến giọt máu đào - Trao đời sự sống',
                'Banner' => 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
                'DonViToChucId' => $dbDonVis['DTN-DHYHN'],
                'DiaChi' => 'Đà Nẵng',
                'BanDo' => 'https://maps.app.goo.gl/hmu1',
                'ThoiGianBatDau' => Carbon::create(2025, 6, 18, 7, 30, 0),
                'ThoiGianKetThuc' => Carbon::create(2025, 6, 18, 15, 30, 0),
                'ThoiGianMoDangKy' => Carbon::create(2025, 6, 1, 0, 0, 0),
                'DangDienRa' => true, // Đang diễn ra
                'SoLuongDuKien' => 180,
                'TrangThai' => 3, // Đang diễn ra (hoặc trạng thái tương ứng)
                'NguoiTaoId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PublicId' => (string) Str::uuid(),
                'TenChuongTrinh' => 'Trao giọt máu - Trao yêu thương',
                'MoTa' => 'Mỗi giọt máu cho đi - Một cuộc đời ở lại',
                'Banner' => 'linear-gradient(135deg, #f857a6 0%, #ff5858 100%)',
                'DonViToChucId' => $dbDonVis['HCTD-TPHCM'],
                'DiaChi' => 'TP. Hồ Chí Minh',
                'BanDo' => 'https://maps.app.goo.gl/hctd-hcm',
                'ThoiGianBatDau' => Carbon::create(2025, 6, 25, 8, 0, 0),
                'ThoiGianKetThuc' => Carbon::create(2025, 6, 25, 16, 0, 0),
                'ThoiGianMoDangKy' => Carbon::create(2025, 6, 10, 0, 0, 0),
                'DangDienRa' => false,
                'SoLuongDuKien' => 250,
                'TrangThai' => 1, // Chờ duyệt
                'NguoiTaoId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PublicId' => (string) Str::uuid(),
                'TenChuongTrinh' => 'Mùa hè nhân ái 2025',
                'MoTa' => 'Hiến máu hôm nay - Sức khỏe ngày mai',
                'Banner' => 'linear-gradient(135deg, #4b6cb7 0%, #182848 100%)',
                'DonViToChucId' => $dbDonVis['CDYT-CT'],
                'DiaChi' => 'Cần Thơ',
                'BanDo' => 'https://maps.app.goo.gl/ct-med',
                'ThoiGianBatDau' => Carbon::create(2025, 6, 28, 7, 0, 0),
                'ThoiGianKetThuc' => Carbon::create(2025, 6, 28, 15, 0, 0),
                'ThoiGianMoDangKy' => Carbon::create(2025, 6, 15, 0, 0, 0),
                'DangDienRa' => false,
                'SoLuongDuKien' => 200,
                'TrangThai' => 4, // Đã hủy
                'NguoiTaoId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'PublicId' => (string) Str::uuid(),
                'TenChuongTrinh' => 'Hiến máu cứu người - Hành động đẹp',
                'MoTa' => 'Hiến máu cứu người - Một nghĩa cử cao đẹp',
                'Banner' => 'linear-gradient(135deg, #e65c00 0%, #F9D423 100%)',
                'DonViToChucId' => $dbDonVis['CT-DPABC'],
                'DiaChi' => 'Hải Phòng',
                'BanDo' => 'https://maps.app.goo.gl/abc-pharm',
                'ThoiGianBatDau' => Carbon::create(2025, 7, 5, 8, 0, 0),
                'ThoiGianKetThuc' => Carbon::create(2025, 7, 5, 16, 0, 0),
                'ThoiGianMoDangKy' => Carbon::create(2025, 6, 20, 0, 0, 0),
                'DangDienRa' => false,
                'SoLuongDuKien' => 150,
                'TrangThai' => 5, // Đã kết thúc
                'NguoiTaoId' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($chuongTrinhs as $ct) {
            $existing = DB::table('ChuongTrinhHienMau')
                ->where('TenChuongTrinh', $ct['TenChuongTrinh'])
                ->first();

            if (!$existing) {
                // Thêm số người đăng ký giả vào bảng đăng ký hoặc giả lập
                $id = DB::table('ChuongTrinhHienMau')->insertGetId($ct);

                // Giả lập một số Đăng ký hiến máu để khớp với tỷ lệ phần trăm đăng ký của hình ảnh
                // 1. Giọt hồng yêu thương 2025: 150 đăng ký
                // 2. Hiến máu nhân đạo đợt 1: 180 đăng ký
                // 3. Trao giọt máu - Trao yêu thương: 120 đăng ký
                // 4. Mùa hè nhân ái 2025: 0 đăng ký
                // 5. Hiến máu cứu người - Hành động đẹp: 60 đăng ký
            }
        }
    }
}
