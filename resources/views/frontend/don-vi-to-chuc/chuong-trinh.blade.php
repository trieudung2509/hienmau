@extends('frontend.layouts.don-vi-to-chuc')

@section('title', 'BloodCare - Đề Xuất Chương Trình')

@section('content')
  <div class="container">
    
    <div class="content-area">
      
      <div class="topbar">
        <div class="topbar-title">
          <i class="fa-solid fa-bars"></i>
          <span>Đề xuất chương trình</span>
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

      <div class="page-header">
        <div class="page-title">
          <h2>Quản lý đề xuất chương trình</h2>
          <p>Tạo và theo dõi danh sách các đề xuất chương trình hiến máu của đơn vị</p>
        </div>
        <button class="btn-create-proposal" id="openModalBtn"><i class="fa-solid fa-plus"></i> Tạo đề xuất mới</button>
      </div>

      @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="alert-error">
          <ul style="list-style: none;">
            @foreach($errors->all() as $error)
              <li><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="filter-box">
        <h3>Tìm kiếm đề xuất</h3>
        <form method="get" action="{{ route('don-vi-to-chuc.chuong-trinh') }}">
          <div class="filter-row">
            <div class="input-group">
              <i class="fa-solid fa-magnifying-glass"></i>
              <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Nhập tên chương trình, địa điểm...">
            </div>
            <div class="input-group">
              <i class="fa-solid fa-toggle-on"></i>
              <select name="trang_thai">
                <option value="">Tất cả trạng thái</option>
                <option value="Đang diễn ra" {{ request('trang_thai') === 'Đang diễn ra' ? 'selected' : '' }}>Đang diễn ra</option>
                <option value="Đã duyệt" {{ request('trang_thai') === 'Đã duyệt' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="Chờ duyệt" {{ request('trang_thai') === 'Chờ duyệt' ? 'selected' : '' }}>Chờ duyệt</option>
              </select>
            </div>
            <button type="submit" class="search-btn">Tìm kiếm</button>
          </div>
        </form>
      </div>

      <div class="program-section">
        <div class="section-header">
          <h3>Đề xuất của bạn</h3>
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
          <div style="text-align: center; color: #888; padding: 20px;">Không có đề xuất nào phù hợp.</div>
          @endforelse
        </div>
      </div>

    </div>

  </div>

  <!-- ================= PROPOSAL CREATION MODAL ================= -->
  <div class="modal" id="proposalModal">
    <div class="modal-content">
      <i class="fa-solid fa-xmark close-btn" id="closeModalBtn"></i>
      <h3 class="modal-title">Tạo Đề Xuất Chương Trình Mới</h3>
      
      <form method="post" action="{{ route('don-vi-to-chuc.chuong-trinh.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-grid">
          <div class="form-group form-group-full">
            <label class="form-label" for="TenChuongTrinh">Tên chương trình *</label>
            <input class="form-input" id="TenChuongTrinh" name="TenChuongTrinh" type="text" required placeholder="Nhập tên chương trình hiến máu">
          </div>

          <div class="form-group form-group-full">
            <label class="form-label" for="MoTa">Mô tả chương trình *</label>
            <textarea class="form-input" id="MoTa" name="MoTa" required placeholder="Nhập mô tả, mục đích chương trình..."></textarea>
          </div>

          <div class="form-group form-group-full">
            <label class="form-label" for="DiaChi">Địa điểm tổ chức *</label>
            <input class="form-input" id="DiaChi" name="DiaChi" type="text" required placeholder="Địa chỉ chi tiết nơi tổ chức">
          </div>

          <div class="form-group form-group-full">
            <label class="form-label" for="BanDo">Link bản đồ (Google Maps URL)</label>
            <input class="form-input" id="BanDo" name="BanDo" type="url" placeholder="Nhập Google Maps URL (Tùy chọn)">
          </div>

          <div class="form-group">
            <label class="form-label" for="ThoiGianBatDau">Thời gian bắt đầu *</label>
            <input class="form-input" id="ThoiGianBatDau" name="ThoiGianBatDau" type="datetime-local" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="ThoiGianKetThuc">Thời gian kết thúc *</label>
            <input class="form-input" id="ThoiGianKetThuc" name="ThoiGianKetThuc" type="datetime-local" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="ThoiGianMoDangKy">Mở đăng ký từ ngày *</label>
            <input class="form-input" id="ThoiGianMoDangKy" name="ThoiGianMoDangKy" type="datetime-local" required>
          </div>

          <div class="form-group">
            <label class="form-label" for="SoLuongDuKien">Số người đăng ký dự kiến *</label>
            <input class="form-input" id="SoLuongDuKien" name="SoLuongDuKien" type="number" min="1" required placeholder="VD: 150">
          </div>

          <div class="form-group form-group-full">
            <label class="form-label" for="BannerFile">Ảnh banner đại diện (Tùy chọn)</label>
            <input class="form-input" id="BannerFile" name="BannerFile" type="file" accept="image/*" style="padding: 5px;">
          </div>
        </div>

        <div class="submit-row">
          <button type="button" class="btn-cancel" id="cancelModalBtn">Hủy bỏ</button>
          <button type="submit" class="btn-submit">Gửi đề xuất</button>
        </div>
      </form>
    </div>
  </div>
@endsection

@section('scripts')
  <script>
    const modal = document.getElementById('proposalModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelModalBtn');

    openBtn.addEventListener('click', () => {
      modal.style.display = 'block';
    });

    const closeModal = () => {
      modal.style.display = 'none';
    };

    closeBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);

    window.addEventListener('click', (e) => {
      if (e.target === modal) {
        closeModal();
      }
    });
  </script>
@endsection
