@extends('admin.layouts.dashboard', [
    'role' => 'admin',
    'sidebarClass' => 'sidebar-dark',
    'primaryColor' => '#2563eb',
    'primaryHoverColor' => '#1d4ed8',
    'primaryLightColor' => '#eff6ff',
    'sidebarBg' => '#111c43',
    'sidebarActive' => '#2563eb',
    'bodyBg' => '#f3f6ff',
    'userName' => 'System Admin',
    'userRole' => 'Quản trị viên',
])

@section('title', 'Quản lý chương trình')
@section('navbar-title', 'Quản lý chương trình')
@section('navbar-subtitle', 'Quản lý, tạo mới, chỉnh sửa và theo dõi các chương trình hiến máu.')


@section('content')
<div class="content-container">
    
    <!-- ALERT NOTIFICATION FOR ACTIONS -->
    @if(session('success'))
        <div class="alert-success">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="alert-success" style="background-color: var(--danger-light); border-color: rgba(239, 68, 68, 0.2); color: #991b1b;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    
    <!-- TABS & ACTIONS CONTAINER -->
    <div class="tabs-header-row">
        <nav>
            <ul class="tabs-list">
                <li>
                    <a href="?tab=tat-ca" class="tab-btn {{ $activeTab === 'tat-ca' ? 'active' : '' }}">
                        Tất cả chương trình
                        <span class="tab-badge">{{ $counts['tat_ca'] }}</span>
                    </a>
                </li>
                <li>
                    <a href="?tab=cho-duyet" class="tab-btn {{ $activeTab === 'cho-duyet' ? 'active' : '' }}">
                        Chờ duyệt
                        <span class="tab-badge" style="background-color: var(--warning-light); color: var(--warning);">{{ $counts['cho_duyet'] }}</span>
                    </a>
                </li>
                <li>
                    <a href="?tab=da-duyet" class="tab-btn {{ $activeTab === 'da-duyet' ? 'active' : '' }}">
                        Đã duyệt
                        <span class="tab-badge">{{ $counts['da_duyet'] }}</span>
                    </a>
                </li>
                <li>
                    <a href="?tab=dang-dien-ra" class="tab-btn {{ $activeTab === 'dang-dien-ra' ? 'active' : '' }}">
                        Đang diễn ra
                        <span class="tab-badge">{{ $counts['dang_dien_ra'] }}</span>
                    </a>
                </li>
                <li>
                    <a href="?tab=da-ket-thuc" class="tab-btn {{ $activeTab === 'da-ket-thuc' ? 'active' : '' }}">
                        Đã kết thúc
                        <span class="tab-badge">{{ $counts['da_ket_thuc'] }}</span>
                    </a>
                </li>
                <li>
                    <a href="?tab=da-huy" class="tab-btn {{ $activeTab === 'da-huy' ? 'active' : '' }}">
                        Đã hủy
                        <span class="tab-badge">{{ $counts['da_huy'] }}</span>
                    </a>
                </li>
            </ul>
        </nav>
        
        <button type="button" id="btn-open-create-modal" class="btn-create">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tạo chương trình mới
        </button>
    </div>

    <!-- FILTERS CARD -->
    <section class="filters-card">
        <form action="" method="GET" class="filters-form">
            <input type="hidden" name="tab" value="{{ $activeTab }}">

            <!-- Search -->
            <div class="form-group">
                <label class="form-label" for="keyword">Tìm kiếm chương trình</label>
                <div class="input-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                    </svg>
                    <input class="form-input form-input-icon" type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="Nhập tên chương trình...">
                </div>
            </div>

            <!-- Status Filter -->
            <div class="form-group">
                <label class="form-label" for="trang_thai">Trạng thái</label>
                <select class="form-input form-select" id="trang_thai" name="trang_thai">
                    <option value="">Tất cả</option>
                    <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="2" {{ request('trang_thai') == '2' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="3" {{ request('trang_thai') == '3' ? 'selected' : '' }}>Đang diễn ra</option>
                    <option value="4" {{ request('trang_thai') == '4' ? 'selected' : '' }}>Đã hủy</option>
                    <option value="5" {{ request('trang_thai') == '5' ? 'selected' : '' }}>Đã kết thúc</option>
                </select>
            </div>

            <!-- Organization -->
            <div class="form-group">
                <label class="form-label" for="don_vi">Đơn vị tổ chức</label>
                <select class="form-input form-select" id="don_vi" name="don_vi">
                    <option value="">Tất cả</option>
                    @foreach ($donVis as $dv)
                        <option value="{{ $dv->Id }}" {{ request('don_vi') == $dv->Id ? 'selected' : '' }}>{{ $dv->TenDonVi }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Start date -->
            <div class="form-group">
                <label class="form-label" for="thoi_gian_bd">Thời gian bắt đầu</label>
                <input class="form-input" type="date" id="thoi_gian_bd" name="thoi_gian_bd" value="{{ request('thoi_gian_bd') }}">
            </div>

            <!-- End date -->
            <div class="form-group">
                <label class="form-label" for="thoi_gian_kt">Thời gian kết thúc</label>
                <input class="form-input" type="date" id="thoi_gian_kt" name="thoi_gian_kt" value="{{ request('thoi_gian_kt') }}">
            </div>

            <!-- Action buttons -->
            <div class="form-group actions-group">
                <button type="submit" class="btn-action btn-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Lọc
                </button>
                <a href="{{ route('admin.chuong-trinh.index', ['tab' => $activeTab]) }}" class="btn-action btn-reset">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Đặt lại
                </a>
            </div>
        </form>
    </section>

    <!-- DATA TABLE CARD -->
    <section class="table-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">STT</th>
                        <th>Tên chương trình</th>
                        <th>Đơn vị tổ chức</th>
                        <th>Địa điểm</th>
                        <th>Thời gian</th>
                        <th>Số người đăng ký / Tối đa</th>
                        <th>Trạng thái</th>
                        <th style="width: 150px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($programs as $index => $prog)
                        @php
                            $statusText = '';
                            $statusClass = '';
                            
                            if ($prog->TrangThai == 1) {
                                $statusText = 'Chờ duyệt';
                                $statusClass = 'badge-pending';
                            } elseif ($prog->TrangThai == 2) {
                                $statusText = 'Sắp diễn ra';
                                $statusClass = 'badge-approved';
                            } elseif ($prog->TrangThai == 3) {
                                $statusText = 'Đang diễn ra';
                                $statusClass = 'badge-ongoing';
                            } elseif ($prog->TrangThai == 4) {
                                $statusText = 'Đã hủy';
                                $statusClass = 'badge-cancelled';
                            } elseif ($prog->TrangThai == 5) {
                                $statusText = 'Đã kết thúc';
                                $statusClass = 'badge-ended';
                            } else {
                                $statusText = 'Không xác định';
                                $statusClass = 'badge-pending';
                            }
                        @endphp
                        <tr>
                            <!-- STT -->
                            <td class="stt-cell">{{ ($programs->currentPage() - 1) * $programs->perPage() + $index + 1 }}</td>
                            
                            <!-- PROGRAM INFO -->
                            <td>
                                <div class="program-cell">
                                    <div class="program-thumbnail" style="{{ Str::startsWith($prog->Banner, 'linear-gradient') ? 'background: ' . $prog->Banner : 'background: url(' . asset($prog->Banner) . ') center/cover no-repeat' }}">
                                        @if(Str::startsWith($prog->Banner, 'linear-gradient'))
                                            <div class="program-thumb-logo">HMTN</div>
                                            <div class="program-thumb-heart">♥</div>
                                            <div class="program-thumb-text">Hiến máu</div>
                                        @endif
                                    </div>
                                    <div class="program-info">
                                        <a href="#" class="program-name btn-edit-trigger" 
                                           data-id="{{ $prog->Id }}" 
                                           data-name="{{ $prog->TenChuongTrinh }}" 
                                           data-desc="{{ $prog->MoTa }}" 
                                           data-banner="{{ $prog->Banner }}" 
                                           data-donvi="{{ $prog->DonViToChucId }}" 
                                           data-diachi="{{ $prog->DiaChi }}" 
                                           data-bando="{{ $prog->BanDo }}" 
                                           data-start="{{ \Carbon\Carbon::parse($prog->ThoiGianBatDau)->format('Y-m-d\TH:i') }}" 
                                           data-end="{{ \Carbon\Carbon::parse($prog->ThoiGianKetThuc)->format('Y-m-d\TH:i') }}" 
                                           data-reg="{{ \Carbon\Carbon::parse($prog->ThoiGianMoDangKy)->format('Y-m-d\TH:i') }}" 
                                           data-max="{{ $prog->SoLuongDuKien }}" 
                                           data-status="{{ $prog->TrangThai }}">{{ $prog->TenChuongTrinh }}</a>
                                        <span class="program-desc">{{ $prog->MoTa }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- ORG -->
                            <td>
                                <div class="org-cell">{{ $prog->TenDonVi }}</div>
                            </td>
                            
                            <!-- LOCATION -->
                            <td>
                                <div class="loc-cell">{{ $prog->DiaChi }}</div>
                            </td>
                            
                            <!-- DATES -->
                            <td>
                                <div class="time-cell">
                                    <div class="time-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($prog->ThoiGianBatDau)->format('d/m/Y H:i') }}</span>
                                    </div>
                                    <div class="time-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span>{{ \Carbon\Carbon::parse($prog->ThoiGianKetThuc)->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- PROGRESS REGISTERED / MAX -->
                            <td>
                                <div class="progress-cell-wrapper">
                                    <div class="progress-label">
                                        <span>{{ $prog->SoNguoiDangKy }} / {{ $prog->SoLuongDuKien }}</span>
                                        <span class="progress-percent">{{ $prog->PhanTram }}%</span>
                                    </div>
                                    <div class="progress-track">
                                        <div class="progress-bar {{ $prog->PhanTram >= 100 ? 'green' : 'blue' }}" style="width: {{ $prog->PhanTram }}%;"></div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- STATUS -->
                            <td>
                                <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                            </td>
                            
                            <!-- ACTIONS -->
                            <td>
                                <div class="actions-cell" style="justify-content: center;">
                                    <button class="btn-table-action btn-view btn-view-trigger" title="Xem chi tiết"
                                            data-id="{{ $prog->Id }}" 
                                            data-name="{{ $prog->TenChuongTrinh }}" 
                                            data-desc="{{ $prog->MoTa }}" 
                                            data-banner="{{ $prog->Banner }}" 
                                            data-donvi="{{ $prog->TenDonVi }}" 
                                            data-diachi="{{ $prog->DiaChi }}" 
                                            data-bando="{{ $prog->BanDo }}" 
                                            data-start="{{ \Carbon\Carbon::parse($prog->ThoiGianBatDau)->format('d/m/Y H:i') }}" 
                                            data-end="{{ \Carbon\Carbon::parse($prog->ThoiGianKetThuc)->format('d/m/Y H:i') }}" 
                                            data-reg="{{ \Carbon\Carbon::parse($prog->ThoiGianMoDangKy)->format('d/m/Y H:i') }}" 
                                            data-max="{{ $prog->SoLuongDuKien }}" 
                                            data-registered="{{ $prog->SoNguoiDangKy }}"
                                            data-status="{{ $statusText }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.755 2.066 2.066 0 011.852-1.15h16.224a2.066 2.066 0 011.853 1.15 1.012 1.012 0 010 .755 2.066 2.066 0 01-1.853 1.15H3.888a2.066 2.066 0 01-1.852-1.15z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    @if ($prog->TrangThai == 1)
                                        <form action="{{ route('admin.chuong-trinh.approve', $prog->Id) }}" method="POST" style="margin: 0; display: inline-flex;">
                                            @csrf
                                            <button type="submit" class="btn-table-action" title="Duyệt chương trình" style="border-color: rgba(16, 185, 129, 0.2); color: var(--success); background-color: var(--success-light); display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; cursor: pointer; transition: all 0.2s ease;">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 16px; height: 16px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <button class="btn-table-action btn-edit btn-edit-trigger" title="Chỉnh sửa"
                                            data-id="{{ $prog->Id }}" 
                                            data-name="{{ $prog->TenChuongTrinh }}" 
                                            data-desc="{{ $prog->MoTa }}" 
                                            data-banner="{{ $prog->Banner }}" 
                                            data-donvi="{{ $prog->DonViToChucId }}" 
                                            data-diachi="{{ $prog->DiaChi }}" 
                                            data-bando="{{ $prog->BanDo }}" 
                                            data-start="{{ \Carbon\Carbon::parse($prog->ThoiGianBatDau)->format('Y-m-d\TH:i') }}" 
                                            data-end="{{ \Carbon\Carbon::parse($prog->ThoiGianKetThuc)->format('Y-m-d\TH:i') }}" 
                                            data-reg="{{ \Carbon\Carbon::parse($prog->ThoiGianMoDangKy)->format('Y-m-d\TH:i') }}" 
                                            data-max="{{ $prog->SoLuongDuKien }}" 
                                            data-status="{{ $prog->TrangThai }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                    <button class="btn-table-action btn-delete btn-delete-trigger" title="Xóa"
                                            data-id="{{ $prog->Id }}" 
                                            data-name="{{ $prog->TenChuongTrinh }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.24 9m4.768-2.912a2.27 2.27 0 01-2.913 0M18 6.75h-.077a2.27 2.27 0 01-2.204-1.815l-.261-1.306a2.27 2.27 0 00-2.204-1.815H10.55a2.27 2.27 0 00-2.204 1.815l-.261 1.306a2.27 2.27 0 01-2.204 1.815H6M18 6.75a2.25 2.25 0 01-2.25 2.25H8.25A2.25 2.25 0 016 6.75m12 0V18a2.25 2.25 0 01-2.25 2.25H8.25A2.25 2.25 0 016 18V6.75m12 0H6" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px; color: var(--neutral-grey);">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 48px; height: 48px; opacity: 0.3;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    <span style="font-size: 15px; font-weight: 500;">Không tìm thấy chương trình nào phù hợp.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TABLE FOOTER / PAGINATION -->
        <footer class="table-footer">
            <span class="footer-info">
                Hiển thị {{ $programs->firstItem() ?? 0 }} - {{ $programs->lastItem() ?? 0 }} trong tổng số {{ $programs->total() }} chương trình
            </span>
            
            <div class="pagination-row">
                <ul class="pagination-list">
                    {{-- Previous Page Link --}}
                    @if ($programs->onFirstPage())
                        <li class="page-item disabled">
                            <span>&lt;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $programs->previousPageUrl() }}">&lt;</a>
                        </li>
                    @endif

                    {{-- First Page if not in range --}}
                    @if($programs->currentPage() > 3)
                        <li class="page-item">
                            <a href="{{ $programs->url(1) }}">1</a>
                        </li>
                        @if($programs->currentPage() > 4)
                            <li class="page-item disabled">
                                <span>...</span>
                            </li>
                        @endif
                    @endif

                    {{-- Pages around current --}}
                    @for($i = max(1, $programs->currentPage() - 2); $i <= min($programs->lastPage(), $programs->currentPage() + 2); $i++)
                        @if ($i == $programs->currentPage())
                            <li class="page-item active">
                                <a href="#">{{ $i }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $programs->url($i) }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor

                    {{-- Last Page if not in range --}}
                    @if($programs->currentPage() < $programs->lastPage() - 2)
                        @if($programs->currentPage() < $programs->lastPage() - 3)
                            <li class="page-item disabled">
                                <span>...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a href="{{ $programs->url($programs->lastPage()) }}">{{ $programs->lastPage() }}</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($programs->hasMorePages())
                        <li class="page-item">
                            <a href="{{ $programs->nextPageUrl() }}">&gt;</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span>&gt;</span>
                        </li>
                    @endif
                </ul>
                
                <div class="page-size-selector">
                    <select class="form-input form-select-sm" style="background-color: #fff;" onchange="window.location.href = this.value">
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => 1]) }}" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 / trang</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 20, 'page' => 1]) }}" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 / trang</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 50, 'page' => 1]) }}" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 / trang</option>
                    </select>
                </div>
            </div>
        </footer>
    </section>

    <!-- ALERT ERROR LIST BANNER -->
    @if ($errors->any())
        <div class="alert-success" style="background-color: var(--danger-light); border-color: rgba(239, 68, 68, 0.2); color: #991b1b; margin-top: 20px; align-items: flex-start; width: 100%;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 22px; height: 22px; flex-shrink: 0; margin-top: 2px; color: var(--danger);">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <span style="font-weight: 700; font-size: 15px;">Đã xảy ra lỗi khi lưu thông tin chương trình:</span>
                <ul style="padding-left: 20px; font-size: 13px; font-weight: 600; line-height: 1.5; text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    
</div>

<!-- CREATE CAMPAIGN MODAL -->
<div id="create-program-modal" class="modal-backdrop">
    <div class="modal-content">
        <header class="modal-header">
            <h3 class="modal-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px; color: var(--primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                Tạo chương trình hiến máu mới
            </h3>
            <button type="button" class="modal-close-btn btn-close-create-modal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <form action="{{ route('admin.chuong-trinh.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="action_type" value="create">
            <div class="modal-body">
                @if ($errors->any() && old('action_type') === 'create')
                    <div style="background-color: #fef2f2; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px; padding: 12px 16px; color: #991b1b; display: flex; gap: 10px; align-items: flex-start; margin-bottom: 16px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; color: var(--danger);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <span style="font-weight: 700; font-size: 13px;">Vui lòng kiểm tra lại thông tin:</span>
                            <ul style="padding-left: 16px; margin: 0; font-size: 12px; font-weight: 600; line-height: 1.5; text-align: left;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Row 1: Program name -->
                <div class="modal-form-group">
                    <label class="modal-label" for="create_TenChuongTrinh">Tên chương trình <span style="color: var(--danger);">*</span></label>
                    <input class="modal-input @error('TenChuongTrinh') is-invalid @enderror" type="text" id="create_TenChuongTrinh" name="TenChuongTrinh" value="{{ old('action_type') === 'create' ? old('TenChuongTrinh') : '' }}" placeholder="Ví dụ: Giọt hồng yêu thương 2026..." required>
                    @error('TenChuongTrinh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 2: Location & Org -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="create_DonViToChucId">Đơn vị tổ chức <span style="color: var(--danger);">*</span></label>
                        <select class="modal-input form-select @error('DonViToChucId') is-invalid @enderror" id="create_DonViToChucId" name="DonViToChucId" required>
                            <option value="">-- Chọn đơn vị tổ chức --</option>
                            @foreach ($donVis as $dv)
                                <option value="{{ $dv->Id }}" {{ (old('action_type') === 'create' && old('DonViToChucId') == $dv->Id) ? 'selected' : '' }}>{{ $dv->TenDonVi }}</option>
                            @endforeach
                        </select>
                        @error('DonViToChucId')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="create_DiaChi">Địa điểm tổ chức <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('DiaChi') is-invalid @enderror" type="text" id="create_DiaChi" name="DiaChi" value="{{ old('action_type') === 'create' ? old('DiaChi') : '' }}" placeholder="Ví dụ: Sảnh A, Tầng 1 Bệnh viện..." required>
                        @error('DiaChi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="create_BanDo">Link bản đồ (Google Maps URL)</label>
                    <input class="modal-input @error('BanDo') is-invalid @enderror" type="url" id="create_BanDo" name="BanDo" value="{{ old('action_type') === 'create' ? old('BanDo') : '' }}" placeholder="Ví dụ: https://maps.google.com/...">
                    @error('BanDo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 3: MoTa -->
                <div class="modal-form-group">
                    <label class="modal-label" for="create_MoTa">Mô tả chương trình <span style="color: var(--danger);">*</span></label>
                    <textarea class="modal-textarea @error('MoTa') is-invalid @enderror" id="create_MoTa" name="MoTa" placeholder="Mô tả nội dung, mục tiêu chương trình hiến máu..." required>{{ old('action_type') === 'create' ? old('MoTa') : '' }}</textarea>
                    @error('MoTa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 4: Dates (Start & End) -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="create_ThoiGianBatDau">Thời gian bắt đầu <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('ThoiGianBatDau') is-invalid @enderror" type="datetime-local" id="create_ThoiGianBatDau" name="ThoiGianBatDau" value="{{ old('action_type') === 'create' ? old('ThoiGianBatDau') : '' }}" required>
                        @error('ThoiGianBatDau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="create_ThoiGianKetThuc">Thời gian kết thúc <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('ThoiGianKetThuc') is-invalid @enderror" type="datetime-local" id="create_ThoiGianKetThuc" name="ThoiGianKetThuc" value="{{ old('action_type') === 'create' ? old('ThoiGianKetThuc') : '' }}" required>
                        @error('ThoiGianKetThuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 5: Registration Dates & Expected Capacity -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="create_ThoiGianMoDangKy">Thời gian mở đăng ký <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('ThoiGianMoDangKy') is-invalid @enderror" type="datetime-local" id="create_ThoiGianMoDangKy" name="ThoiGianMoDangKy" value="{{ old('action_type') === 'create' ? old('ThoiGianMoDangKy') : '' }}" required>
                        @error('ThoiGianMoDangKy')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="create_SoLuongDuKien">Số người tham gia tối đa <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('SoLuongDuKien') is-invalid @enderror" type="number" min="1" id="create_SoLuongDuKien" name="SoLuongDuKien" value="{{ old('action_type') === 'create' ? old('SoLuongDuKien') : '' }}" placeholder="Ví dụ: 200" required>
                        @error('SoLuongDuKien')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 6: Theme Banner -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="create_Banner">Ảnh Banner chương trình <span style="color: var(--danger);">*</span></label>
                        <input type="file" class="modal-input @error('Banner') is-invalid @enderror" id="create_Banner" name="Banner" accept="image/*" required style="padding-top: 8px;">
                        @error('Banner')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <footer class="modal-footer">
                <button type="button" class="btn-modal-cancel btn-close-create-modal">Hủy bỏ</button>
                <button type="submit" class="btn-modal-submit">Tạo chương trình</button>
            </footer>
        </form>
    </div>
</div>

<!-- EDIT CAMPAIGN MODAL -->
<div id="edit-program-modal" class="modal-backdrop">
    <div class="modal-content">
        <header class="modal-header">
            <h3 class="modal-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px; color: var(--warning);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Chỉnh sửa chương trình hiến máu
            </h3>
            <button type="button" class="modal-close-btn btn-close-edit-modal">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <form id="edit-program-form" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="action_type" value="edit">
            <input type="hidden" name="edit_id" id="edit_id" value="{{ old('edit_id') }}">
            <div class="modal-body">
                @if ($errors->any() && old('action_type') === 'edit')
                    <div style="background-color: #fef2f2; border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 12px; padding: 12px 16px; color: #991b1b; display: flex; gap: 10px; align-items: flex-start; margin-bottom: 16px;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; color: var(--danger);">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <span style="font-weight: 700; font-size: 13px;">Vui lòng kiểm tra lại thông tin:</span>
                            <ul style="padding-left: 16px; margin: 0; font-size: 12px; font-weight: 600; line-height: 1.5; text-align: left;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Row 1: Program name -->
                <div class="modal-form-group">
                    <label class="modal-label" for="edit_TenChuongTrinh">Tên chương trình <span style="color: var(--danger);">*</span></label>
                    <input class="modal-input @error('TenChuongTrinh') is-invalid @enderror" type="text" id="edit_TenChuongTrinh" name="TenChuongTrinh" value="{{ old('action_type') === 'edit' ? old('TenChuongTrinh') : '' }}" required>
                    @error('TenChuongTrinh')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 2: Location & Org -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="edit_DonViToChucId">Đơn vị tổ chức <span style="color: var(--danger);">*</span></label>
                        <select class="modal-input form-select @error('DonViToChucId') is-invalid @enderror" id="edit_DonViToChucId" name="DonViToChucId" required>
                            <option value="">-- Chọn đơn vị tổ chức --</option>
                            @foreach ($donVis as $dv)
                                <option value="{{ $dv->Id }}" {{ (old('action_type') === 'edit' && old('DonViToChucId') == $dv->Id) ? 'selected' : '' }}>{{ $dv->TenDonVi }}</option>
                            @endforeach
                        </select>
                        @error('DonViToChucId')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="edit_DiaChi">Địa điểm tổ chức <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('DiaChi') is-invalid @enderror" type="text" id="edit_DiaChi" name="DiaChi" value="{{ old('action_type') === 'edit' ? old('DiaChi') : '' }}" required>
                        @error('DiaChi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-form-group">
                    <label class="modal-label" for="edit_BanDo">Link bản đồ (Google Maps URL)</label>
                    <input class="modal-input @error('BanDo') is-invalid @enderror" type="url" id="edit_BanDo" name="BanDo" value="{{ old('action_type') === 'edit' ? old('BanDo') : '' }}" placeholder="Ví dụ: https://maps.google.com/...">
                    @error('BanDo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 3: MoTa -->
                <div class="modal-form-group">
                    <label class="modal-label" for="edit_MoTa">Mô tả chương trình <span style="color: var(--danger);">*</span></label>
                    <textarea class="modal-textarea @error('MoTa') is-invalid @enderror" id="edit_MoTa" name="MoTa" required>{{ old('action_type') === 'edit' ? old('MoTa') : '' }}</textarea>
                    @error('MoTa')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 4: Dates (Start & End) -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="edit_ThoiGianBatDau">Thời gian bắt đầu <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('ThoiGianBatDau') is-invalid @enderror" type="datetime-local" id="edit_ThoiGianBatDau" name="ThoiGianBatDau" value="{{ old('action_type') === 'edit' ? old('ThoiGianBatDau') : '' }}" required>
                        @error('ThoiGianBatDau')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="edit_ThoiGianKetThuc">Thời gian kết thúc <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('ThoiGianKetThuc') is-invalid @enderror" type="datetime-local" id="edit_ThoiGianKetThuc" name="ThoiGianKetThuc" value="{{ old('action_type') === 'edit' ? old('ThoiGianKetThuc') : '' }}" required>
                        @error('ThoiGianKetThuc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 5: Registration Dates & Expected Capacity -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="edit_ThoiGianMoDangKy">Thời gian mở đăng ký <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('ThoiGianMoDangKy') is-invalid @enderror" type="datetime-local" id="edit_ThoiGianMoDangKy" name="ThoiGianMoDangKy" value="{{ old('action_type') === 'edit' ? old('ThoiGianMoDangKy') : '' }}" required>
                        @error('ThoiGianMoDangKy')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="edit_SoLuongDuKien">Số người tham gia tối đa <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('SoLuongDuKien') is-invalid @enderror" type="number" min="1" id="edit_SoLuongDuKien" name="SoLuongDuKien" value="{{ old('action_type') === 'edit' ? old('SoLuongDuKien') : '' }}" required>
                        @error('SoLuongDuKien')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 6: Theme Banner & Status -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="edit_Banner">Thay đổi ảnh Banner</label>
                        <input type="file" class="modal-input @error('Banner') is-invalid @enderror" id="edit_Banner" name="Banner" accept="image/*" style="padding-top: 8px;">
                        <span style="font-size: 11px; color: var(--neutral-grey); margin-top: 4px; display: block; font-weight: 600;">Để trống nếu muốn giữ nguyên banner hiện tại.</span>
                        @error('Banner')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="edit_TrangThai">Trạng thái <span style="color: var(--danger);">*</span></label>
                        <select class="modal-input form-select @error('TrangThai') is-invalid @enderror" id="edit_TrangThai" name="TrangThai" required>
                            <option value="1" {{ (old('action_type') === 'edit' && old('TrangThai') == '1') ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="2" {{ (old('action_type') === 'edit' && old('TrangThai') == '2') ? 'selected' : '' }}>Đã duyệt</option>
                            <option value="3" {{ (old('action_type') === 'edit' && old('TrangThai') == '3') ? 'selected' : '' }}>Đang diễn ra</option>
                            <option value="4" {{ (old('action_type') === 'edit' && old('TrangThai') == '4') ? 'selected' : '' }}>Đã hủy</option>
                            <option value="5" {{ (old('action_type') === 'edit' && old('TrangThai') == '5') ? 'selected' : '' }}>Đã kết thúc</option>
                        </select>
                        @error('TrangThai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <footer class="modal-footer">
                <button type="button" class="btn-modal-cancel btn-close-edit-modal">Hủy bỏ</button>
                <button type="submit" class="btn-modal-submit">Lưu thay đổi</button>
            </footer>
        </form>
    </div>
</div>

<!-- VIEW CAMPAIGN DETAILS MODAL -->
<div id="view-program-modal" class="modal-backdrop">
    <div class="modal-content">
        <header class="modal-header">
            <h3 class="modal-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px; color: var(--primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                Chi tiết chương trình hiến máu
            </h3>
            <button type="button" id="btn-close-view-modal" class="modal-close-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <div class="modal-body" style="gap: 20px;">
            <!-- Header banner preview inside modal -->
            <div id="view_BannerContainer" style="border-radius: 12px; height: 100px; display: flex; flex-direction: column; justify-content: space-between; padding: 16px; color: #fff; font-family: var(--font-heading); box-shadow: var(--shadow-sm);">
                <div style="font-size: 10px; font-weight: 700; background-color: rgba(255,255,255,0.25); padding: 2px 6px; border-radius: 4px; align-self: flex-start; text-transform: uppercase;">Chương trình hiến máu</div>
                <div style="font-size: 24px; align-self: center; animation: pulse 2s infinite;">♥</div>
                <div style="font-size: 10px; font-weight: 700; text-transform: uppercase; text-align: center; letter-spacing: 0.5px; opacity: 0.9;">Đồng hành cứu người</div>
            </div>

            <!-- Fields details -->
            <div style="display: flex; flex-direction: column; gap: 14px;">
                <div>
                    <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Tên chương trình</span>
                    <span id="view_TenChuongTrinh" style="font-size: 16px; font-weight: 700; color: var(--neutral-dark);">—</span>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Đơn vị tổ chức</span>
                        <span id="view_DonVi" style="font-size: 14px; font-weight: 600; color: var(--neutral-dark);">—</span>
                    </div>
                    <div>
                        <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Trạng thái</span>
                        <span id="view_TrangThai" style="font-size: 14px; font-weight: 700; color: var(--primary);">—</span>
                    </div>
                </div>

                <div>
                    <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Địa điểm tổ chức</span>
                    <span id="view_DiaChi" style="font-size: 14px; font-weight: 600; color: var(--neutral-dark);">—</span>
                </div>

                <div>
                    <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Link bản đồ</span>
                    <a id="view_BanDoLink" href="#" target="_blank" style="font-size: 14px; font-weight: 600; color: var(--primary); text-decoration: none;"><i class="fa-solid fa-map-location-dot"></i> Xem trên Google Maps</a>
                    <span id="view_BanDoEmpty" style="font-size: 14px; font-weight: 600; color: var(--neutral-grey); display: none;">Chưa cập nhật</span>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Thời gian bắt đầu</span>
                        <span id="view_BatDau" style="font-size: 14px; font-weight: 600; color: var(--neutral-dark);">—</span>
                    </div>
                    <div>
                        <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Thời gian kết thúc</span>
                        <span id="view_KetThuc" style="font-size: 14px; font-weight: 600; color: var(--neutral-dark);">—</span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Mở đăng ký vào lúc</span>
                        <span id="view_MoDangKy" style="font-size: 14px; font-weight: 600; color: var(--neutral-dark);">—</span>
                    </div>
                    <div>
                        <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Số người đăng ký / Dự kiến</span>
                        <span id="view_NguoiThamGia" style="font-size: 14px; font-weight: 700; color: var(--neutral-dark);">—</span>
                    </div>
                </div>

                <div>
                    <span style="font-size: 12px; font-weight: 700; color: var(--neutral-grey); text-transform: uppercase; display: block; margin-bottom: 4px;">Mô tả chi tiết</span>
                    <span id="view_MoTa" style="font-size: 14px; color: #475569; line-height: 1.5; display: block; white-space: pre-line;">—</span>
                </div>
            </div>
        </div>

        <footer class="modal-footer">
            <button type="button" id="btn-close-view-footer" class="btn-modal-cancel" style="width: 100px;">Đóng</button>
        </footer>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="delete-program-modal" class="modal-backdrop">
    <div class="modal-content" style="max-width: 450px;">
        <header class="modal-header" style="border-bottom: none; padding-bottom: 0;">
            <h3 class="modal-title" style="color: var(--danger);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; height: 24px; color: var(--danger);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                Xác nhận xóa chương trình
            </h3>
        </header>

        <form id="delete-program-form" action="" method="POST">
            @csrf
            <div class="modal-body" style="padding-top: 10px; gap: 8px;">
                <p style="font-size: 14px; line-height: 1.5; color: #475569;">
                    Bạn có chắc chắn muốn xóa chương trình hiến máu dưới đây? Hành động này sẽ chuyển chương trình vào trạng thái lưu trữ.
                </p>
                <div style="background-color: var(--danger-light); padding: 12px; border-radius: 8px; border: 1px solid rgba(239, 68, 68, 0.1); margin-top: 8px;">
                    <span id="delete_program_name" style="font-size: 14px; font-weight: 700; color: var(--danger);">—</span>
                </div>
            </div>

            <footer class="modal-footer" style="background-color: transparent; border-top: none;">
                <button type="button" class="btn-modal-cancel btn-close-delete-modal">Hủy bỏ</button>
                <button type="submit" class="btn-modal-submit" style="background-color: var(--danger);">Đồng ý xóa</button>
            </footer>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Create Modal elements
        const createModal = document.getElementById('create-program-modal');
        const openCreateBtn = document.getElementById('btn-open-create-modal');
        const closeCreateBtns = document.querySelectorAll('.btn-close-create-modal');

        function showCreateModal() {
            createModal.classList.add('show');
        }
        function hideCreateModal() {
            createModal.classList.remove('show');
        }

        if (openCreateBtn) openCreateBtn.addEventListener('click', showCreateModal);
        closeCreateBtns.forEach(btn => btn.addEventListener('click', hideCreateModal));

        // Edit Modal elements
        const editModal = document.getElementById('edit-program-modal');
        const editForm = document.getElementById('edit-program-form');
        const closeEditBtns = document.querySelectorAll('.btn-close-edit-modal');
        const editTriggers = document.querySelectorAll('.btn-edit-trigger');

        function showEditModal(id, name, desc, banner, donvi, diachi, bando, start, end, reg, max, status) {
            editForm.action = `/admin/chuong-trinh/${id}/update`;
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_TenChuongTrinh').value = name;
            document.getElementById('edit_MoTa').value = desc;
            document.getElementById('edit_Banner').value = '';
            document.getElementById('edit_DonViToChucId').value = donvi;
            document.getElementById('edit_DiaChi').value = diachi;
            document.getElementById('edit_BanDo').value = bando;
            document.getElementById('edit_ThoiGianBatDau').value = start;
            document.getElementById('edit_ThoiGianKetThuc').value = end;
            document.getElementById('edit_ThoiGianMoDangKy').value = reg;
            document.getElementById('edit_SoLuongDuKien').value = max;
            document.getElementById('edit_TrangThai').value = status;
            editModal.classList.add('show');
        }

        function hideEditModal() {
            editModal.classList.remove('show');
        }

        editTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const desc = this.getAttribute('data-desc');
                const banner = this.getAttribute('data-banner');
                const donvi = this.getAttribute('data-donvi');
                const diachi = this.getAttribute('data-diachi');
                const bando = this.getAttribute('data-bando');
                const start = this.getAttribute('data-start');
                const end = this.getAttribute('data-end');
                const reg = this.getAttribute('data-reg');
                const max = this.getAttribute('data-max');
                const status = this.getAttribute('data-status');
                showEditModal(id, name, desc, banner, donvi, diachi, bando, start, end, reg, max, status);
            });
        });

        closeEditBtns.forEach(btn => btn.addEventListener('click', hideEditModal));

        // View Modal elements
        const viewModal = document.getElementById('view-program-modal');
        const closeViewBtnHeader = document.getElementById('btn-close-view-modal');
        const closeViewBtnFooter = document.getElementById('btn-close-view-footer');
        const viewTriggers = document.querySelectorAll('.btn-view-trigger');

        function showViewModal(name, desc, banner, donvi, diachi, bando, start, end, reg, max, registered, status) {
            document.getElementById('view_TenChuongTrinh').textContent = name;
            document.getElementById('view_MoTa').textContent = desc;
            document.getElementById('view_DonVi').textContent = donvi;
            document.getElementById('view_DiaChi').textContent = diachi;
            document.getElementById('view_BatDau').textContent = start;
            document.getElementById('view_KetThuc').textContent = end;
            document.getElementById('view_MoDangKy').textContent = reg;
            document.getElementById('view_NguoiThamGia').textContent = `${registered} / ${max}`;
            document.getElementById('view_TrangThai').textContent = status;

            const mapLink = document.getElementById('view_BanDoLink');
            const mapEmpty = document.getElementById('view_BanDoEmpty');
            if (bando) {
                mapLink.href = bando;
                mapLink.style.display = 'inline-block';
                mapEmpty.style.display = 'none';
            } else {
                mapLink.style.display = 'none';
                mapEmpty.style.display = 'block';
            }

            if (banner.startsWith('linear-gradient')) {
                document.getElementById('view_BannerContainer').style.background = banner;
            } else {
                const path = banner.startsWith('http') || banner.startsWith('/') ? banner : '/' + banner;
                document.getElementById('view_BannerContainer').style.background = `url('${path}') center/cover no-repeat`;
            }
            viewModal.classList.add('show');
        }

        function hideViewModal() {
            viewModal.classList.remove('show');
        }

        viewTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const name = this.getAttribute('data-name');
                const desc = this.getAttribute('data-desc');
                const banner = this.getAttribute('data-banner');
                const donvi = this.getAttribute('data-donvi');
                const diachi = this.getAttribute('data-diachi');
                const bando = this.getAttribute('data-bando');
                const start = this.getAttribute('data-start');
                const end = this.getAttribute('data-end');
                const reg = this.getAttribute('data-reg');
                const max = this.getAttribute('data-max');
                const registered = this.getAttribute('data-registered');
                const status = this.getAttribute('data-status');
                showViewModal(name, desc, banner, donvi, diachi, bando, start, end, reg, max, registered, status);
            });
        });

        if (closeViewBtnHeader) closeViewBtnHeader.addEventListener('click', hideViewModal);
        if (closeViewBtnFooter) closeViewBtnFooter.addEventListener('click', hideViewModal);

        // Delete Modal elements
        const deleteModal = document.getElementById('delete-program-modal');
        const deleteForm = document.getElementById('delete-program-form');
        const deleteProgramName = document.getElementById('delete_program_name');
        const closeDeleteBtns = document.querySelectorAll('.btn-close-delete-modal');
        const deleteTriggers = document.querySelectorAll('.btn-delete-trigger');

        function showDeleteModal(id, name) {
            deleteForm.action = `/admin/chuong-trinh/${id}/delete`;
            deleteProgramName.textContent = name;
            deleteModal.classList.add('show');
        }

        function hideDeleteModal() {
            deleteModal.classList.remove('show');
        }

        deleteTriggers.forEach(trigger => {
            trigger.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                showDeleteModal(id, name);
            });
        });

        closeDeleteBtns.forEach(btn => btn.addEventListener('click', hideDeleteModal));

        // Click outside backdrop to close
        window.addEventListener('click', function (e) {
            if (e.target === createModal) hideCreateModal();
            if (e.target === editModal) hideEditModal();
            if (e.target === viewModal) hideViewModal();
            if (e.target === deleteModal) hideDeleteModal();
        });

        // Auto re-open correct modal on validation errors
        @if ($errors->any())
            @if(old('action_type') === 'edit')
                const oldEditId = "{{ old('edit_id') }}";
                const matchingEditBtn = document.querySelector(`.btn-edit-trigger[data-id="${oldEditId}"]`);
                if (matchingEditBtn) {
                    // Populate from data attributes but override with old inputs
                    const id = oldEditId;
                    const name = "{{ old('TenChuongTrinh') }}";
                    const desc = "{{ old('MoTa') }}";
                    const banner = "{{ old('Banner') }}";
                    const donvi = "{{ old('DonViToChucId') }}";
                    const diachi = "{{ old('DiaChi') }}";
                    const bando = "{{ old('BanDo') }}";
                    const start = "{{ old('ThoiGianBatDau') }}";
                    const end = "{{ old('ThoiGianKetThuc') }}";
                    const reg = "{{ old('ThoiGianMoDangKy') }}";
                    const max = "{{ old('SoLuongDuKien') }}";
                    const status = "{{ old('TrangThai') }}";
                    showEditModal(id, name, desc, banner, donvi, diachi, bando, start, end, reg, max, status);
                } else {
                    editModal.classList.add('show');
                }
            @else
                showCreateModal();
            @endif
        @endif
    });
</script>
@endpush

