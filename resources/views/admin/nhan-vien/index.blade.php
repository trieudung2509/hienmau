@extends('admin.nhan-vien.layout')

@section('title', 'Trang nhân viên | Cổng thông tin Hiến Máu Tình Nguyện')
@section('navbar_title', 'Trang nhân viên')

@section('content')
    <!-- Greeting -->
    <div class="greeting-section">
        <h2 class="greeting-title">Xin chào, {{ $staff->HoTen }}!</h2>
        <span class="greeting-subtitle">Hôm nay là {{ ucfirst(\Carbon\Carbon::now()->locale('vi')->translatedFormat('l')) }}, ngày {{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
    </div>

    <!-- Stats grid -->
    <section class="stats-grid">
        <!-- Stat 1: Total participants -->
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Tổng người tham gia</span>
                <span class="stat-value">{{ $metrics['tong_nguoi_tg'] }}</span>
                <span class="stat-label">Hôm nay</span>
            </div>
        </div>

        <!-- Stat 2: Donated -->
        <div class="stat-card">
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Đã hiến máu</span>
                <span class="stat-value">{{ $metrics['da_hien'] }}</span>
                <span class="stat-label">Hôm nay</span>
            </div>
        </div>

        <!-- Stat 3: Blood volume -->
        <div class="stat-card">
            <div class="stat-icon orange">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Lượng máu thu được</span>
                <span class="stat-value" style="color: var(--warning);">{{ $metrics['luong_mau'] }} <span style="font-size: 14px; font-weight: 700;">ml</span></span>
                <span class="stat-label">Hôm nay</span>
            </div>
        </div>

        <!-- Stat 4: Waiting -->
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Đang chờ hiến</span>
                <span class="stat-value" style="color: #8b5cf6;">{{ $metrics['dang_cho'] }}</span>
                <span class="stat-label">Hôm nay</span>
            </div>
        </div>
    </section>

    <!-- TWO COLUMNS LAYOUT -->
    <section class="content-columns">
        
        <!-- LEFT COLUMN: SEARCH CARD -->
        <div class="card-box search-dossier-wrapper">
            <h3 class="dossier-heading">Tra cứu hồ sơ</h3>
            
            <div class="dossier-tabs">
                <button class="dossier-tab-btn active">Tìm kiếm nhanh</button>
            </div>
            
            <div class="dossier-body">
                <form action="{{ route('nhan-vien.ho-so') }}" method="GET" class="dossier-form">
                    <div class="form-group-dossier">
                        <svg class="dossier-input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                        </svg>
                        <input class="dossier-input" type="text" name="search" value="{{ request('search') }}" placeholder="Nhập họ tên hoặc số điện thoại...">
                    </div>
                    
                    <button type="submit" class="btn-dossier-search">Tra cứu</button>
                </form>
                
                <!-- Illustration Panel -->
                <div class="dossier-promo-panel">
                    <svg class="promo-illustration" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 80" fill="none" style="width: 100%; height: 90px;">
                        <rect x="25" y="10" width="50" height="60" rx="6" fill="#fff" stroke="#e2e8f0" stroke-width="2"/>
                        <circle cx="50" cy="30" r="10" fill="#eff6ff" stroke="#3b82f6" stroke-width="2"/>
                        <circle cx="50" cy="28" r="4" fill="#3b82f6"/>
                        <path d="M42 42h16v2H42zM45 48h10v2H45z" fill="#cbd5e1"/>
                        <circle cx="70" cy="55" r="12" fill="#fff" stroke="#3b82f6" stroke-width="3"/>
                        <line x1="78" y1="63" x2="88" y2="73" stroke="#3b82f6" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                    <span class="promo-text">Tìm kiếm người tham gia trong chương trình để xem thông tin hồ sơ và cập nhật kết quả hiến máu.</span>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: TODAY'S PROGRAM & QUICK ACTIONS -->
        <div class="right-column-wrapper">
            <!-- Quick actions -->
            <div class="quick-actions-box">
                <a href="#" class="action-row-btn green" onclick="event.preventDefault(); openCreateModal();">
                    <div class="action-row-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <div class="action-row-info">
                        <span class="action-row-title">Thêm mới hồ sơ</span>
                        <span class="action-row-subtitle">Thêm người tham gia chưa có trong hệ thống</span>
                    </div>
                    <svg class="action-row-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
                
                <a href="#" class="action-row-btn blue">
                    <div class="action-row-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <div class="action-row-info">
                        <span class="action-row-title">Cập nhật kết quả hiến máu</span>
                        <span class="action-row-subtitle">Nhập kết quả sau khi hiến máu</span>
                    </div>
                    <svg class="action-row-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
                
                <a href="#" class="action-row-btn orange">
                    <div class="action-row-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </div>
                    <div class="action-row-info">
                        <span class="action-row-title">Danh sách chờ hiến</span>
                        <span class="action-row-subtitle">Xem danh sách người đang chờ</span>
                    </div>
                    <svg class="action-row-chevron" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </div>

    </section>

    <!-- BOTTOM SECTION: REGISTRATION LIST TABLE -->
    <section class="bottom-section">
        
        <header class="bottom-header">
            <h3 class="bottom-title">Danh sách người tham gia hôm nay ({{ $participants->total() }})</h3>
            
            <div class="bottom-actions-row" style="display: flex; gap: 10px;">
                <input class="search-table-input filter-input" style="height: 36px; padding: 4px 16px; font-size: 13px;" type="text" placeholder="Tìm trong danh sách...">
                
                <button type="button" class="btn-filter-reset" style="height: 36px; font-size: 13px;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 14px; height: 14px; margin-right: 4px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Bộ lọc
                </button>
            </div>
        </header>

        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">STT</th>
                        <th>Họ và tên</th>
                        <th>SĐT</th>
                        <th>Giới tính</th>
                        <th>Nhóm máu</th>
                        <th>Tình trạng</th>
                        <th style="width: 150px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($participants as $p)
                        <tr>
                            <!-- STT -->
                            <td class="stt-cell">{{ $p['STT'] }}</td>
                            
                            <!-- NAME & DOB -->
                            <td>
                                <div class="user-cell">
                                    <div class="user-table-avatar color-{{ $p['STT'] % 5 }}">
                                        <span>{{ mb_substr($p['HoTen'], 0, 1) }}</span>
                                    </div>
                                    <div class="user-info-text">
                                        <span class="user-name-text">{{ $p['HoTen'] }}</span>
                                        <span class="user-dob-text">{{ $p['NgaySinh'] }}</span>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- PHONE -->
                            <td>
                                <span class="phone-text">{{ $p['SDT'] }}</span>
                            </td>
                            
                            <!-- GENDER -->
                            <td>{{ $p['GioiTinh'] }}</td>
                            
                            <!-- BLOOD TYPE -->
                            <td>
                                <span style="font-weight: 700;">{{ $p['NhomMau'] }}</span>
                            </td>
                            
                            <!-- STATUS / VOLUMES -->
                            <td>
                                <span class="badge {{ $p['TinhTrangClass'] }}">{{ $p['TinhTrang'] }}</span>
                                @if($p['LuongMau'])
                                    <span class="volume-badge">{{ $p['LuongMau'] }}</span>
                                @endif
                            </td>
                            
                            <!-- ACTION ACTIONS -->
                            <td>
                                <div class="actions-cell" style="justify-content: center;">
                                    <button class="btn-table-action btn-view" title="Xem hồ sơ">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.755 2.066 2.066 0 011.852-1.15h16.224a2.066 2.066 0 011.853 1.15 1.012 1.012 0 010 .755 2.066 2.066 0 01-1.853 1.15H3.888a2.066 2.066 0 01-1.852-1.15z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button class="btn-table-action btn-table-action" title="Cập nhật kết quả">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button class="btn-table-action btn-table-action" title="Xuất chứng nhận">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px; color: var(--neutral-grey);">
                                Không tìm thấy người tham gia nào phù hợp trong danh sách hôm nay.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TABLE FOOTER -->
        <footer class="table-footer">
            <span class="footer-info">
                Hiển thị {{ $participants->firstItem() ?? 0 }} - {{ $participants->lastItem() ?? 0 }} trong tổng số {{ $participants->total() }} người
            </span>
            
            <div class="pagination-row">
                <ul class="pagination-list">
                    @if ($participants->onFirstPage())
                        <li class="page-item disabled">
                            <span>&lt;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $participants->previousPageUrl() }}">&lt;</a>
                        </li>
                    @endif

                    @if($participants->currentPage() > 3)
                        <li class="page-item">
                            <a href="{{ $participants->url(1) }}">1</a>
                        </li>
                        @if($participants->currentPage() > 4)
                            <li class="page-item disabled">
                                <span>...</span>
                            </li>
                        @endif
                    @endif

                    @for($i = max(1, $participants->currentPage() - 2); $i <= min($participants->lastPage(), $participants->currentPage() + 2); $i++)
                        @if ($i == $participants->currentPage())
                            <li class="page-item active">
                                <a href="#">{{ $i }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $participants->url($i) }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor

                    @if($participants->currentPage() < $participants->lastPage() - 2)
                        @if($participants->currentPage() < $participants->lastPage() - 3)
                            <li class="page-item disabled">
                                <span>...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a href="{{ $participants->url($participants->lastPage()) }}">{{ $participants->lastPage() }}</a>
                        </li>
                    @endif

                    @if ($participants->hasMorePages())
                        <li class="page-item">
                            <a href="{{ $participants->nextPageUrl() }}">&gt;</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span>&gt;</span>
                        </li>
                    @endif
                </ul>
                
                <div class="page-size-selector">
                    <select class="form-input filter-input" style="background-color: #fff; width: 110px; height: 34px; padding: 4px 10px; font-size: 13px; border-radius: 8px;" onchange="window.location.href = this.value">
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 10, 'page' => 1]) }}" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 / trang</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 20, 'page' => 1]) }}" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20 / trang</option>
                        <option value="{{ request()->fullUrlWithQuery(['per_page' => 50, 'page' => 1]) }}" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 / trang</option>
                    </select>
                </div>
            </div>
        </footer>
    </section>
@endsection
