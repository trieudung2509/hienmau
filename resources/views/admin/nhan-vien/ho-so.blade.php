@extends('admin.nhan-vien.layout')

@section('title', 'Quản lý hồ sơ sức khỏe | Cổng thông tin Hiến Máu Tình Nguyện')
@section('navbar_title', 'Quản lý hồ sơ sức khỏe')

@section('content')
    <!-- Title -->
    <div class="greeting-section">
        <h2 class="greeting-title">Hồ sơ sức khỏe người hiến máu</h2>
        <span class="greeting-subtitle">Tra cứu thông tin kiểm tra sức khỏe và kết quả hiến máu của người tham gia</span>
    </div>

    <style>
        .btn-table-action.btn-edit:hover {
            background-color: var(--warning-light);
            border-color: rgba(245, 158, 11, 0.3);
            color: var(--warning);
        }
    </style>

    @if (session('success'))
        <div class="alert alert-success" style="margin-bottom: 16px; background-color: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #0f766e; padding: 14px 16px; border-radius: 12px; font-weight: 600; font-size: 14px;">
            <strong>Thành công:</strong> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 16px; background-color: var(--danger-light); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--danger); padding: 14px 16px; border-radius: 12px; font-weight: 600; font-size: 14px;">
            <strong>Vui lòng kiểm tra lại thông tin:</strong>
            <ul style="margin: 8px 0 0 18px;">
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Stats grid -->
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Tổng số hồ sơ</span>
                <span class="stat-value">{{ $metrics['tong_ho_so'] }}</span>
                <span class="stat-label">Hệ thống</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Hiến máu thành công</span>
                <span class="stat-value">{{ $metrics['thanh_cong'] }}</span>
                <span class="stat-label">Đạt điều kiện</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon orange">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Lượng máu đã thu</span>
                <span class="stat-value" style="color: var(--warning);">{{ $metrics['tong_luong_mau'] }} <span style="font-size: 14px; font-weight: 700;">ml</span></span>
                <span class="stat-label">Tổng thể tích</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon red">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Đã hủy đăng ký</span>
                <span class="stat-value" style="color: var(--danger);">{{ $metrics['da_huy'] }}</span>
                <span class="stat-label">Lượt hủy trong kết quả này</span>
            </div>
        </div>
    </section>

    <!-- FILTERS PANEL -->
    <section class="filter-card">
        <h3 class="filter-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
            </svg>
            Bộ lọc tìm kiếm nâng cao
        </h3>
        
        <form action="{{ route('nhan-vien.ho-so') }}" method="GET" class="filter-grid-form">
            <div class="form-group-filter">
                <label for="search" class="filter-label">Tìm theo Họ tên, Số điện thoại hoặc Email</label>
                <div class="filter-input-wrapper">
                    <svg class="filter-input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                    </svg>
                    <input type="text" id="search" name="search" class="filter-input" placeholder="Nhập từ khóa tìm kiếm..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="form-group-filter">
                <label for="chuong_trinh_id" class="filter-label">Chương trình hiến máu</label>
                <div class="filter-input-wrapper">
                    <select id="chuong_trinh_id" name="chuong_trinh_id" class="filter-input filter-select">
                        <option value="">Tất cả chương trình</option>
                        @foreach ($programsList as $prog)
                            <option value="{{ $prog->Id }}" {{ request('chuong_trinh_id') == $prog->Id ? 'selected' : '' }}>
                                        {{ $prog->TenChuongTrinh }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="filter-btn-group">
                <button type="submit" class="btn-filter-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                    </svg>
                    Tìm kiếm
                </button>
                <a href="{{ route('nhan-vien.ho-so') }}" class="btn-filter-reset">
                            Làm mới
                </a>
            </div>
        </form>
    </section>

    <!-- RESULTS LIST -->
    <section class="bottom-section">
        <header class="bottom-header">
            <h3 class="bottom-title">Kết quả tìm kiếm ({{ count($records) }})</h3>
        </header>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">STT</th>
                        <th>Người hiến máu</th>
                        <th>Chương trình</th>
                        <th>Lượng máu</th>
                        <th>Thời gian hiến</th>
                        <th>Kết quả sau hiến</th>
                        <th>Trạng thái ĐK</th>
                        <th style="width: 120px; text-align: center;">Chi tiết</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $index => $r)
                        <tr class="{{ $r->DangKyTrangThai == 0 ? 'row-cancelled' : '' }}">
                            <td class="stt-cell">{{ $index + 1 }}</td>
                                    
                            <td>
                                <div class="user-cell">
                                    <div class="user-table-avatar color-{{ ($index + 1) % 5 }}">
                                        <span>{{ mb_substr($r->HoTen, 0, 1) }}</span>
                                    </div>
                                    <div class="user-info-text">
                                        <span class="user-name-text">{{ $r->HoTen }}</span>
                                        <span class="user-dob-text">{{ $r->GioiTinh }} • {{ \Carbon\Carbon::parse($r->NgaySinh)->timezone(config('app.timezone'))->format('d/m/Y') }}</span>
                                        <span class="email-text">{{ $r->SoDienThoai }} • {{ $r->Email }}</span>
                                    </div>
                                </div>
                            </td>
                                    
                            <td style="max-width: 300px; font-weight: 500;">
                                {{ $r->TenChuongTrinh }}
                            </td>
                                    
                            <td>
                                @if ($r->LuongMau)
                                    <span class="volume-badge" style="margin-left: 0;">{{ $r->LuongMau }} ml</span>
                                @else
                                    <span style="color: var(--neutral-grey); font-style: italic;">Chưa hiến</span>
                                @endif
                            </td>
                                    
                            <td>
                                @if ($r->ThoiGianHien)
                                    <span style="font-weight: 600;">{{ \Carbon\Carbon::parse($r->ThoiGianHien)->timezone(config('app.timezone'))->format('H:i d/m/Y') }}</span>
                                @else
                                    <span style="color: var(--neutral-grey); font-style: italic;">-</span>
                                @endif
                            </td>
                                    
                            <td>
                                @if ($r->KetQuaSauHien == 1)
                                    <span class="badge badge-success">Thành công</span>
                                @elseif($r->KetQuaSauHien == 2)
                                    <span class="badge badge-danger">Thất bại / Hủy</span>
                                @else
                                    <span class="badge" style="background-color: #f1f5f9; color: var(--neutral-grey);">Không xác định</span>
                                @endif
                            </td>

                            {{-- Registration status --}}
                            <td>
                                @if ($r->DangKyTrangThai == 0)
                                    <span class="badge badge-danger">Đã hủy</span>
                                @elseif ($r->KetQuaSauHien == 1)
                                    <span class="badge badge-success">Đã hiến thành công</span>
                                @elseif ($r->KetQuaSauHien == 2)
                                    <span class="badge badge-danger">Không đủ đk</span>
                                @elseif ($r->HoSoId)
                                    <span class="badge badge-warning">Đang xử lý</span>
                                @else
                                    <span class="badge badge-primary">Chờ hiến</span>
                                @endif
                            </td>
                                    
                            <td>
                                <div class="actions-cell" style="justify-content: center;">
                                    <button class="btn-table-action btn-view" title="Xem chi tiết hồ sơ" data-record="{{ json_encode($r) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.755 2.066 2.066 0 011.852-1.15h16.224a2.066 2.066 0 011.853 1.15 1.012 1.012 0 010 .755 2.066 2.066 0 01-1.853 1.15H3.888a2.066 2.066 0 01-1.852-1.15z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    @if ($r->HoSoId)
                                    <button class="btn-table-action btn-edit btn-edit-hoso" title="Chỉnh sửa hồ sơ"
                                            data-id="{{ $r->HoSoId }}"
                                            data-record="{{ json_encode($r) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 48px; color: var(--neutral-grey);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.5;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                <p style="font-weight: 600; font-size: 15px;">Không tìm thấy hồ sơ sức khỏe nào</p>
                                <p style="font-size: 13px; margin-top: 4px;">Vui lòng thử lại với các tiêu chí tìm kiếm khác.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TABLE FOOTER -->
        <footer class="table-footer">
            <span class="footer-info">
                Hiển thị 1 - {{ count($records) }} trong tổng số {{ count($records) }} hồ sơ
            </span>
            
            <div class="pagination-row">
                <ul class="pagination-list">
                    <li class="page-item disabled">
                        <span>&lt;</span>
                    </li>
                    <li class="page-item active">
                        <a href="#">1</a>
                    </li>
                    <li class="page-item disabled">
                        <span>&gt;</span>
                    </li>
                </ul>
            </div>
        </footer>
    </section>

    <!-- PREMIUM DETAILED HEALTH MODAL -->
    <div id="healthModal" class="modal-backdrop">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-header-profile">
                    <div class="modal-avatar" id="mAvatar">?</div>
                    <div class="modal-name-wrap">
                        <span class="modal-name" id="mName">Nguyễn Văn A</span>
                        <span class="modal-sub" id="mMeta">Nam • 01/01/1990</span>
                    </div>
                </div>
                <button class="btn-modal-close" onclick="closeModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="modal-body">
                {{-- Cancelled registration alert banner --}}
                <div id="mCancelledBanner" style="display:none; align-items: center; gap: 10px; background: rgba(254,242,242,1); border: 1px solid rgba(239,68,68,0.25); border-radius: 10px; padding: 12px 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width:20px;height:20px;color:#ef4444;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    <div>
                        <span style="font-size:13px; font-weight:700; color:#b91c1c;">Hồ sơ này đã bị hủy đăng ký</span>
                        <span style="font-size:12px; color:#ef4444; display:block;">Người hiến máu đã rút khỏi chương trình này.</span>
                    </div>
                </div>

                <!-- SECTION 1: Personal Dossier Info -->
                <div>
                    <h4 class="modal-section-title">Thông tin cơ bản</h4>
                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-item-label">Số điện thoại</span>
                            <span class="detail-item-value" id="mPhone">090 123 4567</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Thư điện tử (Email)</span>
                            <span class="detail-item-value" id="mEmail">nguyenvana@gmail.com</span>
                        </div>
                        <div class="detail-item" style="grid-column: span 2;">
                            <span class="detail-item-label">Chương trình tham gia</span>
                            <span class="detail-item-value" id="mProgram" style="color: var(--primary);">Giọt hồng yêu thương 2025</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Physical Medical Examination Details -->
                <div>
                    <h4 class="modal-section-title">Kết quả kiểm tra sức khỏe trước hiến</h4>
                    <div class="health-grid">
                        <!-- Blood Pressure -->
                        <div class="health-card">
                            <div class="health-card-icon red">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span class="health-card-title">Huyết áp</span>
                            <span class="health-card-value" id="mHuyetAp">120/80 mmHg</span>
                        </div>

                        <!-- Heart Rate -->
                        <div class="health-card">
                            <div class="health-card-icon purple">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="health-card-title">Nhịp tim</span>
                            <span class="health-card-value" id="mNhipTim">76 bpm</span>
                        </div>

                        <!-- Weight -->
                        <div class="health-card">
                            <div class="health-card-icon yellow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                </svg>
                            </div>
                            <span class="health-card-title">Cân nặng</span>
                            <span class="health-card-value" id="mCanNang">68.5 kg</span>
                        </div>

                        <!-- Temperature -->
                        <div class="health-card">
                            <div class="health-card-icon emerald">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="health-card-title">Nhiệt độ</span>
                            <span class="health-card-value" id="mNhietDo">36.5 °C</span>
                        </div>

                        <!-- Hemoglobin -->
                        <div class="health-card">
                            <div class="health-card-icon blue">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <span class="health-card-title">Hemoglobin</span>
                            <span class="health-card-value" id="mHemoglobin">14.2 g/dL</span>
                        </div>

                        <!-- Blood Group -->
                        <div class="health-card">
                            <div class="health-card-icon red" style="background-color: rgba(239, 68, 68, 0.08);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="health-card-title">Nhóm máu</span>
                            <span class="health-card-value" id="mNhomMau" style="color: var(--danger); font-weight: 800;">A+</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Donation Outcome & Examiners -->
                <div>
                    <h4 class="modal-section-title">Kết quả & Thông tin khám</h4>
                    <div class="details-grid" style="margin-bottom: 12px;">
                        <div class="detail-item">
                            <span class="detail-item-label">Bác sĩ / Người khám</span>
                            <span class="detail-item-value" id="mNguoiKham">Bác sĩ khám tuyển</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Kết quả hiến máu</span>
                            <span class="detail-item-value" id="mKetQua">Thành công</span>
                        </div>
                    </div>
                    
                    <div class="note-card" id="mGhiChuWrapper">
                        <span class="note-card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Ghi chú sức khỏe / Khám lâm sàng
                        </span>
                        <p class="note-card-value" id="mGhiChu">Không có ghi chú đặc biệt.</p>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn-modal-close-action" onclick="closeModal()">Đóng cửa sổ</button>
            </div>
        </div>
    </div>

    <!-- EDIT HEALTH MODAL -->
    <div id="editHoSoModal" class="modal-backdrop">
        <div class="modal-content" style="max-width: 650px;">
            <div class="modal-header">
                <div class="modal-header-profile">
                    <div class="modal-avatar" id="edit_mAvatar" style="background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%);">?</div>
                    <div class="modal-name-wrap">
                        <span class="modal-name" id="edit_mName">Chỉnh sửa hồ sơ sức khỏe</span>
                        <span class="modal-sub" id="edit_mMeta">Người hiến: ...</span>
                    </div>
                </div>
                <button class="btn-modal-close" onclick="closeEditModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="editHoSoForm" method="POST">
                @csrf
                <div class="modal-body" style="gap: 16px; max-height: 70vh; overflow-y: auto; padding: 20px;">
                    <!-- Section: Physical exam -->
                    <div>
                        <h4 class="modal-section-title">Khám sức khỏe trước hiến</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div class="form-group-filter">
                                <label for="edit_huyet_ap" class="filter-label">Huyết áp (mmHg)</label>
                                <input type="text" id="edit_huyet_ap" name="huyet_ap" class="filter-input" style="padding-left: 14px;" placeholder="Ví dụ: 120/80">
                            </div>
                            <div class="form-group-filter">
                                <label for="edit_nhip_tim" class="filter-label">Nhịp tim (bpm)</label>
                                <input type="number" id="edit_nhip_tim" name="nhip_tim" class="filter-input" style="padding-left: 14px;" placeholder="bpm">
                            </div>
                            <div class="form-group-filter">
                                <label for="edit_nhiet_do" class="filter-label">Nhiệt độ (°C)</label>
                                <input type="number" step="0.1" id="edit_nhiet_do" name="nhiet_do" class="filter-input" style="padding-left: 14px;" placeholder="°C">
                            </div>
                            <div class="form-group-filter">
                                <label for="edit_can_nang" class="filter-label">Cân nặng (kg)</label>
                                <input type="number" step="0.1" id="edit_can_nang" name="can_nang" class="filter-input" style="padding-left: 14px;" placeholder="kg">
                            </div>
                            <div class="form-group-filter">
                                <label for="edit_hemoglobin" class="filter-label">Hemoglobin (g/dL)</label>
                                <input type="number" step="0.1" id="edit_hemoglobin" name="hemoglobin" class="filter-input" style="padding-left: 14px;" placeholder="g/dL">
                            </div>
                            <div class="form-group-filter">
                                <label for="edit_nguoi_kham" class="filter-label">Bác sĩ khám tuyển</label>
                                <input type="text" id="edit_nguoi_kham" name="nguoi_kham" class="filter-input" style="padding-left: 14px;" placeholder="Tên bác sĩ">
                            </div>
                        </div>
                    </div>

                    <!-- Section: Outcome & Info -->
                    <div style="margin-top: 16px;">
                        <h4 class="modal-section-title">Kết quả & Lượng máu hiến</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div class="form-group-filter">
                                <label for="edit_luong_mau" class="filter-label">Lượng máu hiến <span style="color: var(--danger);">*</span></label>
                                <select id="edit_luong_mau" name="luong_mau" class="filter-input filter-select" style="padding-left: 14px; background-color: var(--neutral-light);" required>
                                    <option value="350">350 ml</option>
                                    <option value="250">250 ml</option>
                                    <option value="450">450 ml</option>
                                    <option value="0">0 ml (Không hiến)</option>
                                </select>
                            </div>
                            <div class="form-group-filter">
                                <label for="edit_ket_qua_sau_hien" class="filter-label">Kết quả hiến máu <span style="color: var(--danger);">*</span></label>
                                <select id="edit_ket_qua_sau_hien" name="ket_qua_sau_hien" class="filter-input filter-select" style="padding-left: 14px; background-color: var(--neutral-light);" required>
                                    <option value="1">Thành công</option>
                                    <option value="2">Thất bại / Hủy</option>
                                </select>
                            </div>
                            <div class="form-group-filter" style="grid-column: span 2;">
                                <label for="edit_thoi_gian_hien" class="filter-label">Thời gian thực hiện <span style="color: var(--danger);">*</span></label>
                                <input type="datetime-local" id="edit_thoi_gian_hien" name="thoi_gian_hien" class="filter-input" style="padding-left: 14px;" required>
                            </div>
                            <div class="form-group-filter" style="grid-column: span 2;">
                                <label for="edit_ghi_chu" class="filter-label">Ghi chú lâm sàng / Lý do hủy</label>
                                <textarea id="edit_ghi_chu" name="ghi_chu" class="filter-input" style="padding-left: 14px; height: 60px; padding-top: 8px; resize: none;" placeholder="Nhập ghi chú..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-filter-reset" onclick="closeEditModal()" style="height: 40px;">Hủy bỏ</button>
                    <button type="submit" class="btn-filter-submit" style="height: 40px; margin-left: 8px; background: linear-gradient(135deg, var(--warning) 0%, #d97706 100%); border-color: var(--warning);">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- MODAL LOGIC JAVASCRIPT -->
    <script>
        const modal = document.getElementById('healthModal');
        const editModal = document.getElementById('editHoSoModal');

        // Attach click handlers to eye buttons
        document.querySelectorAll('.btn-view').forEach(button => {
            button.addEventListener('click', function() {
                try {
                    const record = JSON.parse(this.getAttribute('data-record'));
                    openModal(record);
                } catch (e) {
                    console.error('Error parsing record JSON data', e);
                }
            });
        });

        // Attach click handlers to pencil/edit buttons
        document.querySelectorAll('.btn-edit-hoso').forEach(button => {
            button.addEventListener('click', function() {
                try {
                    const record = JSON.parse(this.getAttribute('data-record'));
                    const id = this.getAttribute('data-id');
                    openEditModal(record, id);
                } catch (e) {
                    console.error('Error parsing record JSON data', e);
                }
            });
        });

        function openModal(record) {
            // Show/hide cancelled banner
            const banner = document.getElementById('mCancelledBanner');
            if (record.DangKyTrangThai == 0) {
                banner.style.display = 'flex';
            } else {
                banner.style.display = 'none';
            }

            // Populate basic header
            document.getElementById('mAvatar').innerText = record.HoTen ? record.HoTen.charAt(0) : '?';
            document.getElementById('mName').innerText = record.HoTen || 'Chưa cập nhật';
            
            // Format DOB
            let dobFormatted = 'Chưa cập nhật';
            if (record.NgaySinh) {
                const date = new Date(record.NgaySinh);
                if (!isNaN(date.getTime())) {
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    dobFormatted = `${day}/${month}/${year}`;
                }
            }
            // Normalize gender
            let gender = 'Chưa rõ';
            if (record.GioiTinh == 1 || record.GioiTinh === 'Nam') gender = 'Nam';
            else if (record.GioiTinh == 2 || record.GioiTinh === 'Nữ') gender = 'Nữ';
            document.getElementById('mMeta').innerText = `${gender} • ${dobFormatted}`;

            // Details
            document.getElementById('mPhone').innerText = record.SoDienThoai || 'Chưa cập nhật';
            document.getElementById('mEmail').innerText = record.Email || 'Chưa cập nhật';
            document.getElementById('mProgram').innerText = record.TenChuongTrinh || 'Chưa cập nhật';

            // Health parameters before donation
            document.getElementById('mHuyetAp').innerText = record.HuyetAp ? `${record.HuyetAp} mmHg` : 'Chưa đo';
            document.getElementById('mNhipTim').innerText = record.NhipTim ? `${record.NhipTim} bpm` : 'Chưa đo';
            document.getElementById('mCanNang').innerText = record.CanNang ? `${record.CanNang} kg` : 'Chưa đo';
            document.getElementById('mNhietDo').innerText = record.NhietDo ? `${record.NhietDo} °C` : 'Chưa đo';
            document.getElementById('mHemoglobin').innerText = record.Hemoglobin ? `${record.Hemoglobin} g/dL` : 'Chưa đo';
            document.getElementById('mNhomMau').innerText = record.NhomMau || 'Chưa rõ';

            // Outcome & Examiner
            document.getElementById('mNguoiKham').innerText = record.NguoiKham || 'Bác sĩ trực khám';
            
            let statusText = 'Chưa xác định';
            if (record.KetQuaSauHien == 1) {
                statusText = 'Hiến máu thành công';
                if (record.LuongMau) {
                    statusText += ` (${record.LuongMau} ml)`;
                }
            } else if (record.KetQuaSauHien == 2) {
                statusText = 'Không đủ điều kiện hiến';
            }
            document.getElementById('mKetQua').innerText = statusText;

            // Ghi chú
            if (record.GhiChu) {
                document.getElementById('mGhiChu').innerText = record.GhiChu;
                document.getElementById('mGhiChuWrapper').style.display = 'flex';
            } else {
                document.getElementById('mGhiChu').innerText = 'Sức khỏe tốt, không có biểu hiện bất thường lâm sàng.';
                document.getElementById('mGhiChuWrapper').style.display = 'flex';
            }

            // Open backdrop
            modal.classList.add('open');
        }

        function openEditModal(record, id) {
            // Set form action
            document.getElementById('editHoSoForm').action = `/admin/nhan-vien/ho-so/${id}/update`;

            // Populate header info
            document.getElementById('edit_mAvatar').innerText = record.HoTen ? record.HoTen.charAt(0) : '?';
            document.getElementById('edit_mName').innerText = record.HoTen || 'Chưa cập nhật';
            
            // Format DOB
            let dobFormatted = 'Chưa cập nhật';
            if (record.NgaySinh) {
                const date = new Date(record.NgaySinh);
                if (!isNaN(date.getTime())) {
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    dobFormatted = `${day}/${month}/${year}`;
                }
            }
            // Normalize gender
            let gender = 'Chưa rõ';
            if (record.GioiTinh == 1 || record.GioiTinh === 'Nam') gender = 'Nam';
            else if (record.GioiTinh == 2 || record.GioiTinh === 'Nữ') gender = 'Nữ';
            document.getElementById('edit_mMeta').innerText = `Người hiến: ${gender} • ${dobFormatted}`;

            // Populate inputs
            document.getElementById('edit_huyet_ap').value = record.HuyetAp || '';
            document.getElementById('edit_nhip_tim').value = record.NhipTim || '';
            document.getElementById('edit_nhiet_do').value = record.NhietDo || '';
            document.getElementById('edit_can_nang').value = record.CanNang || '';
            document.getElementById('edit_hemoglobin').value = record.Hemoglobin || '';
            document.getElementById('edit_nguoi_kham').value = record.NguoiKham || '';

            document.getElementById('edit_luong_mau').value = record.LuongMau !== null ? record.LuongMau : '350';
            document.getElementById('edit_ket_qua_sau_hien').value = record.KetQuaSauHien || '1';
            document.getElementById('edit_ghi_chu').value = record.GhiChu || '';

            // Format ThoiGianHien
            if (record.ThoiGianHien) {
                const d = new Date(record.ThoiGianHien);
                if (!isNaN(d.getTime())) {
                    const year = d.getFullYear();
                    const month = String(d.getMonth() + 1).padStart(2, '0');
                    const day = String(d.getDate()).padStart(2, '0');
                    const hours = String(d.getHours()).padStart(2, '0');
                    const minutes = String(d.getMinutes()).padStart(2, '0');
                    document.getElementById('edit_thoi_gian_hien').value = `${year}-${month}-${day}T${hours}:${minutes}`;
                } else {
                    document.getElementById('edit_thoi_gian_hien').value = '';
                }
            } else {
                const now = new Date();
                const year = now.getFullYear();
                const month = String(now.getMonth() + 1).padStart(2, '0');
                const day = String(now.getDate()).padStart(2, '0');
                const hours = String(now.getHours()).padStart(2, '0');
                const minutes = String(now.getMinutes()).padStart(2, '0');
                document.getElementById('edit_thoi_gian_hien').value = `${year}-${month}-${day}T${hours}:${minutes}`;
            }

            editModal.classList.add('open');
        }

        function closeModal() {
            modal.classList.remove('open');
        }

        function closeEditModal() {
            editModal.classList.remove('open');
        }

        // Close when clicking outside modal body content
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        editModal.addEventListener('click', function(e) {
            if (e.target === editModal) {
                closeEditModal();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                closeEditModal();
            }
        });
    </script>
@endsection
