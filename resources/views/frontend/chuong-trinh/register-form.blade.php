@extends('frontend.layouts.app')

@section('title', 'Đăng ký tham gia hiến máu')

@push('styles')
<style>
    .register-page {
        padding: 60px 0;
        background-color: #f8fafc;
        min-height: 90vh;
        display: flex;
        align-items: center;
    }

    .register-container {
        max-width: 800px;
        margin: 0 auto;
        width: 100%;
    }

    .register-card {
        background-color: #ffffff;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        padding: 40px;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    }

    .register-header {
        text-align: center;
        margin-bottom: 36px;
    }

    .register-header h1 {
        font-family: var(--font-heading, 'Inter', sans-serif);
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .register-header h1 span {
        color: #e53935;
    }

    .register-header p {
        color: #64748b;
        font-size: 15px;
        font-weight: 500;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 20px;
    }

    .form-label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input {
        height: 46px;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        outline: none;
        transition: all 0.2s ease;
        font-family: var(--font-main, sans-serif);
    }

    .form-input:focus {
        border-color: #e53935;
        box-shadow: 0 0 0 3px rgba(229, 57, 53, 0.15);
    }

    .form-input:disabled {
        background-color: #f1f5f9;
        color: #64748b;
        border-color: #e2e8f0;
        cursor: not-allowed;
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

    .btn-submit {
        background-color: #e53935;
        color: #ffffff;
        height: 48px;
        width: 100%;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(229, 57, 53, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 10px;
    }

    .btn-submit:hover {
        background-color: #b71c1c;
        transform: translateY(-1px);
    }

    .info-badge {
        background-color: #fef2f2;
        border: 1px solid #fee2e2;
        color: #b91c1c;
        border-radius: 12px;
        padding: 16px;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.5;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .alert-error {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 14px 16px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 24px;
    }

    .section-divider {
        margin: 28px 0;
        border-top: 1px dashed #e2e8f0;
    }

    .section-title {
        font-family: var(--font-heading, 'Inter', sans-serif);
        font-size: 16px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>
@endpush

@section('content')
<div class="register-page">
    <div class="container register-container">
        
        <div class="register-card">
            
            <header class="register-header">
                <h1>🩸 Đăng ký <span>Hiến máu nhân đạo</span></h1>
                <p>Đặt lịch hẹn hiến máu hôm nay – Sẻ chia sự sống cho người bệnh đang cần bạn.</p>
            </header>

            @if($errors->any())
                <div class="alert-error">
                    <span>⚠️</span> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('frontend.chuong-trinh.register.submit') }}" method="POST">
                @csrf

                <!-- Campaign Selection (Required for both logged-in and guest) -->
                <div class="form-group">
                    <label class="form-label" for="ChuongTrinhId">Chương trình hiến máu</label>
                    <select class="form-input" id="ChuongTrinhId" name="ChuongTrinhId" style="appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%2364748b\' stroke-width=\'2.5\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19.5 8.25l-7.5 7.5-7.5-7.5\'/></svg>'); background-repeat: no-repeat; background-position: right 16px center; background-size: 14px; padding-right: 40px;">
                        <option value="">-- Chọn chương trình hiến máu hoạt động --</option>
                        @foreach($programs as $prog)
                            @php
                                $isSelected = (old('ChuongTrinhId', $preselectedId) == $prog->Id);
                                $dateStr = \Carbon\Carbon::parse($prog->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y');
                            @endphp
                            <option value="{{ $prog->Id }}" {{ $isSelected ? 'selected' : '' }}>
                                {{ $prog->TenChuongTrinh }} ({{ $prog->DiaChi }} — {{ $dateStr }})
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(session()->has('admin_user'))
                    <!-- LOGGED IN VIEW: Information is pre-filled and read-only -->
                    <div class="info-badge">
                        <span>ℹ️</span> 
                        <div>
                            Bạn đã đăng nhập với tài khoản <strong>{{ $user->HoTen }}</strong>. 
                            Hệ thống đã tự động liên kết thông tin cá nhân và hồ sơ sức khỏe của bạn. Bạn không cần điền thêm thông tin nào khác!
                        </div>
                    </div>

                    <div class="section-title">
                        <span>👤</span> Thông tin cá nhân liên kết
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Họ tên</label>
                            <input class="form-input" type="text" value="{{ $user->HoTen }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input class="form-input" type="text" value="{{ $user->SoDienThoai }}" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input class="form-input" type="email" value="{{ $user->Email }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Số CCCD</label>
                            <input class="form-input" type="text" value="{{ $donor->CCCD ?? 'Chưa cập nhật' }}" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Ngày sinh</label>
                            <input class="form-input" type="text" value="{{ $donor ? \Carbon\Carbon::parse($donor->NgaySinh)->format('d/m/Y') : 'Chưa cập nhật' }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Giới tính</label>
                            @php
                                $gender = 'Chưa cập nhật';
                                if ($donor) {
                                    if ((int)$donor->GioiTinh === 1) $gender = 'Nam';
                                    elseif ((int)$donor->GioiTinh === 2) $gender = 'Nữ';
                                    elseif ((int)$donor->GioiTinh === 3) $gender = 'Khác';
                                }
                            @endphp
                            <input class="form-input" type="text" value="{{ $gender }}" disabled>
                        </div>
                    </div>

                @else
                    <!-- GUEST VIEW: Sign up fields required -->
                    <div class="info-badge" style="background-color: #eff6ff; border-color: #dbeafe; color: #1e40af;">
                        <span>👤</span>
                        <div>
                            Bạn chưa đăng nhập. Điền thông tin dưới đây để đăng ký tài khoản hiến máu và đăng ký tham gia chương trình trong 1 bước!
                        </div>
                    </div>

                    <div class="section-title">
                        <span>✏️</span> Nhập thông tin đăng ký tài khoản và hiến máu
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="HoTen">Họ và tên</label>
                            <input class="form-input" id="HoTen" name="HoTen" type="text" value="{{ old('HoTen') }}" placeholder="Nhập họ và tên của bạn">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="SoDienThoai">Số điện thoại</label>
                            <input class="form-input" id="SoDienThoai" name="SoDienThoai" type="text" value="{{ old('SoDienThoai') }}" placeholder="Nhập số điện thoại di động">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="Email">Email</label>
                            <input class="form-input" id="Email" name="Email" type="email" value="{{ old('Email') }}" placeholder="Nhập địa chỉ email của bạn">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="CCCD">Số CCCD/CMND (12 chữ số)</label>
                            <input class="form-input" id="CCCD" name="CCCD" type="text" value="{{ old('CCCD') }}" placeholder="Nhập số căn cước công dân">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="NgaySinh">Ngày sinh</label>
                            <input class="form-input" id="NgaySinh" name="NgaySinh" type="date" value="{{ old('NgaySinh') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="GioiTinh">Giới tính</label>
                            <select class="form-input" id="GioiTinh" name="GioiTinh" style="appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'%2364748b\' stroke-width=\'2.5\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M19.5 8.25l-7.5 7.5-7.5-7.5\'/></svg>'); background-repeat: no-repeat; background-position: right 16px center; background-size: 14px; padding-right: 40px;">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam" {{ old('GioiTinh') === 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ old('GioiTinh') === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác" {{ old('GioiTinh') === 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" for="MatKhau">Mật khẩu tài khoản (tối thiểu 6 ký tự)</label>
                            <input class="form-input" id="MatKhau" name="MatKhau" type="password" placeholder="Nhập mật khẩu để đăng nhập sau này">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="MatKhau_confirmation">Xác nhận mật khẩu</label>
                            <input class="form-input" id="MatKhau_confirmation" name="MatKhau_confirmation" type="password" placeholder="Nhập lại mật khẩu">
                        </div>
                    </div>
                @endif

                <div class="section-divider"></div>

                <div class="form-group">
                    <label class="form-label" for="GhiChu">Ghi chú hoặc lời nhắn (nếu có)</label>
                    <textarea class="form-input" id="GhiChu" name="GhiChu" style="height: 100px; resize: vertical; padding: 12px 16px;" placeholder="Ví dụ: Đăng ký hiến máu đợt sáng lúc 9:00...">{{ old('GhiChu') }}</textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <span>❤️</span> Xác nhận Đăng ký hiến máu
                </button>

            </form>

        </div>

    </div>
</div>
@endsection
