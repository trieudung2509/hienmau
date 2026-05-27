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

@section('title', 'Quản lý hồ sơ | Cổng thông tin Hiến Máu Tình Nguyện')
@section('navbar-title', 'Quản lý hồ sơ chương trình')
@section('navbar-subtitle', 'Tra cứu, lọc danh sách hồ sơ sức khỏe và đăng ký hiến máu trên toàn hệ thống.')

@push('styles')
    <style>
        /* DASHBOARD EXTRA STYLES */
        .dashboard-body {
            padding: 32px;
            overflow-y: auto;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 24px;
            max-width: 1600px;
            width: 100%;
            margin: 0 auto;
        }

        .greeting-section {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-bottom: 8px;
        }

        .greeting-title {
            font-family: var(--font-heading);
            font-size: 24px;
            font-weight: 800;
            color: var(--neutral-dark);
        }

        .greeting-subtitle {
            font-size: 14px;
            color: var(--neutral-grey);
            font-weight: 600;
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 8px;
        }

        .stat-card {
            background-color: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon.blue { background-color: rgba(37, 99, 235, 0.08); color: var(--primary); }
        .stat-icon.green { background-color: rgba(16, 185, 129, 0.08); color: var(--success); }
        .stat-icon.orange { background-color: rgba(245, 158, 11, 0.08); color: var(--warning); }
        .stat-icon.red { background-color: rgba(239, 68, 68, 0.08); color: var(--danger); }

        /* Cancelled registration row highlight */
        .admin-table tbody tr.row-cancelled {
            background-color: rgba(254, 242, 242, 0.6);
        }
        .admin-table tbody tr.row-cancelled:hover {
            background-color: rgba(254, 226, 226, 0.8);
        }

        .stat-icon svg {
            width: 24px;
            height: 24px;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-title {
            font-size: 13px;
            color: var(--neutral-grey);
            font-weight: 600;
        }

        .stat-value {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 800;
            color: var(--neutral-dark);
            margin: 2px 0;
            display: flex;
            align-items: baseline;
            gap: 4px;
        }

        .stat-label {
            font-size: 11px;
            color: var(--neutral-grey);
            font-weight: 500;
        }

        /* FILTER CARD STYLE */
        .filter-card {
            background-color: #fff;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            margin-bottom: 8px;
        }

        .filter-title {
            font-family: var(--font-heading);
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--neutral-dark);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-title svg {
            width: 18px;
            height: 18px;
            color: var(--primary);
        }

        .filter-grid-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 16px;
            align-items: end;
        }

        @media (max-width: 768px) {
            .filter-grid-form {
                grid-template-columns: 1fr;
            }
        }

        .form-group-filter {
            display: flex;
            flex-direction: column;
            gap: 6px;
            position: relative;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: var(--neutral-grey);
        }

        .filter-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .filter-input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: var(--neutral-grey);
            pointer-events: none;
        }

        .filter-input {
            width: 100%;
            height: 44px;
            padding: 8px 16px 8px 44px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-family: var(--font-main);
            font-size: 14px;
            font-weight: 500;
            color: var(--neutral-dark);
            background-color: var(--neutral-light);
            outline: none;
            transition: all 0.2s ease;
        }

        .filter-input:focus {
            border-color: var(--primary);
            background-color: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .filter-btn-group {
            display: flex;
            gap: 10px;
            height: 44px;
        }

        .btn-filter-submit {
            background-color: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: var(--font-main);
            font-size: 14px;
            font-weight: 600;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .btn-filter-submit:hover {
            background-color: var(--primary-hover);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
        }

        .btn-filter-reset {
            background-color: #fff;
            color: var(--neutral-grey);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-family: var(--font-main);
            font-size: 14px;
            font-weight: 600;
            padding: 0 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-filter-reset:hover {
            background-color: var(--neutral-light);
            color: var(--neutral-dark);
            border-color: var(--neutral-grey);
        }

        /* DATA TABLE CONTAINER */
        .bottom-section {
            background-color: #fff;
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .bottom-header {
            padding: 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .bottom-title {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 700;
            color: var(--neutral-dark);
        }

        /* TABLE DESIGN */
        .table-responsive {
            overflow-x: auto;
            width: 100%;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 14px;
        }

        .admin-table th {
            background-color: var(--neutral-light);
            color: var(--neutral-grey);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.8px;
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
        }

        .admin-table td {
            padding: 16px 24px;
            border-bottom: 1px solid var(--border-color);
            color: var(--neutral-dark);
            vertical-align: middle;
        }

        .admin-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .admin-table tbody tr:hover {
            background-color: rgba(248, 250, 252, 0.8);
        }

        .stt-cell {
            font-weight: 700;
            color: var(--neutral-grey);
        }

        /* User Profile Badge Cell */
        .user-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-table-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
            color: #fff;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .user-table-avatar.color-0 { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
        .user-table-avatar.color-1 { background: linear-gradient(135deg, #10b981 0%, #047857 100%); }
        .user-table-avatar.color-2 { background: linear-gradient(135deg, #f59e0b 0%, #b45309 100%); }
        .user-table-avatar.color-3 { background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); }
        .user-table-avatar.color-4 { background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%); }

        .user-info-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .user-name-text {
            font-weight: 700;
            color: var(--neutral-dark);
            font-size: 14px;
        }

        .user-dob-text {
            font-size: 12px;
            color: var(--neutral-grey);
            font-weight: 500;
        }

        .phone-text {
            font-weight: 600;
            color: var(--neutral-dark);
        }

        .email-text {
            font-size: 13px;
            color: var(--neutral-grey);
        }

        /* BADGES */
        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px;
            line-height: 1;
        }

        .badge-success {
            background-color: var(--success-light);
            color: var(--success);
        }

        .badge-danger {
            background-color: var(--danger-light);
            color: var(--danger);
        }

        .badge-warning {
            background-color: var(--warning-light);
            color: var(--warning);
        }

        .badge-primary {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .volume-badge {
            background-color: #f1f5f9;
            color: var(--neutral-dark);
            border: 1px solid var(--border-color);
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 8px;
            display: inline-block;
        }

        /* ACTIONS */
        .actions-cell {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .btn-table-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--neutral-grey);
        }

        .btn-table-action:hover {
            background-color: var(--neutral-light);
            border-color: var(--neutral-grey);
            color: var(--neutral-dark);
        }

        .btn-table-action.btn-view:hover {
            background-color: var(--primary-light);
            border-color: rgba(37, 99, 235, 0.3);
            color: var(--primary);
        }

        .btn-table-action svg {
            width: 16px;
            height: 16px;
        }

        /* FOOTER & PAGINATION */
        .table-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            background-color: var(--neutral-light);
        }

        .footer-info {
            font-size: 13px;
            color: var(--neutral-grey);
            font-weight: 500;
        }

        .pagination-row {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .pagination-list {
            list-style: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .page-item a, .page-item span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid var(--border-color);
            background-color: #fff;
            color: var(--neutral-grey);
        }

        .page-item a:hover {
            background-color: var(--neutral-light);
            color: var(--neutral-dark);
            border-color: var(--neutral-grey);
        }

        .page-item.active span {
            background-color: var(--primary);
            color: #fff;
            border-color: var(--primary);
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .page-item.disabled span {
            background-color: #f8fafc;
            color: #cbd5e1;
            cursor: not-allowed;
            border-color: #f1f5f9;
        }

        /* PREMIUM DYNAMIC MODAL SHEET */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(8px);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 20px;
        }

        .modal-backdrop.open {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 680px;
            box-shadow: var(--shadow-lg), 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            transform: scale(0.9) translateY(20px);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .modal-backdrop.open .modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
        }

        .modal-header-profile {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .modal-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
            text-transform: uppercase;
        }

        .modal-name-wrap {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .modal-name {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 700;
            color: #ffffff;
        }

        .modal-sub {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
            margin-top: 4px;
        }

        .btn-modal-close {
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            padding: 6px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .btn-modal-close:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
        }

        .btn-modal-close svg {
            width: 20px;
            height: 20px;
        }

        .modal-body {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            overflow-y: auto;
            max-height: calc(85vh - 80px);
        }

        .modal-section-title {
            font-family: var(--font-heading);
            font-size: 14px;
            font-weight: 700;
            color: var(--neutral-dark);
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 8px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .detail-item-label {
            font-size: 12px;
            color: var(--neutral-grey);
            font-weight: 600;
        }

        .detail-item-value {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--neutral-dark);
        }

        .health-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        @media (max-width: 576px) {
            .health-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        .health-card {
            background-color: var(--neutral-light);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 6px;
        }

        .health-card-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .health-card-icon.red { background-color: rgba(239, 68, 68, 0.08); color: var(--danger); }
        .health-card-icon.purple { background-color: rgba(139, 92, 246, 0.08); color: #8b5cf6; }
        .health-card-icon.yellow { background-color: rgba(245, 158, 11, 0.08); color: var(--warning); }
        .health-card-icon.emerald { background-color: rgba(16, 185, 129, 0.08); color: var(--success); }
        .health-card-icon.blue { background-color: rgba(59, 130, 246, 0.08); color: var(--info); }

        .health-card-icon svg {
            width: 18px;
            height: 18px;
        }

        .health-card-title {
            font-size: 11px;
            color: var(--neutral-grey);
            font-weight: 600;
            text-transform: uppercase;
        }

        .health-card-value {
            font-size: 14px;
            font-weight: 800;
            color: var(--neutral-dark);
        }

        .note-card {
            background-color: var(--warning-light);
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .note-card-title {
            font-size: 12px;
            font-weight: 700;
            color: #b45309;
            display: flex;
            align-items: center;
            gap: 6px;
            text-transform: uppercase;
        }

        .note-card-title svg {
            width: 16px;
            height: 16px;
            color: var(--warning);
        }

        .note-card-value {
            font-size: 13.5px;
            color: #78350f;
            font-weight: 550;
            line-height: 1.4;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            background-color: var(--neutral-light);
            display: flex;
            justify-content: flex-end;
        }

        .btn-modal-close-action {
            height: 38px;
            background-color: #fff;
            color: var(--neutral-dark);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-family: var(--font-main);
            font-size: 13px;
            font-weight: 600;
            padding: 0 16px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-modal-close-action:hover {
            background-color: var(--neutral-light);
            border-color: var(--neutral-grey);
        }
    </style>
@endpush

@section('content')
<div class="dashboard-body">
    <!-- Title greeting block -->
    <div class="greeting-section">
        <h2 class="greeting-title">Hồ sơ sức khỏe người hiến máu</h2>
        <span class="greeting-subtitle">Tra cứu, xem thông tin kiểm tra sức khỏe và lịch sử kết quả hiến máu toàn hệ thống</span>
    </div>

    <!-- STATS METRICS GRID -->
    <section class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title">Đăng ký tham gia</span>
                <span class="stat-value">{{ $metrics['tong_ho_so'] }}</span>
                <span class="stat-label">{{ request('chuong_trinh_id') ? 'Chương trình này' : 'Toàn hệ thống' }}</span>
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
                <span class="stat-label">{{ request('chuong_trinh_id') ? 'Chương trình này' : 'Đạt điều kiện' }}</span>
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
                <span class="stat-label">{{ request('chuong_trinh_id') ? 'Chương trình này' : 'Thể tích thu nhận' }}</span>
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
                <span class="stat-label">{{ request('chuong_trinh_id') ? 'Chương trình này' : 'Lượt hủy toàn hệ thống' }}</span>
            </div>
        </div>
    </section>

    <!-- FILTER BAR -->
    <section class="filter-card">
        <h3 class="filter-title">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
            </svg>
            Bộ lọc tìm kiếm nâng cao
        </h3>
        
        <form action="{{ route('admin.ho-so.index') }}" method="GET" class="filter-grid-form" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)) auto; gap: 16px; align-items: end;">
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
                    <select id="chuong_trinh_id" name="chuong_trinh_id" class="filter-input" style="padding-left: 14px; background-color: var(--neutral-light); cursor: pointer;">
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
                <a href="{{ route('admin.ho-so.index') }}" class="btn-filter-reset">
                    Làm mới
                </a>
            </div>
        </form>
    </section>

    <!-- LIST AND TABLE SECTION -->
    <section class="bottom-section">
        <header class="bottom-header">
            <h3 class="bottom-title">Kết quả tìm kiếm ({{ $records->total() }})</h3>
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
                            <td class="stt-cell">{{ $records->firstItem() + $index }}</td>
                                    
                            <td>
                                <div class="user-cell">
                                    <div class="user-table-avatar color-{{ ($records->firstItem() + $index) % 5 }}">
                                        <span>{{ mb_substr($r->HoTen, 0, 1) }}</span>
                                    </div>
                                    <div class="user-info-text">
                                        <span class="user-name-text">{{ $r->HoTen }}</span>
                                        <span class="user-dob-text">
                                            {{ $r->GioiTinh == 1 ? 'Nam' : ($r->GioiTinh == 2 ? 'Nữ' : 'Khác') }} • 
                                            {{ $r->NgaySinh ? \Carbon\Carbon::parse($r->NgaySinh)->timezone(config('app.timezone'))->format('d/m/Y') : '—' }}
                                        </span>
                                        <span class="email-text">{{ $r->SoDienThoai }} • {{ $r->Email }}</span>
                                    </div>
                                </div>
                            </td>
                                    
                            <td style="max-width: 300px; font-weight: 500;">
                                {{ $r->TenChuongTrinh }}
                            </td>
                                    
                            <td>
                                @if ($r->LuongMau)
                                    <span class="volume-badge">{{ $r->LuongMau }} ml</span>
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

                            {{-- Registration status column --}}
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
                Hiển thị {{ $records->firstItem() ?? 0 }} - {{ $records->lastItem() ?? 0 }} trong tổng số {{ $records->total() }} hồ sơ
            </span>
            
            <div class="pagination-row">
                <ul class="pagination-list">
                    {{-- Previous Page Link --}}
                    @if ($records->onFirstPage())
                        <li class="page-item disabled">
                            <span>&lt;</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a href="{{ $records->previousPageUrl() }}">&lt;</a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($records->getUrlRange(1, $records->lastPage()) as $page => $url)
                        @if ($page == $records->currentPage())
                            <li class="page-item active">
                                <span>{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($records->hasMorePages())
                        <li class="page-item">
                            <a href="{{ $records->nextPageUrl() }}">&gt;</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span>&gt;</span>
                        </li>
                    @endif
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
                        <span class="modal-name" id="mName">Chưa cập nhật</span>
                        <span class="modal-sub" id="mMeta">Chưa rõ</span>
                    </div>
                </div>
                <button class="btn-modal-close" onclick="closeModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <div class="modal-body">
                <!-- Cancelled registration alert banner -->
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
                            <span class="detail-item-value" id="mPhone">Chưa rõ</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Thư điện tử (Email)</span>
                            <span class="detail-item-value" id="mEmail">Chưa rõ</span>
                        </div>
                        <div class="detail-item" style="grid-column: span 2;">
                            <span class="detail-item-label">Chương trình tham gia</span>
                            <span class="detail-item-value" id="mProgram" style="color: var(--primary);">Chưa rõ</span>
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
                            <span class="health-card-value" id="mHuyetAp">Chưa rõ</span>
                        </div>

                        <!-- Heart Rate -->
                        <div class="health-card">
                            <div class="health-card-icon purple">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <span class="health-card-title">Nhịp tim</span>
                            <span class="health-card-value" id="mNhipTim">Chưa rõ</span>
                        </div>

                        <!-- Weight -->
                        <div class="health-card">
                            <div class="health-card-icon yellow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                                </svg>
                            </div>
                            <span class="health-card-title">Cân nặng</span>
                            <span class="health-card-value" id="mCanNang">Chưa rõ</span>
                        </div>

                        <!-- Temperature -->
                        <div class="health-card">
                            <div class="health-card-icon emerald">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <span class="health-card-title">Nhiệt độ</span>
                            <span class="health-card-value" id="mNhietDo">Chưa rõ</span>
                        </div>

                        <!-- Hemoglobin -->
                        <div class="health-card">
                            <div class="health-card-icon blue">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                            </div>
                            <span class="health-card-title">Hemoglobin</span>
                            <span class="health-card-value" id="mHemoglobin">Chưa rõ</span>
                        </div>

                        <!-- Blood Group -->
                        <div class="health-card">
                            <div class="health-card-icon red" style="background-color: rgba(239, 68, 68, 0.08);">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <span class="health-card-title">Nhóm máu</span>
                            <span class="health-card-value" id="mNhomMau" style="color: var(--danger); font-weight: 800;">Chưa rõ</span>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: Donation Outcome & Examiners -->
                <div>
                    <h4 class="modal-section-title">Kết quả & Thông tin khám</h4>
                    <div class="details-grid" style="margin-bottom: 12px;">
                        <div class="detail-item">
                            <span class="detail-item-label">Bác sĩ / Người khám</span>
                            <span class="detail-item-value" id="mNguoiKham">Chưa rõ</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-item-label">Kết quả hiến máu</span>
                            <span class="detail-item-value" id="mKetQua">Chưa rõ</span>
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
</div>
@endsection

@push('scripts')
    <!-- MODAL LOGIC JAVASCRIPT -->
    <script>
        const modal = document.getElementById('healthModal');

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

        function openModal(record) {
            // Show/hide cancelled registration banner
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
            
            let genderText = 'Chưa rõ';
            if (record.GioiTinh == 1) genderText = 'Nam';
            else if (record.GioiTinh == 2) genderText = 'Nữ';
            else if (record.GioiTinh == 3) genderText = 'Khác';

            document.getElementById('mMeta').innerText = `${genderText} • ${dobFormatted}`;

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

        function closeModal() {
            modal.classList.remove('open');
        }

        // Close when clicking outside modal body content
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
@endpush
