@extends('frontend.layouts.don-vi-to-chuc')

@section('title', 'BloodCare - Đơn Vị Tổ Chức')

@section('content')
  <div class="container">
    
    <div class="content-area">
      
      <div class="topbar">
        <div class="topbar-title">
          <i class="fa-solid fa-bars"></i>
          <span>Trang chủ</span>
        </div>
        <div class="topbar-right">
          <div class="noti-btn">
            <i class="fa-regular fa-bell"></i>
            <div class="noti-badge">3</div>
          </div>
          <div class="user-profile-header">
            <div class="user-info-header">
              <h4>{{ $donVi->TenDonVi }}</h4>
              <p>Đơn vị tổ chức</p>
            </div>
            <div class="avatar-wrap-header">
              <i class="fa-solid fa-building"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="welcome-banner">
        <div class="welcome-text">
          <h2>Chào mừng, {{ $donVi->TenDonVi }}</h2>
          <p>Cùng chung tay kết nối và lan tỏa nghĩa cử hiến máu cứu người</p>
        </div>
        <i class="fa-solid fa-heart-circle-check blood-drop-art"></i>
      </div>

      <div class="stat-cards">
        <div class="stat-card">
          <div class="stat-icon" style="background: #fff0f0; color: #e53935;"><i class="fa-solid fa-file-medical"></i></div>
          <div class="stat-info">
            <h3>{{ $stats['cho_duyet'] }}</h3>
            <p>Đề xuất đang chờ duyệt</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #fff0f0; color: #e53935;"><i class="fa-solid fa-calendar-days"></i></div>
          <div class="stat-info">
            <h3>{{ $stats['sap_dien_ra'] }}</h3>
            <p>Chương trình sắp diễn ra</p>
          </div>
        </div>
        <div class="stat-card">
          <div class="stat-icon" style="background: #fff0f0; color: #e53935;"><i class="fa-solid fa-users"></i></div>
          <div class="stat-info">
            <h3>{{ $stats['dang_ky'] }}</h3>
            <p>Người tham gia đã đăng ký</p>
          </div>
        </div>
      </div>

      <div class="program-section">
        <div class="section-header">
          <h3>Chương trình của đơn vị</h3>
          <a href="{{ route('don-vi-to-chuc.chuong-trinh') }}">Xem tất cả</a>
        </div>

        <div class="program-list">
          @forelse($programs as $prog)
          <div class="program-card">
            <div class="program-main">
              <img src="{{ $prog->Banner }}" alt="{{ $prog->TenChuongTrinh }}" class="program-img">
              <div class="program-details">
                <h4>{{ $prog->TenChuongTrinh }}</h4>
                <div class="program-meta">
                  <span>
                    <i class="fa-solid fa-location-dot"></i> {{ $prog->DiaChi }}
                    @if(!empty($prog->BanDo))
                      &nbsp;•&nbsp; <a href="{{ $prog->BanDo }}" target="_blank" style="color: #e53935; font-weight: 600;"><i class="fa-solid fa-map-location-dot"></i> Bản đồ</a>
                    @endif
                  </span>
                  <span><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($prog->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }} &nbsp;•&nbsp; <i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($prog->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('H:i') }} - {{ \Carbon\Carbon::parse($prog->ThoiGianKetThuc)->timezone('Asia/Ho_Chi_Minh')->format('H:i') }}</span>
                </div>
                <div class="badge-row">
                  @if($prog->TrangThai == 3)
                    <span class="badge badge-running">Đang diễn ra</span>
                  @elseif($prog->TrangThai == 1)
                    <span class="badge badge-pending">Chờ duyệt</span>
                  @elseif($prog->TrangThai == 2)
                    <span class="badge badge-approved">Đã duyệt</span>
                  @else
                    <span class="badge badge-approved">Đã duyệt</span>
                  @endif
                </div>
              </div>
            </div>
            <div class="program-stats">
              @if(isset($prog->RegisteredCount) && $prog->RegisteredCount !== null)
                <div class="stat-item-inner"><i class="fa-regular fa-user"></i> {{ $prog->RegisteredCount }} người đăng ký</div>
              @endif
            </div>
            <div class="arrow-btn"><i class="fa-solid fa-chevron-right"></i></div>
          </div>
          @empty
          <div style="text-align: center; color: #888; padding: 20px;">Không tìm thấy chương trình nào phù hợp.</div>
          @endforelse
        </div>
      </div>

    </div>

    <div class="content-right">
      
      <div class="panel-card">
        <h3>Thông tin đơn vị</h3>
        <div class="unit-box">
          <div class="unit-icon-box"><i class="fa-solid fa-building"></i></div>
          <h4>{{ $donVi->TenDonVi }}</h4>
          <span class="unit-tag">Đơn vị tổ chức</span>
        </div>
        <div class="unit-contact-list">
          <li><i class="fa-solid fa-phone"></i> {{ $donVi->SoDienThoai }}</li>
          <li><i class="fa-solid fa-envelope"></i> {{ $donVi->Email }}</li>
          <li><i class="fa-solid fa-location-dot"></i> {{ $donVi->DiaChi }}</li>
        </div>
      </div>

      <div class="panel-card">
        <h3>Lịch hiến máu gần nhất</h3>
        <div class="recent-date-box">
          <i class="fa-regular fa-calendar-check"></i>
          <h4>{{ \Carbon\Carbon::parse($nextProgram->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}</h4>
          <p><i class="fa-regular fa-clock" style="font-size:11px; color:#888;"></i> {{ \Carbon\Carbon::parse($nextProgram->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('H:i') }} - {{ \Carbon\Carbon::parse($nextProgram->ThoiGianKetThuc)->timezone('Asia/Ho_Chi_Minh')->format('H:i') }}</p>
          <span>{{ $nextProgram->DiaChi }}</span>
        </div>
        <button class="view-detail-btn">Xem chi tiết</button>
      </div>

    </div>

  </div>
@endsection
