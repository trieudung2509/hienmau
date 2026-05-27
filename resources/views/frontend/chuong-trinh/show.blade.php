@extends('frontend.layouts.app')

@section('title', $program->TenChuongTrinh . ' | Chi tiết Chương trình')

@push('styles')
  <style>
    /* DETAILS LAYOUT CONTAINER */
    .details-wrapper {
      padding: 60px 0;
      background: #fafafa;
    }

    .details-grid {
      display: grid;
      grid-template-columns: 7fr 3fr;
      gap: 40px;
    }

    @media(max-width: 992px) {
      .details-grid {
        grid-template-columns: 1fr;
      }
    }

    /* LEFT COLUMN CARDS */
    .detail-card {
      background: white;
      border-radius: 24px;
      padding: 32px;
      border: 1px solid #f0f0f0;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
      margin-bottom: 30px;
    }

    .detail-banner {
      width: 100%;
      height: 380px;
      border-radius: 20px;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      margin-bottom: 30px;
      box-shadow: var(--shadow-sm);
    }

    @media(max-width: 768px) {
      .detail-banner {
        height: 240px;
      }
    }

    .detail-title {
      font-size: 32px;
      font-family: var(--font-heading);
      color: #111;
      font-weight: 800;
      margin-bottom: 20px;
      line-height: 1.3;
    }

    .detail-section-title {
      font-size: 18px;
      font-weight: 700;
      color: #222;
      border-bottom: 2px solid #f5f5f5;
      padding-bottom: 10px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .detail-section-title svg {
      width: 20px;
      height: 20px;
      color: #e53935;
    }

    .description-text {
      font-size: 15px;
      color: #444;
      line-height: 1.8;
      white-space: pre-line;
    }

    /* STATS & INFO BLOCKS */
    .info-list {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .info-item {
      display: flex;
      align-items: flex-start;
      gap: 16px;
    }

    .info-icon-wrapper {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: #fdf2f2;
      color: #e53935;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .info-icon-wrapper svg {
      width: 20px;
      height: 20px;
    }

    .info-content {
      display: flex;
      flex-direction: column;
      gap: 4px;
    }

    .info-label {
      font-size: 12px;
      font-weight: 700;
      color: #888;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .info-value {
      font-size: 15px;
      font-weight: 600;
      color: #222;
    }

    /* RIGHT SIDEBAR ACTION STICKY CARD */
    .sidebar-card {
      background: white;
      border-radius: 24px;
      padding: 32px;
      border: 1px solid #f0f0f0;
      box-shadow: 0 6px 25px rgba(0, 0, 0, 0.04);
      position: sticky;
      top: 100px;
    }

    .progress-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
      font-weight: 700;
      color: #444;
      margin-bottom: 8px;
    }

    .progress-bar-bg {
      width: 100%;
      height: 10px;
      background: #eee;
      border-radius: 5px;
      overflow: hidden;
      margin-bottom: 24px;
    }

    .progress-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, #ff5252, #e53935);
      border-radius: 5px;
    }

    .sidebar-badge {
      display: inline-block;
      padding: 6px 12px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 11px;
      text-transform: uppercase;
      margin-bottom: 20px;
    }

    .sidebar-badge.ongoing { background: #e8f5e9; color: #2e7d32; }
    .sidebar-badge.upcoming { background: #e3f2fd; color: #1565c0; }
    .sidebar-badge.ended { background: #eceff1; color: #37474f; }

    .btn-register-cta {
      display: block;
      width: 100%;
      text-align: center;
      padding: 16px 0;
      background: linear-gradient(135deg, #d81f26 0%, #e53935 100%);
      color: white;
      border-radius: 14px;
      font-weight: 700;
      font-size: 15px;
      box-shadow: 0 4px 15px rgba(229, 57, 53, 0.25);
      transition: all 0.3s;
    }

    .btn-register-cta:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(229, 57, 53, 0.35);
    }

    .btn-register-ended {
      display: block;
      width: 100%;
      text-align: center;
      padding: 16px 0;
      background: #eceff1;
      color: #90a4ae;
      border-radius: 14px;
      font-weight: 700;
      font-size: 15px;
      cursor: not-allowed;
    }

    .register-hint {
      margin-top: 14px;
      font-size: 12px;
      color: #888;
      text-align: center;
      line-height: 1.4;
    }
  </style>
@endpush

@section('content')
  <div class="details-wrapper">
    <div class="container">
      <div class="details-grid">
        <!-- LEFT COLUMN (Details & Description) -->
        <div>
          <!-- Hero Banner Image -->
          <div class="detail-banner" style="background-image: url('{{ asset($program->Banner ?? '') }}'), linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);"></div>

          <!-- Main Title Card -->
          <div class="detail-card">
            <h1 class="detail-title">{{ $program->TenChuongTrinh }}</h1>
            
            <div class="info-list" style="margin-top: 24px;">
              <!-- Location item -->
              <div class="info-item">
                <div class="info-icon-wrapper">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                  </svg>
                </div>
                <div class="info-content">
                  <span class="info-label">Địa điểm tổ chức</span>
                  <span class="info-value">
                    {{ $program->DiaChi }}
                    @if(!empty($program->BanDo))
                      <a href="{{ $program->BanDo }}" target="_blank" style="margin-left: 8px; color: #e53935; font-size: 13px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; text-decoration: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 14px; height: 14px; flex-shrink: 0;">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        Xem bản đồ
                      </a>
                    @endif
                  </span>
                </div>
              </div>

              <!-- Time item -->
              <div class="info-item">
                <div class="info-icon-wrapper">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div class="info-content">
                  <span class="info-label">Thời gian diễn ra</span>
                  <span class="info-value">
                    Từ {{ \Carbon\Carbon::parse($program->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }} đến {{ \Carbon\Carbon::parse($program->ThoiGianKetThuc)->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Description Card -->
          <div class="detail-card">
            <h2 class="detail-section-title">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7" />
              </svg>
              Thông tin chi tiết chương trình
            </h2>
            <div class="description-text">
              {!! nl2br(e($program->MoTa)) !!}
            </div>
          </div>

          <!-- Organizer Contact Card -->
          <div class="detail-card">
            <h2 class="detail-section-title">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              Đơn vị tổ chức
            </h2>
            
            <div style="margin-bottom: 20px;">
              <h3 style="font-size: 18px; color: #222; font-weight: 700; margin-bottom: 8px;">{{ $program->TenDonVi }}</h3>
              <p style="font-size: 14.5px; color: #666; line-height: 1.6;">{{ $program->DvMoTa ?? 'Là đơn vị tích cực liên kết với Cổng thông tin Hiến Máu Tình Nguyện nhằm thúc đẩy hoạt động sẻ chia sự sống trong cộng đồng.' }}</p>
            </div>

            <div class="info-list">
              <div class="info-item">
                <div class="info-icon-wrapper" style="background:#e8f5e9; color:#2e7d32;">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </div>
                <div class="info-content">
                  <span class="info-label" style="color:#2e7d32;">Người đại diện</span>
                  <span class="info-value">{{ $program->NguoiDaiDien }}</span>
                </div>
              </div>

              <div class="info-item">
                <div class="info-icon-wrapper" style="background:#e3f2fd; color:#1565c0;">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                  </svg>
                </div>
                <div class="info-content">
                  <span class="info-label" style="color:#1565c0;">Điện thoại liên hệ</span>
                  <span class="info-value">{{ $program->DvSoDienThoai ?? $program->DvSDT }}</span>
                </div>
              </div>

              <div class="info-item">
                <div class="info-icon-wrapper" style="background:#fff3e0; color:#ef6c00;">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                  </svg>
                </div>
                <div class="info-content">
                  <span class="info-label" style="color:#ef6c00;">Hòm thư (Email)</span>
                  <span class="info-value">{{ $program->DvEmail }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT SIDEBAR (Dynamic action sticky box) -->
        <div>
          <div class="sidebar-card">
            <!-- Timeline Status Badge -->
            @if($program->TinhTrangTimeline === 'ongoing')
              <span class="sidebar-badge ongoing">Đang diễn ra</span>
            @elseif($program->TinhTrangTimeline === 'upcoming')
              <span class="sidebar-badge upcoming">Sắp diễn ra</span>
            @else
              <span class="sidebar-badge ended">Đã kết thúc</span>
            @endif

            <!-- Dynamic Registration Progress Tracker -->
            <div class="progress-container" style="border-top: none; padding-top: 0;">
              <div class="progress-header">
                <span>Lượt đăng ký:</span>
                <span>{{ $program->SoNguoiDangKy }}/{{ $program->SoLuongDuKien }}</span>
              </div>
              <div class="progress-bar-bg">
                <div class="progress-bar-fill" style="width: {{ min(100, $program->PhanTram) }}%;"></div>
              </div>
            </div>

            <!-- Pre-defined Timelines -->
            <div class="info-list" style="margin-bottom: 30px; gap: 12px; font-size: 13px;">
              <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #f5f5f5; padding-bottom: 8px;">
                <span style="color:#888; font-weight:600;">Mở đăng ký:</span>
                <span style="font-weight:700; color:#222;">{{ \Carbon\Carbon::parse($program->ThoiGianMoDangKy)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}</span>
              </div>
              <div style="display: flex; justify-content: space-between;">
                <span style="color:#888; font-weight:600;">Thời hạn đăng ký:</span>
                <span style="font-weight:700; color:#e53935;">Trước giờ bắt đầu</span>
              </div>
            </div>

            <!-- CTA Call to Action registration button -->
            @if($program->TinhTrangTimeline === 'ended')
              <span class="btn-register-ended">Chương trình đã kết thúc</span>
            @else
              <a href="{{ route('frontend.chuong-trinh.register', ['program_id' => $program->Id]) }}" class="btn-register-cta">Đăng ký tham gia</a>
              <p class="register-hint">Chỉ mất 2 phút đăng ký lịch hẹn hiến máu nhân đạo.</p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
