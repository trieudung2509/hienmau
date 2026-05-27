@extends('frontend.layouts.app')

@section('title', 'Danh sách Chương trình Hiến máu | Hiến Máu Bệnh viện E')

@push('styles')
  <style>
    /* HERO BANNER */
    .programs-hero {
      background: linear-gradient(135deg, #d81f26 0%, #ef5350 100%);
      color: white;
      padding: 60px 0;
      text-align: center;
    }

    .programs-hero h1 {
      font-size: 38px;
      font-family: var(--font-heading);
      margin-bottom: 12px;
      font-weight: 800;
    }

    .programs-hero p {
      font-size: 16px;
      opacity: 0.95;
      max-width: 600px;
      margin: 0 auto;
    }

    /* SEARCH & FILTER SECTION */
    .filter-section {
      background: #fdfdfd;
      border-bottom: 1px solid #eee;
      padding: 24px 0;
    }

    .filter-container {
      display: grid;
      grid-template-columns: 2fr 1fr auto;
      gap: 16px;
      align-items: center;
    }

    @media(max-width: 768px) {
      .filter-container {
        grid-template-columns: 1fr;
      }
    }

    .input-group {
      position: relative;
    }

    .filter-input {
      width: 100%;
      height: 48px;
      padding: 8px 16px;
      border: 1px solid #ddd;
      border-radius: 12px;
      font-size: 14px;
      outline: none;
      transition: all 0.3s;
      background: white;
    }

    .filter-input:focus {
      border-color: #d81f26;
      box-shadow: 0 0 0 4px rgba(216, 31, 38, 0.1);
    }

    .btn-search {
      height: 48px;
      background: #d81f26;
      color: white;
      border: none;
      border-radius: 12px;
      padding: 0 24px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.3s;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    .btn-search:hover {
      background: #b71c1c;
    }

    /* PROGRAMS GRID */
    .programs-section {
      padding: 60px 0;
      background: #fafafa;
      min-height: 400px;
    }

    .programs-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
    }

    @media(max-width: 992px) {
      .programs-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media(max-width: 576px) {
      .programs-grid {
        grid-template-columns: 1fr;
      }
    }

    /* PROGRAM CARD */
    .program-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.04);
      border: 1px solid #f0f0f0;
      transition: all 0.3s ease;
      display: flex;
      flex-direction: column;
    }

    .program-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.08);
    }

    .card-banner {
      height: 180px;
      position: relative;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }

    .status-badge {
      position: absolute;
      top: 16px;
      left: 16px;
      padding: 6px 12px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 12px;
      color: white;
      text-transform: uppercase;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .status-badge.ongoing {
      background: #2e7d32; /* Green */
    }

    .status-badge.upcoming {
      background: #1565c0; /* Blue */
    }

    .status-badge.ended {
      background: #37474f; /* Slate grey */
    }

    .card-body {
      padding: 24px;
      display: flex;
      flex-direction: column;
      flex-grow: 1;
    }

    .card-title {
      font-size: 18px;
      font-weight: 700;
      color: #222;
      margin-bottom: 12px;
      line-height: 1.4;
      min-height: 50px;
    }

    .card-meta {
      display: flex;
      flex-direction: column;
      gap: 8px;
      margin-bottom: 20px;
      font-size: 13.5px;
      color: #666;
    }

    .meta-item {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .meta-item svg {
      width: 16px;
      height: 16px;
      color: #e53935;
      flex-shrink: 0;
    }

    /* PROGRESS TRACKER */
    .progress-container {
      margin-top: auto;
      border-top: 1px dashed #eee;
      padding-top: 16px;
    }

    .progress-info {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 12.5px;
      font-weight: 600;
      color: #555;
      margin-bottom: 6px;
    }

    .progress-bar-bg {
      width: 100%;
      height: 8px;
      background: #eee;
      border-radius: 4px;
      overflow: hidden;
      margin-bottom: 16px;
    }

    .progress-bar-fill {
      height: 100%;
      background: linear-gradient(90deg, #ff5252, #e53935);
      border-radius: 4px;
    }

    .btn-card-action {
      display: block;
      width: 100%;
      text-align: center;
      padding: 12px 0;
      background: #fdf2f2;
      color: #d81f26;
      border-radius: 12px;
      font-weight: 700;
      font-size: 14px;
      transition: all 0.3s;
    }

    .btn-card-action:hover {
      background: #d81f26;
      color: white;
    }
  </style>
@endpush

@section('content')
  <!-- HERO BANNER -->
  <section class="programs-hero">
    <div class="container">
      <h1>Chương trình Hiến máu</h1>
      <p>Lựa chọn chương trình hiến máu phù hợp và thuận tiện nhất với bạn để bắt đầu hành trình hiến máu nhân đạo.</p>
    </div>
  </section>

  <!-- SEARCH FILTERS -->
  <section class="filter-section">
    <div class="container">
      <form action="{{ route('frontend.chuong-trinh.index') }}" method="GET" class="filter-container">
        <!-- Keyword input -->
        <div class="input-group">
          <input type="text" name="keyword" class="filter-input" placeholder="Tìm theo tên chương trình, địa điểm tổ chức..." value="{{ request('keyword') }}">
        </div>

        <!-- Location Dropdown -->
        <div class="input-group">
          <select name="dia_diem" class="filter-input filter-select" style="padding-right: 40px; cursor: pointer;">
            <option value="tat-ca">Tất cả địa điểm</option>
            @foreach($cities as $city)
              <option value="{{ $city }}" {{ request('dia_diem') == $city ? 'selected' : '' }}>{{ $city }}</option>
            @endforeach
          </select>
        </div>

        <!-- Search Submit Button -->
        <button type="submit" class="btn-search">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
          </svg>
          Tìm kiếm
        </button>
      </form>
    </div>
  </section>

  <!-- PROGRAMS CARD GRID -->
  <section class="programs-section">
    <div class="container">
      <div class="programs-grid">
        @forelse($programs as $p)
          <div class="program-card">
            <!-- Banner & Status Badge -->
            <div class="card-banner" style="background-image: url('{{ asset($p->Banner ?? '') }}'), linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);">
              @if($p->TinhTrangTimeline === 'ongoing')
                <span class="status-badge ongoing">Đang diễn ra</span>
              @elseif($p->TinhTrangTimeline === 'upcoming')
                <span class="status-badge upcoming">Sắp diễn ra</span>
              @else
                <span class="status-badge ended">Đã kết thúc</span>
              @endif
            </div>

            <!-- Card Body -->
            <div class="card-body">
              <h3 class="card-title">{{ $p->TenChuongTrinh }}</h3>
              
              <div class="card-meta">
                <!-- Location item -->
                <div class="meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                  </svg>
                  <span>{{ $p->DiaChi }}</span>
                </div>

                <!-- Date range item -->
                <div class="meta-item">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                  </svg>
                  <span>{{ \Carbon\Carbon::parse($p->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('H:i d/m/Y') }}</span>
                </div>
              </div>

              <!-- Progress & Action button -->
              <div class="progress-container">
                <div class="progress-info">
                  <span>Tiến độ đạt được:</span>
                  <span>{{ $p->SoNguoiDangKy }}/{{ $p->SoLuongDuKien }} lượt</span>
                </div>
                <div class="progress-bar-bg">
                  <div class="progress-bar-fill" style="width: {{ min(100, $p->PhanTram) }}%;"></div>
                </div>

                <a href="{{ route('frontend.chuong-trinh.show', $p->Id) }}" class="btn-card-action">Xem chi tiết</a>
              </div>
            </div>
          </div>
        @empty
          <div style="grid-column: span 3; text-align: center; padding: 80px 0; color: #777;">
            <div style="font-size: 60px; margin-bottom: 20px;">📅</div>
            <h3>Không tìm thấy chương trình nào</h3>
            <p style="margin-top: 8px;">Vui lòng thử lại với từ khóa tìm kiếm hoặc bộ lọc khác.</p>
          </div>
        @endforelse
      </div>
    </div>
  </section>
@endsection
