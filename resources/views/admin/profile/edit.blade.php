@php
    $theme = [
        'role' => $role ?? 'donor',
        'userName' => $userName ?? 'Nguoi dung',
        'userRole' => $userRole ?? 'Khach',
    ];

    if (($role ?? '') === 'admin') {
        $theme = array_merge($theme, [
            'sidebarClass' => 'sidebar-dark',
            'primaryColor' => '#2563eb',
            'primaryHoverColor' => '#1d4ed8',
            'primaryLightColor' => '#eff6ff',
            'sidebarBg' => '#111c43',
            'sidebarActive' => '#2563eb',
            'bodyBg' => '#f3f6ff',
        ]);
    } elseif (($role ?? '') === 'nhan-vien') {
        $theme = array_merge($theme, [
            'sidebarClass' => 'sidebar-dark',
            'primaryColor' => '#2563eb',
            'primaryHoverColor' => '#1d4ed8',
            'primaryLightColor' => '#eff6ff',
            'sidebarBg' => '#111c43',
            'sidebarActive' => '#2563eb',
            'bodyBg' => '#f6f8fb',
        ]);
    } else {
        $theme = array_merge($theme, [
            'sidebarClass' => 'sidebar-light',
            'primaryColor' => '#ef4444',
            'primaryHoverColor' => '#dc2626',
            'primaryLightColor' => '#fef2f2',
            'sidebarBg' => '#ffffff',
            'sidebarActive' => '#fee2e2',
            'bodyBg' => '#f6f8fb',
        ]);
    }
@endphp

@extends(($role ?? '') === 'donor' ? 'frontend.layouts.app' : 'admin.layouts.dashboard', $theme)

@section('title', 'Thông tin cá nhân')
@section('navbar-title', 'Thông tin cá nhân')
@section('navbar-subtitle', 'Quản lý thông tin tài khoản và mặt khẩu.')

@push('styles')
<style>
    .profile-page {
        --primary: {{ ($role ?? '') === 'donor' ? '#e53935' : ($theme['primaryColor'] ?? '#2563eb') }};
        --primary-light: {{ ($role ?? '') === 'donor' ? '#fdf2f2' : ($theme['primaryLightColor'] ?? '#eff6ff') }};
        --primary-hover: {{ ($role ?? '') === 'donor' ? '#b71c1c' : ($theme['primaryHoverColor'] ?? '#1d4ed8') }};
        padding: 28px 32px;
        display: grid;
        gap: 24px;
    }

    .profile-grid {
        display: grid;
        gap: 24px;
    }

    @media (min-width: 992px) {
        .profile-grid {
            grid-template-columns: 1fr 1fr;
            align-items: start;
        }
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
    }

    @media (min-width: 576px) {
        .form-row {
            grid-template-columns: 1fr 1fr;
        }
    }

    .exam-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background-color: var(--primary-light);
        color: var(--primary);
        font-size: 12px;
        font-weight: 600;
        padding: 6px 12px;
        border-radius: 20px;
        margin-bottom: 16px;
    }

    .card {
        background-color: #fff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid var(--border-color);
        box-shadow: var(--shadow-sm);
    }

    .card-title {
        font-family: var(--font-heading);
        font-size: 18px;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .card-subtitle {
        color: var(--neutral-grey);
        font-size: 13px;
        margin-bottom: 18px;
    }

    .alert-success {
        background-color: var(--success-light);
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #065f46;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .alert-error {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 12px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 16px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--neutral-grey);
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .form-input {
        height: 44px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
        background-color: var(--neutral-light);
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 500;
        outline: none;
        transition: all 0.2s ease;
        font-family: var(--font-main);
    }

    .form-input:focus {
        background-color: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .btn-primary {
        border: none;
        background-color: var(--primary);
        color: #fff;
        height: 44px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        padding: 0 18px;
    }

    .btn-primary:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
    }

    .btn-block {
        width: 100%;
    }

    .form-section {
        padding-top: 18px;
        margin-top: 18px;
        border-top: 1px solid var(--border-color);
    }

    .note-text {
        margin-top: 12px;
        font-size: 12px;
        color: var(--neutral-grey);
    }

    /* REGISTRATION HISTORY TABLE STYLES */
    .history-table-wrapper {
        overflow-x: auto;
        margin-top: 16px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 14px;
    }

    .history-table th, .history-table td {
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }

    .history-table th {
        background-color: var(--neutral-light);
        font-weight: 700;
        color: var(--neutral-grey);
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }

    .history-table tr:last-child td {
        border-bottom: none;
    }

    .history-table tr:hover td {
        background-color: var(--primary-light);
    }

    .history-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 6px;
    }
</style>
@endpush

@section('content')
<div class="{{ ($role ?? '') === 'donor' ? 'container' : '' }}" style="{{ ($role ?? '') === 'donor' ? 'padding: 60px 0;' : '' }}">
<section class="profile-page" style="{{ ($role ?? '') === 'donor' ? 'padding: 0;' : '' }}">
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if (session('success_password'))
        <div class="alert-success">{{ session('success_password') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <form id="profile-form" method="post" action="{{ route('profile.update') }}">
        @csrf
        <input type="hidden" name="role" value="{{ $role ?? 'donor' }}">
    </form>

    <div class="profile-grid">
        <div class="card">
            <h2 class="card-title">Thông tin cá nhân</h2>
            <p class="card-subtitle">Cập nhật họ tên, email và số điện thoại.</p>

            <div class="form-group">
                <label class="form-label" for="HoTen">Họ tên</label>
                <input class="form-input" id="HoTen" name="HoTen" type="text" form="profile-form" value="{{ old('HoTen', $user->HoTen ?? '') }}" placeholder="Nhập họ tên">
            </div>

            <div class="form-group">
                <label class="form-label" for="Email">Email</label>
                <input class="form-input" id="Email" name="Email" type="email" form="profile-form" value="{{ old('Email', $user->Email ?? '') }}" placeholder="Nhập email">
            </div>

            <div class="form-group">
                <label class="form-label" for="SoDienThoai">Số điện thoại</label>
                <input class="form-input" id="SoDienThoai" name="SoDienThoai" type="text" form="profile-form" value="{{ old('SoDienThoai', $user->SoDienThoai ?? '') }}" placeholder="Nhập số điện thoại">
            </div>

            <button class="btn-primary btn-block" type="submit" form="profile-form">Lưu thông tin</button>

            <div class="form-section">
                <h2 class="card-title">Đổi mật khẩu</h2>
                <p class="card-subtitle">Cập nhật mật khẩu mới để bảo vệ tài khoản.</p>

                <form method="post" action="{{ route('profile.password') }}">
                    @csrf
                    <input type="hidden" name="role" value="{{ $role ?? 'donor' }}">

                    <div class="form-group">
                        <label class="form-label" for="current_password">Mật khẩu hiện tại</label>
                        <input class="form-input" id="current_password" name="current_password" type="password" placeholder="Nhập mật khẩu hiện tại">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="new_password">Mật khẩu mới</label>
                        <input class="form-input" id="new_password" name="new_password" type="password" placeholder="Nhập mật khẩu mới">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="new_password_confirmation">Xác nhận mật khẩu mới</label>
                        <input class="form-input" id="new_password_confirmation" name="new_password_confirmation" type="password" placeholder="Nhập lại mật khẩu mới">
                    </div>

                    <button class="btn-primary btn-block" type="submit">Đổi mật khẩu</button>
                </form>

                <p class="note-text">Mật khẩu tối thiểu 6 ký tự.</p>
            </div>
        </div>

        <div class="card">
            <h2 class="card-title">Hồ sơ sức khỏe</h2>
            <p class="card-subtitle">Chỉ số kiểm tra sức khỏe vật lý gần nhất.</p>

            @if ($healthRecord && isset($healthRecord->ThoiGianKham))
                <div class="exam-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" />
                    </svg>
                    <span>Lần khám gần nhất: {{ \Carbon\Carbon::parse($healthRecord->ThoiGianKham)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</span>
                </div>
            @endif

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="Nhommau">Nhóm máu</label>
                    <input class="form-input" id="Nhommau" name="Nhommau" type="text" form="profile-form" value="{{ old('Nhommau', $healthRecord->Nhommau ?? '') }}" placeholder="Ví dụ: O (Rh+)">
                </div>

                <div class="form-group">
                    <label class="form-label" for="HuyetAp">Huyết áp (mmHg)</label>
                    <input class="form-input" id="HuyetAp" name="HuyetAp" type="text" form="profile-form" value="{{ old('HuyetAp', $healthRecord->HuyetAp ?? '') }}" placeholder="Ví dụ: 120/80">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="NhipTim">Nhịp tim (bpm)</label>
                    <input class="form-input" id="NhipTim" name="NhipTim" type="number" form="profile-form" value="{{ old('NhipTim', $healthRecord->NhipTim ?? '') }}" placeholder="Ví dụ: 75">
                </div>

                <div class="form-group">
                    <label class="form-label" for="NhietDo">Nhiệt độ (°C)</label>
                    <input class="form-input" id="NhietDo" name="NhietDo" type="number" step="0.1" form="profile-form" value="{{ old('NhietDo', $healthRecord->NhietDo ?? '') }}" placeholder="Ví dụ: 36.5">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="CanNang">Cân nặng (kg)</label>
                    <input class="form-input" id="CanNang" name="CanNang" type="number" step="0.1" form="profile-form" value="{{ old('CanNang', $healthRecord->CanNang ?? '') }}" placeholder="Ví dụ: 68">
                </div>

                <div class="form-group">
                    <label class="form-label" for="Hemoglobin">Hemoglobin (g/dL)</label>
                    <input class="form-input" id="Hemoglobin" name="Hemoglobin" type="number" step="0.1" form="profile-form" value="{{ old('Hemoglobin', $healthRecord->Hemoglobin ?? '') }}" placeholder="Ví dụ: 14.5">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="NguoiKham">Bác sĩ / Người khám</label>
                <input class="form-input" id="NguoiKham" name="NguoiKham" type="text" form="profile-form" value="{{ old('NguoiKham', $healthRecord->NguoiKham ?? '') }}" placeholder="Họ tên bác sĩ">
            </div>

            <button class="btn-primary btn-block" type="submit" form="profile-form">Cập nhật Hồ sơ Sức khỏe</button>
        </div>
    </div>

    @if (($role ?? '') === 'donor')
        <div class="card" id="history" style="margin-top: 24px;">
            <h2 class="card-title" style="display: flex; align-items: center; gap: 8px;">
                <span>📅</span> Lịch sử đăng ký hiến máu
            </h2>
            <p class="card-subtitle">Danh sách các chương trình hiến máu bạn đã đăng ký tham gia.</p>

            @if (count($registrations) > 0)
                <div class="history-table-wrapper">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Tên chương trình</th>
                                <th>Đơn vị tổ chức</th>
                                <th>Thời gian diễn ra</th>
                                <th>Địa điểm</th>
                                <th>Thời gian đăng ký</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($registrations as $reg)
                                @php
                                    $status = 'Chờ hiến';
                                    $statusStyle = 'background-color: #fef3c7; color: #d97706;';
                                    
                                    if ($reg->HoSoId) {
                                        $status = 'Đã hiến';
                                        $statusStyle = 'background-color: #d1fae5; color: #059669;';
                                    } elseif ((int)$reg->TrangThai === 2) {
                                        $status = 'Đã duyệt';
                                        $statusStyle = 'background-color: #e0f2fe; color: #0284c7;';
                                    } elseif ((int)$reg->TrangThai === 0) {
                                        $status = 'Hủy đăng ký';
                                        $statusStyle = 'background-color: #fee2e2; color: #dc2626;';
                                    }
                                @endphp
                                <tr>
                                    <td style="font-weight: 700; color: var(--neutral-dark);">
                                        <a href="{{ route('frontend.chuong-trinh.show', $reg->ChuongTrinhId) }}" style="color: inherit; text-decoration: none;">
                                            {{ $reg->TenChuongTrinh }}
                                        </a>
                                    </td>
                                    <td>{{ $reg->TenDonVi }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reg->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</td>
                                    <td>{{ $reg->DiaChi }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reg->ThoiGianDangKy)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="history-badge" style="{{ $statusStyle }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 32px; color: var(--neutral-grey);">
                    <p style="font-size: 16px; font-weight: 600; margin-bottom: 8px; color: var(--neutral-dark);">Chưa có đăng ký nào</p>
                    <p style="font-size: 14px;">Bạn chưa đăng ký tham gia chương trình hiến máu nào gần đây.</p>
                    <a href="{{ route('frontend.chuong-trinh.index') }}" class="btn-primary" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none; margin-top: 16px; height: 38px;">
                        Khám phá chương trình
                    </a>
                </div>
            @endif
        </div>
    @endif
</section>
</div>
@endsection
