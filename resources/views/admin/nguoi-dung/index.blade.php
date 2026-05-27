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

@section('title', 'Quản lý người dùng')
@section('navbar-title', 'Quản lý người dùng')
@section('navbar-subtitle', 'Quản lý, cấp quyền, đóng băng và theo dõi tài khoản người dùng toàn hệ thống.')


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

    <!-- STATS SECTION -->
    <section class="stats-grid">
        <!-- Stat 1: Total Users -->
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Tổng số người dùng</span>
                <span class="stat-value">{{ number_format($stats['total'], 0, ',', '.') }}</span>
                <span class="stat-label">Tất cả tài khoản</span>
            </div>
        </div>

        <!-- Stat 2: Participants -->
        <div class="stat-card">
            <div class="stat-icon green">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Người tham gia</span>
                <span class="stat-value">{{ number_format($stats['participants'], 0, ',', '.') }}</span>
                <span class="stat-label">{{ $stats['percent_participants'] }}% tổng số</span>
            </div>
        </div>

        <!-- Stat 3: Orgs -->
        <div class="stat-card">
            <div class="stat-icon orange">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Đơn vị tổ chức</span>
                <span class="stat-value">{{ number_format($stats['orgs'], 0, ',', '.') }}</span>
                <span class="stat-label">{{ $stats['percent_orgs'] }}% tổng số</span>
            </div>
        </div>

        <!-- Stat 4: Employees -->
        <div class="stat-card">
            <div class="stat-icon purple">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Nhân viên</span>
                <span class="stat-value">{{ number_format($stats['employees'], 0, ',', '.') }}</span>
                <span class="stat-label">{{ $stats['percent_employees'] }}% tổng số</span>
            </div>
        </div>
    </section>

    <!-- FILTERS CARD -->
    <section class="filters-card">
        <form action="" method="GET" class="filters-form">
            <!-- Search -->
            <div class="form-group">
                <label class="form-label" for="keyword">Tìm kiếm người dùng</label>
                <div class="input-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                    </svg>
                    <input class="form-input form-input-icon" type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm kiếm người dùng...">
                </div>
            </div>

            <!-- Role Filter -->
            <div class="form-group">
                <label class="form-label" for="vai_tro">Vai trò</label>
                <select class="form-input form-select" id="vai_tro" name="vai_tro">
                    <option value="">Tất cả vai trò</option>
                    @foreach ($roles as $r)
                        @if ($r->TenVaiTro !== 'Người dùng')
                            <option value="{{ $r->Id }}" {{ request('vai_tro') == $r->Id ? 'selected' : '' }}>{{ $r->TenVaiTro }}</option>
                        @endif
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="form-group">
                <label class="form-label" for="trang_thai">Trạng thái</label>
                <select class="form-input form-select" id="trang_thai" name="trang_thai">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="2" {{ request('trang_thai') == '2' ? 'selected' : '' }}>Đã đóng băng</option>
                </select>
            </div>

            <!-- Action buttons -->
            <div class="form-group actions-group">
                <button type="submit" class="btn-action btn-filter">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Lọc
                </button>
                <a href="{{ route('admin.nguoi-dung.index') }}" class="btn-action btn-reset">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Đặt lại
                </a>
            </div>

            <!-- Create button -->
            <div class="form-group" style="justify-self: flex-end;">
                <button type="button" id="btn-open-create-modal" class="btn-create">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Thêm người dùng
                </button>
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
                        <th>Họ và tên</th>
                        <th>Số điện thoại / Email</th>
                        <th>Vai trò</th>
                        <th>Đơn vị</th>
                        <th>Ngày tạo</th>
                        <th>Trạng thái</th>
                        <th style="width: 150px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $index => $u)
                        <tr>
                            <!-- STT -->
                            <td class="stt-cell">{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            
                            <!-- AVATAR & NAME -->
                            <td>
                                <div class="user-cell">
                                    <div class="user-table-avatar color-{{ $index % 5 }}">
                                        @if($u->TenVaiTro === 'Đơn vị tổ chức')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 16px; height: 16px; color: #fff;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M2.25 5.625c0-.621.504-1.125 1.125-1.125h17.25c.621 0 1.125.504 1.125 1.125v15.75c0 .621-.504 1.125-1.125 1.125H3.375c-.621 0-1.125-.504-1.125-1.125V5.625z" />
                                            </svg>
                                        @else
                                            <span>{{ mb_substr($u->HoTen, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <a href="#" class="user-table-name">{{ $u->HoTen }}</a>
                                </div>
                            </td>
                            
                            <!-- PHONE & EMAIL -->
                            <td>
                                <div class="contact-cell">
                                    <span class="contact-phone">{{ $u->SoDienThoai }}</span>
                                    <span class="contact-email">{{ $u->Email }}</span>
                                </div>
                            </td>
                            
                            <!-- ROLE BADGE -->
                            <td>
                                @if ($u->TenVaiTro === 'Quản trị viên')
                                    <span class="role-badge role-admin">Quản trị viên</span>
                                @elseif ($u->TenVaiTro === 'Nhân viên')
                                    <span class="role-badge role-employee">Nhân viên</span>
                                @elseif ($u->TenVaiTro === 'Đơn vị tổ chức')
                                    <span class="role-badge role-org">Đơn vị tổ chức</span>
                                @elseif ($u->TenVaiTro === 'Người tham gia')
                                    <span class="role-badge role-participant">Người tham gia</span>
                                @else
                                    <span class="role-badge role-user">{{ $u->TenVaiTro }}</span>
                                @endif
                            </td>
                            
                            <!-- UNIT -->
                            <td>
                                <div class="unit-cell">{{ $u->DonVi }}</div>
                            </td>
                            
                            <!-- CREATION DATE -->
                            <td>
                                <div class="date-cell">
                                    <span>{{ \Carbon\Carbon::parse($u->created_at)->format('d/m/Y') }}</span>
                                    <span class="date-time">{{ \Carbon\Carbon::parse($u->created_at)->format('H:i') }}</span>
                                </div>
                            </td>
                            
                            <!-- STATUS DOT -->
                            <td>
                                @if ($u->TrangThai == 1)
                                    <span class="status-dot-wrapper status-active-dot">Hoạt động</span>
                                @else
                                    <span class="status-dot-wrapper status-frozen-dot">Đã đóng băng</span>
                                @endif
                            </td>
                            
                            <!-- ACTIONS -->
                            <td>
                                <div class="actions-cell">
                                    <button type="button" class="btn-table-action btn-view" title="Xem chi tiết"
                                            data-id="{{ $u->Id }}"
                                            data-hoten="{{ $u->HoTen }}"
                                            data-email="{{ $u->Email }}"
                                            data-sodienthoai="{{ $u->SoDienThoai }}"
                                            data-vaitroid="{{ $u->VaiTroId }}"
                                            data-vaitroten="{{ $u->TenVaiTro }}"
                                            data-trangthai="{{ $u->TrangThai }}"
                                            data-donvi="{{ $u->DonVi }}"
                                            data-ngaysinh="{{ $u->NgaySinh }}"
                                            data-gioitinh="{{ $u->GioiTinh }}"
                                            data-created="{{ \Carbon\Carbon::parse($u->created_at)->format('d/m/Y H:i') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.755 2.066 2.066 0 011.852-1.15h16.224a2.066 2.066 0 011.853 1.15 1.012 1.012 0 010 .755 2.066 2.066 0 01-1.853 1.15H3.888a2.066 2.066 0 01-1.852-1.15z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button type="button" class="btn-table-action btn-edit" title="Chỉnh sửa"
                                            data-id="{{ $u->Id }}"
                                            data-hoten="{{ $u->HoTen }}"
                                            data-email="{{ $u->Email }}"
                                            data-sodienthoai="{{ $u->SoDienThoai }}"
                                            data-vaitroid="{{ $u->VaiTroId }}"
                                            data-trangthai="{{ $u->TrangThai }}"
                                            data-ngaysinh="{{ $u->NgaySinh }}"
                                            data-gioitinh="{{ $u->GioiTinh }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                    
                                    <form action="{{ route('admin.nguoi-dung.toggle-status', $u->Id) }}" method="POST" style="margin: 0; display: inline-flex;">
                                        @csrf
                                        @if ($u->TrangThai == 1)
                                            <button type="submit" class="btn-table-action btn-lock" title="Đóng băng (Khoá tài khoản)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                </svg>
                                            </button>
                                        @else
                                            <button type="submit" class="btn-table-action btn-unlock" title="Kích hoạt (Mở khoá tài khoản)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                </svg>
                                            </button>
                                        @endif
                                    </form>
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
                                    <span style="font-size: 15px; font-weight: 500;">Không tìm thấy người dùng nào phù hợp.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- TABLE FOOTER -->
        <footer class="table-footer">
            <span class="footer-info">
                Hiển thị {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} trong tổng số {{ $users->total() }} người dùng
            </span>
            
            <div class="pagination-row">
                <ul class="pagination-list">
                    {{-- Previous Page Link --}}
                    @if ($users->onFirstPage())
                        <li class="page-item disabled">
                            <span>&lt;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $users->previousPageUrl() }}">&lt;</a>
                        </li>
                    @endif

                    {{-- First Page if not in range --}}
                    @if($users->currentPage() > 3)
                        <li class="page-item">
                            <a href="{{ $users->url(1) }}">1</a>
                        </li>
                        @if($users->currentPage() > 4)
                            <li class="page-item disabled">
                                <span>...</span>
                            </li>
                        @endif
                    @endif

                    {{-- Pages around current --}}
                    @for($i = max(1, $users->currentPage() - 2); $i <= min($users->lastPage(), $users->currentPage() + 2); $i++)
                        @if ($i == $users->currentPage())
                            <li class="page-item active">
                                <a href="#">{{ $i }}</a>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $users->url($i) }}">{{ $i }}</a>
                            </li>
                        @endif
                    @endfor

                    {{-- Last Page if not in range --}}
                    @if($users->currentPage() < $users->lastPage() - 2)
                        @if($users->currentPage() < $users->lastPage() - 3)
                            <li class="page-item disabled">
                                <span>...</span>
                            </li>
                        @endif
                        <li class="page-item">
                            <a href="{{ $users->url($users->lastPage()) }}">{{ $users->lastPage() }}</a>
                        </li>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($users->hasMorePages())
                        <li class="page-item">
                            <a href="{{ $users->nextPageUrl() }}">&gt;</a>
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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 22px; height: 22px; flex-shrink: 0; margin-top: 2px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <div style="display: flex; flex-direction: column; gap: 4px;">
                <span style="font-weight: 700; font-size: 15px;">Đã xảy ra lỗi khi tạo tài khoản:</span>
                <ul style="padding-left: 20px; font-size: 13px; font-weight: 600; line-height: 1.5; text-align: left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
    
</div>

<!-- CREATE USER MODAL -->
<div id="create-user-modal" class="modal-backdrop">
    <div class="modal-content">
        <header class="modal-header">
            <h3 class="modal-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px; color: var(--primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                Thêm người dùng mới
            </h3>
            <button type="button" id="btn-close-modal" class="modal-close-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <form action="{{ route('admin.nguoi-dung.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                @if ($errors->any())
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

                <!-- Row 1: Full name -->
                <div class="modal-form-group">
                    <label class="modal-label" for="modal_HoTen">Họ và tên <span style="color: var(--danger);">*</span></label>
                    <input class="modal-input @error('HoTen') is-invalid @enderror" type="text" id="modal_HoTen" name="HoTen" value="{{ old('HoTen') }}" placeholder="Nhập đầy đủ họ và tên..." required>
                    @error('HoTen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 2: Email & Phone -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_Email">Email <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('Email') is-invalid @enderror" type="email" id="modal_Email" name="Email" value="{{ old('Email') }}" placeholder="example@gmail.com" required>
                        @error('Email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_SoDienThoai">Số điện thoại <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input @error('SoDienThoai') is-invalid @enderror" type="text" id="modal_SoDienThoai" name="SoDienThoai" value="{{ old('SoDienThoai') }}" placeholder="Ví dụ: 0912345678" required>
                        @error('SoDienThoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row: Date of Birth & Gender -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_NgaySinh">Ngày sinh</label>
                        <input class="modal-input @error('NgaySinh') is-invalid @enderror" type="date" id="modal_NgaySinh" name="NgaySinh" value="{{ old('NgaySinh') }}">
                        @error('NgaySinh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_GioiTinh">Giới tính</label>
                        <select class="modal-input form-select @error('GioiTinh') is-invalid @enderror" id="modal_GioiTinh" name="GioiTinh">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="1" {{ old('GioiTinh') == '1' ? 'selected' : '' }}>Nam</option>
                            <option value="2" {{ old('GioiTinh') == '2' ? 'selected' : '' }}>Nữ</option>
                            <option value="3" {{ old('GioiTinh') == '3' ? 'selected' : '' }}>Khác</option>
                        </select>
                        @error('GioiTinh')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Password -->
                <div class="modal-form-group">
                    <label class="modal-label" for="modal_MatKhau">Mật khẩu khởi tạo <span style="color: var(--danger);">*</span></label>
                    <input class="modal-input @error('MatKhau') is-invalid @enderror" type="password" id="modal_MatKhau" name="MatKhau" placeholder="Nhập mật khẩu (tối thiểu 6 ký tự)..." required>
                    @error('MatKhau')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 4: Role & Status -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_VaiTroId">Vai trò <span style="color: var(--danger);">*</span></label>
                        <select class="modal-input form-select @error('VaiTroId') is-invalid @enderror" id="modal_VaiTroId" name="VaiTroId" required>
                            <option value="">-- Chọn vai trò --</option>
                            @foreach ($roles as $r)
                                @if ($r->TenVaiTro !== 'Người dùng')
                                    <option value="{{ $r->Id }}" {{ old('VaiTroId') == $r->Id ? 'selected' : '' }}>{{ $r->TenVaiTro }}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('VaiTroId')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_TrangThai">Trạng thái <span style="color: var(--danger);">*</span></label>
                        <select class="modal-input form-select @error('TrangThai') is-invalid @enderror" id="modal_TrangThai" name="TrangThai" required>
                            <option value="1" selected>Hoạt động</option>
                        </select>
                        @error('TrangThai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <footer class="modal-footer">
                <button type="button" id="btn-cancel-modal" class="btn-modal-cancel">Hủy bỏ</button>
                <button type="submit" class="btn-modal-submit">Thêm tài khoản</button>
            </footer>
        </form>
    </div>
</div>

<!-- VIEW USER DETAILS MODAL -->
<div id="view-user-modal" class="modal-backdrop">
    <div class="modal-content" style="max-width: 600px;">
        <header class="modal-header">
            <h3 class="modal-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px; color: var(--primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.036 12.322a1.012 1.012 0 010-.755 2.066 2.066 0 011.852-1.15h16.224a2.066 2.066 0 011.853 1.15 1.012 1.012 0 010 .755 2.066 2.066 0 01-1.853 1.15H3.888a2.066 2.066 0 01-1.852-1.15z" />
                </svg>
                Chi tiết tài khoản người dùng
            </h3>
            <button type="button" id="btn-close-view-modal" class="modal-close-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <div class="modal-body" style="gap: 24px;">
            <!-- Profile Header Section -->
            <div style="display: flex; align-items: center; gap: 20px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color);">
                <div id="view_avatar" class="user-table-avatar" style="width: 68px; height: 68px; font-size: 28px; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
                    <span>U</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <h4 id="view_HoTen" style="font-family: var(--font-heading); font-size: 20px; font-weight: 700; color: var(--neutral-dark);">Nguyễn Văn A</h4>
                    <div style="display: flex; gap: 8px; align-items: center;">
                        <span id="view_VaiTroBadge" class="role-badge role-admin">Quản trị viên</span>
                        <span id="view_TrangThaiBadge" class="status-dot-wrapper status-active-dot">Hoạt động</span>
                    </div>
                </div>
            </div>

            <!-- Details Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--neutral-grey); letter-spacing: 0.5px;">Email</span>
                    <a id="view_Email" href="#" style="font-size: 14px; font-weight: 600; color: var(--primary); text-decoration: none; word-break: break-all;">example@gmail.com</a>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--neutral-grey); letter-spacing: 0.5px;">Số điện thoại</span>
                    <a id="view_SoDienThoai" href="#" style="font-size: 14px; font-weight: 600; color: #1e293b; text-decoration: none;">0912345678</a>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--neutral-grey); letter-spacing: 0.5px;">Ngày sinh</span>
                    <span id="view_NgaySinh" style="font-size: 14px; font-weight: 600; color: #1e293b;">—</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px;">
                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--neutral-grey); letter-spacing: 0.5px;">Giới tính</span>
                    <span id="view_GioiTinh" style="font-size: 14px; font-weight: 600; color: #1e293b;">—</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px; grid-column: span 2;">
                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--neutral-grey); letter-spacing: 0.5px;">Đơn vị công tác</span>
                    <span id="view_DonVi" style="font-size: 14px; font-weight: 600; color: #334155;">Khoa Tiếp nhận</span>
                </div>
                <div style="display: flex; flex-direction: column; gap: 4px; grid-column: span 2;">
                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--neutral-grey); letter-spacing: 0.5px;">Ngày tham gia hệ thống</span>
                    <span id="view_NgayTao" style="font-size: 14px; font-weight: 600; color: #334155;">25/05/2026 20:30</span>
                </div>
            </div>
        </div>

        <footer class="modal-footer">
            <button type="button" id="btn-close-view-modal-footer" class="btn-modal-cancel" style="border-color: var(--primary); color: var(--primary);">Đóng</button>
        </footer>
    </div>
</div>

<!-- EDIT USER MODAL -->
<div id="edit-user-modal" class="modal-backdrop">
    <div class="modal-content">
        <header class="modal-header">
            <h3 class="modal-title">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px; color: var(--warning);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
                Chỉnh sửa tài khoản người dùng
            </h3>
            <button type="button" id="btn-close-edit-modal" class="modal-close-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </header>

        <form id="edit-user-form" action="" method="POST">
            @csrf
            <div class="modal-body">
                <!-- Row 1: Full name -->
                <div class="modal-form-group">
                    <label class="modal-label" for="modal_edit_HoTen">Họ và tên <span style="color: var(--danger);">*</span></label>
                    <input class="modal-input" type="text" id="modal_edit_HoTen" name="HoTen" placeholder="Nhập đầy đủ họ và tên..." required>
                </div>

                <!-- Row 2: Email & Phone -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_edit_Email">Email <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input" type="email" id="modal_edit_Email" name="Email" placeholder="example@gmail.com" required>
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_edit_SoDienThoai">Số điện thoại <span style="color: var(--danger);">*</span></label>
                        <input class="modal-input" type="text" id="modal_edit_SoDienThoai" name="SoDienThoai" placeholder="Ví dụ: 0912345678" required>
                    </div>
                </div>

                <!-- Row: Date of Birth & Gender -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_edit_NgaySinh">Ngày sinh</label>
                        <input class="modal-input" type="date" id="modal_edit_NgaySinh" name="NgaySinh">
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_edit_GioiTinh">Giới tính</label>
                        <select class="modal-input form-select" id="modal_edit_GioiTinh" name="GioiTinh">
                            <option value="">-- Chọn giới tính --</option>
                            <option value="1">Nam</option>
                            <option value="2">Nữ</option>
                            <option value="3">Khác</option>
                        </select>
                    </div>
                </div>

                <!-- Row 3: Password (optional for Edit) -->
                <div class="modal-form-group">
                    <label class="modal-label" for="modal_edit_MatKhau">Mật khẩu mới <span style="font-weight: 500; font-size: 11px; color: var(--neutral-grey);">(Bỏ trống nếu giữ nguyên)</span></label>
                    <input class="modal-input" type="password" id="modal_edit_MatKhau" name="MatKhau" placeholder="Nhập mật khẩu mới (tối thiểu 6 ký tự)...">
                </div>

                <!-- Row 4: Role & Status -->
                <div class="form-row">
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_edit_VaiTroId">Vai trò <span style="color: var(--danger);">*</span></label>
                        <select class="modal-input form-select" id="modal_edit_VaiTroId" name="VaiTroId" required>
                            <option value="">-- Chọn vai trò --</option>
                            @foreach ($roles as $r)
                                @if ($r->TenVaiTro !== 'Người dùng')
                                    <option value="{{ $r->Id }}">{{ $r->TenVaiTro }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-form-group">
                        <label class="modal-label" for="modal_edit_TrangThai">Trạng thái <span style="color: var(--danger);">*</span></label>
                        <select class="modal-input form-select" id="modal_edit_TrangThai" name="TrangThai" required>
                            <option value="1">Hoạt động</option>
                            <option value="2">Đã đóng băng</option>
                        </select>
                    </div>
                </div>
            </div>

            <footer class="modal-footer">
                <button type="button" id="btn-cancel-edit-modal" class="btn-modal-cancel">Hủy bỏ</button>
                <button type="submit" class="btn-modal-submit" style="background-color: var(--warning); color: #fff;">Lưu thay đổi</button>
            </footer>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- CREATE USER MODAL ---
        const modal = document.getElementById('create-user-modal');
        const openBtn = document.getElementById('btn-open-create-modal');
        const closeBtn = document.getElementById('btn-close-modal');
        const cancelBtn = document.getElementById('btn-cancel-modal');

        function showModal() {
            modal.classList.add('show');
        }

        function hideModal() {
            modal.classList.remove('show');
        }

        if (openBtn) openBtn.addEventListener('click', showModal);
        if (closeBtn) closeBtn.addEventListener('click', hideModal);
        if (cancelBtn) cancelBtn.addEventListener('click', hideModal);

        // Close when clicking outside content
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                hideModal();
            }
        });

        // Auto open modal on load if there are validation errors
        @if ($errors->any())
            showModal();
        @endif

        // --- VIEW USER DETAILS MODAL ---
        const viewModal = document.getElementById('view-user-modal');
        const viewCloseBtn = document.getElementById('btn-close-view-modal');
        const viewCloseFooterBtn = document.getElementById('btn-close-view-modal-footer');

        function hideViewModal() {
            viewModal.classList.remove('show');
        }

        if (viewCloseBtn) viewCloseBtn.addEventListener('click', hideViewModal);
        if (viewCloseFooterBtn) viewCloseFooterBtn.addEventListener('click', hideViewModal);

        viewModal.addEventListener('click', function (e) {
            if (e.target === viewModal) {
                hideViewModal();
            }
        });

        document.querySelectorAll('.btn-view').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const data = this.dataset;
                
                try {
                    // Populate profile header
                    const avatarDiv = document.getElementById('view_avatar');
                    if (avatarDiv) {
                        avatarDiv.className = `user-table-avatar color-${Math.abs(data.id % 5)}`;
                        const avatarSpan = avatarDiv.querySelector('span');
                        if (avatarSpan) {
                            avatarSpan.textContent = data.hoten ? data.hoten.charAt(0).toUpperCase() : 'U';
                        }
                    }
                    
                    const hoTenEl = document.getElementById('view_HoTen');
                    if (hoTenEl) hoTenEl.textContent = data.hoten || '—';
                    
                    // Role Badge
                    const roleBadge = document.getElementById('view_VaiTroBadge');
                    if (roleBadge) {
                        roleBadge.textContent = data.vaitroten || '—';
                        roleBadge.className = 'role-badge';
                        if (data.vaitroten === 'Quản trị viên') {
                            roleBadge.classList.add('role-admin');
                        } else if (data.vaitroten === 'Nhân viên') {
                            roleBadge.classList.add('role-employee');
                        } else if (data.vaitroten === 'Đơn vị tổ chức') {
                            roleBadge.classList.add('role-org');
                        } else if (data.vaitroten === 'Người tham gia') {
                            roleBadge.classList.add('role-participant');
                        } else {
                            roleBadge.classList.add('role-user');
                        }
                    }

                    // Status Badge
                    const statusBadge = document.getElementById('view_TrangThaiBadge');
                    if (statusBadge) {
                        if (data.trangthai == 1) {
                            statusBadge.textContent = 'Hoạt động';
                            statusBadge.className = 'status-dot-wrapper status-active-dot';
                        } else {
                            statusBadge.textContent = 'Đã đóng băng';
                            statusBadge.className = 'status-dot-wrapper status-frozen-dot';
                        }
                    }

                    // Details Fields
                    const emailLink = document.getElementById('view_Email');
                    if (emailLink) {
                        emailLink.textContent = data.email || '—';
                        emailLink.href = data.email ? `mailto:${data.email}` : '#';
                    }

                    const phoneLink = document.getElementById('view_SoDienThoai');
                    if (phoneLink) {
                        phoneLink.textContent = data.sodienthoai || '—';
                        phoneLink.href = data.sodienthoai ? `tel:${data.sodienthoai}` : '#';
                    }

                    const donViEl = document.getElementById('view_DonVi');
                    if (donViEl) donViEl.textContent = data.donvi || '—';
                    
                    const ngayTaoEl = document.getElementById('view_NgayTao');
                    if (ngayTaoEl) ngayTaoEl.textContent = data.created || '—';

                    // Date of Birth & Gender
                    const ngaySinhEl = document.getElementById('view_NgaySinh');
                    if (ngaySinhEl) ngaySinhEl.textContent = data.ngaysinh || '—';
                    
                    const gioiTinhEl = document.getElementById('view_GioiTinh');
                    if (gioiTinhEl) {
                        const genders = {1: 'Nam', 2: 'Nữ', 3: 'Khác'};
                        gioiTinhEl.textContent = genders[data.gioitinh] || '—';
                    }
                } catch (err) {
                    console.error("Error populating view modal:", err);
                }

                viewModal.classList.add('show');
            });
        });

        // --- EDIT USER MODAL ---
        const editModal = document.getElementById('edit-user-modal');
        const editCloseBtn = document.getElementById('btn-close-edit-modal');
        const editCancelBtn = document.getElementById('btn-cancel-edit-modal');
        const editForm = document.getElementById('edit-user-form');

        function hideEditModal() {
            editModal.classList.remove('show');
        }

        if (editCloseBtn) editCloseBtn.addEventListener('click', hideEditModal);
        if (editCancelBtn) editCancelBtn.addEventListener('click', hideEditModal);

        editModal.addEventListener('click', function (e) {
            if (e.target === editModal) {
                hideEditModal();
            }
        });

        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const data = this.dataset;
                
                try {
                    // Set form action action
                    const baseRoute = "{{ route('admin.nguoi-dung.update', ['id' => '__ID__']) }}";
                    editForm.action = baseRoute.replace('__ID__', data.id);
                    
                    // Populate fields
                    if (document.getElementById('modal_edit_HoTen')) document.getElementById('modal_edit_HoTen').value = data.hoten || '';
                    if (document.getElementById('modal_edit_Email')) document.getElementById('modal_edit_Email').value = data.email || '';
                    if (document.getElementById('modal_edit_SoDienThoai')) document.getElementById('modal_edit_SoDienThoai').value = data.sodienthoai || '';
                    if (document.getElementById('modal_edit_VaiTroId')) document.getElementById('modal_edit_VaiTroId').value = data.vaitroid || '';
                    if (document.getElementById('modal_edit_TrangThai')) document.getElementById('modal_edit_TrangThai').value = data.trangthai || '1';
                    if (document.getElementById('modal_edit_NgaySinh')) document.getElementById('modal_edit_NgaySinh').value = data.ngaysinh || '';
                    if (document.getElementById('modal_edit_GioiTinh')) document.getElementById('modal_edit_GioiTinh').value = data.gioitinh || '';
                    
                    // Reset password field
                    if (document.getElementById('modal_edit_MatKhau')) document.getElementById('modal_edit_MatKhau').value = '';
                } catch (err) {
                    console.error("Error populating edit modal:", err);
                }

                editModal.classList.add('show');
            });
        });
    });
</script>
@endpush
