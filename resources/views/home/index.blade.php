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

@extends('admin.layouts.dashboard', $theme)

@section('title', 'Trang chủ')
@section('navbar-title', 'Trang chủ')

@push('styles')
<style>
    .center-column {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 24px;
        min-width: 0;
        padding: 28px 32px;
    }

    .right-column {
        width: 320px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* ROSE BANNER CARD */
    .banner-card {
        background: linear-gradient(135deg, #fff5f5 0%, #fff0f0 100%);
        border-radius: 20px;
        padding: 32px;
        border: 1px solid #fee2e2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .banner-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
        z-index: 2;
        max-width: 60%;
    }

    .banner-title {
        font-family: var(--font-heading);
        font-size: 32px;
        font-weight: 800;
        line-height: 1.2;
    }

    .banner-title span {
        color: var(--primary);
    }

    .banner-subtitle {
        font-size: 15px;
        color: #475569;
        font-weight: 600;
    }

    .banner-illustration {
        width: 180px;
        height: 180px;
        z-index: 1;
        flex-shrink: 0;
    }

    /* SEARCH CARD */
    .search-card {
        background-color: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .search-title {
        font-family: var(--font-heading);
        font-size: 16px;
        font-weight: 700;
        color: var(--primary);
    }

    .search-row {
        display: grid;
        grid-template-columns: 2fr 1.2fr 1.2fr auto;
        gap: 12px;
        align-items: center;
    }

    .form-group {
        position: relative;
        display: flex;
        align-items: center;
    }

    .form-input {
        width: 100%;
        height: 42px;
        background-color: var(--neutral-light);
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 8px 12px 8px 40px;
        font-size: 14px;
        font-weight: 500;
        outline: none;
        transition: all 0.2s ease;
        font-family: var(--font-main);
    }

    .form-input:focus {
        background-color: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 14px;
        padding-right: 32px;
    }

    .form-icon {
        position: absolute;
        left: 12px;
        top: 12px;
        width: 18px;
        height: 18px;
        color: var(--neutral-grey);
        pointer-events: none;
    }

    .btn-submit-search {
        background-color: var(--primary);
        color: #fff;
        height: 42px;
        padding: 0 24px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
        font-family: var(--font-main);
    }

    .btn-submit-search:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
    }

    /* METRICS SECTION */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    .metric-card {
        background-color: #fff;
        border-radius: 16px;
        padding: 16px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: var(--shadow-sm);
        transition: all 0.3s ease;
    }

    .metric-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .metric-icon-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .metric-icon-circle.red {
        background-color: rgba(239, 68, 68, 0.08);
        color: var(--primary);
    }

    .metric-icon-circle.orange {
        background-color: rgba(245, 158, 11, 0.08);
        color: var(--warning);
    }

    .metric-icon-circle.green {
        background-color: rgba(16, 185, 129, 0.08);
        color: var(--success);
    }

    .metric-icon-circle svg {
        width: 20px;
        height: 20px;
    }

    .metric-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .metric-title {
        font-size: 11px;
        font-weight: 600;
        color: var(--neutral-grey);
        text-transform: uppercase;
        letter-spacing: 0.2px;
    }

    .metric-value {
        font-family: var(--font-heading);
        font-size: 16px;
        font-weight: 700;
        color: var(--neutral-dark);
    }

    .metric-value span {
        color: var(--primary);
    }

    /* UPCOMING PROGRAMS SECTION */
    .programs-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 8px;
    }

    .programs-title {
        font-family: var(--font-heading);
        font-size: 20px;
        font-weight: 700;
        color: var(--neutral-dark);
    }

    .btn-view-all {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-view-all:hover {
        color: var(--primary-hover);
    }

    .programs-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .program-row {
        background-color: #fff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: all 0.3s ease;
    }

    .program-row:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .program-row-thumbnail {
        width: 80px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 10px;
        color: #fff;
        font-family: var(--font-heading);
        flex-shrink: 0;
        box-shadow: var(--shadow-sm);
    }

    .program-row-thumbnail .logo {
        font-size: 10px;
        font-weight: 700;
        background-color: rgba(255,255,255,0.25);
        padding: 2px 4px;
        border-radius: 4px;
        align-self: flex-start;
        text-transform: uppercase;
    }

    .program-row-thumbnail .heart {
        font-size: 20px;
        align-self: center;
    }

    .program-row-thumbnail .label {
        font-size: 8px;
        font-weight: 700;
        text-transform: uppercase;
        text-align: center;
    }

    .program-row-content {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
    }

    .program-row-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--neutral-dark);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .program-row-name:hover {
        color: var(--primary);
    }

    .program-row-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
        font-size: 13px;
        color: var(--neutral-grey);
        font-weight: 500;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .meta-item svg {
        width: 16px;
        height: 16px;
        color: var(--neutral-grey);
        flex-shrink: 0;
    }

    .program-row-badges {
        display: flex;
        gap: 8px;
        margin-top: 4px;
    }

    .row-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 700;
        border-radius: 6px;
    }

    .badge-spots {
        background-color: var(--success-light);
        color: var(--success);
    }

    .badge-status-approved {
        background-color: var(--info-light);
        color: var(--info);
    }

    .badge-status-pending {
        background-color: var(--warning-light);
        color: var(--warning);
    }

    .program-row-actions {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .btn-register-program {
        background-color: var(--primary);
        color: #fff;
        border: none;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(239, 68, 68, 0.15);
        font-family: var(--font-main);
        white-space: nowrap;
    }

    .btn-register-program:hover {
        background-color: var(--primary-hover);
    }

    .link-details {
        font-size: 12px;
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .link-details:hover {
        color: var(--primary-hover);
        text-decoration: underline;
    }

    /* RIGHT SIDEBAR PANELS */
    .right-panel-card {
        background-color: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .panel-heading {
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        color: var(--neutral-dark);
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .panel-heading svg {
        width: 18px;
        height: 18px;
        color: var(--neutral-grey);
    }

    /* PROFILE PANEL */
    .profile-panel-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        text-align: center;
    }

    .profile-panel-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #3b82f6;
        overflow: hidden;
        border: 3px solid #fff;
        box-shadow: var(--shadow-md);
    }

    .profile-panel-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-panel-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--neutral-dark);
    }

    .profile-panel-contact {
        display: flex;
        flex-direction: column;
        gap: 6px;
        width: 100%;
        font-size: 13px;
        font-weight: 500;
        color: var(--neutral-grey);
        padding: 12px 0;
        border-top: 1px dashed #e2e8f0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .contact-item svg {
        width: 16px;
        height: 16px;
        color: var(--neutral-grey);
    }

    .link-profile {
        font-size: 13px;
        font-weight: 700;
        color: var(--primary);
        text-decoration: none;
        align-self: center;
        transition: all 0.2s ease;
    }

    .link-profile:hover {
        color: var(--primary-hover);
    }

    /* REMINDER PANEL */
    .reminder-box {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        text-align: center;
        padding: 4px 0;
    }

    .reminder-desc {
        font-size: 13px;
        font-weight: 600;
        color: var(--neutral-grey);
    }

    .reminder-countdown {
        font-family: var(--font-heading);
        font-size: 28px;
        font-weight: 800;
        color: var(--primary);
    }

    .reminder-date {
        font-size: 12px;
        color: var(--neutral-grey);
        font-weight: 500;
    }

    .standard-card-blue {
        width: 100%;
        background-color: var(--info-light);
        border-radius: 10px;
        padding: 12px;
        font-size: 12px;
        font-weight: 600;
        color: var(--info);
        display: flex;
        flex-direction: column;
        gap: 4px;
        text-align: left;
    }

    /* QUICK GUIDES PANEL */
    .guide-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        list-style: none;
    }

    .guide-item a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 0;
        text-decoration: none;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .guide-item a:hover {
        color: var(--primary);
    }

    .guide-icon-wrapper {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .guide-icon-wrapper.orange { background-color: rgba(245, 158, 11, 0.08); color: var(--warning); }
    .guide-icon-wrapper.blue { background-color: rgba(37, 99, 235, 0.08); color: var(--info); }
    .guide-icon-wrapper.teal { background-color: rgba(20, 184, 166, 0.08); color: #14b8a6; }
    .guide-icon-wrapper.purple { background-color: rgba(139, 92, 246, 0.08); color: #8b5cf6; }

    .guide-icon-wrapper svg {
        width: 16px;
        height: 16px;
    }

    .chevron-right {
        width: 14px;
        height: 14px;
        color: #cbd5e1;
        margin-left: auto;
    }

    /* HELP CARD */
    .help-card {
        background: linear-gradient(135deg, #fff5f5 0%, #fff0f0 100%);
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #fee2e2;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .help-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background-color: var(--primary-light);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .help-icon svg {
        width: 22px;
        height: 22px;
    }

    .help-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--neutral-dark);
    }

    .help-sub {
        font-size: 12px;
        color: var(--neutral-grey);
        font-weight: 500;
        margin-top: -8px;
    }

    .btn-help-contact {
        width: 100%;
        height: 38px;
        background-color: #fff;
        color: var(--primary);
        font-size: 13px;
        font-weight: 700;
        border: 1px solid rgba(239, 68, 68, 0.2);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn-help-contact:hover {
        background-color: var(--primary-light);
        border-color: var(--primary);
    }
</style>
@endpush

@section('content')
    <!-- CENTER VIEW COLUMN -->
    <main class="center-column">
        
        <!-- ROSE GRADIENT BANNER CARD -->
        <section class="banner-card">
            <div class="banner-content">
                <h2 class="banner-title">Hiến máu hôm nay<br><span>Sức khỏe ngày mai</span></h2>
                <p class="banner-subtitle">Mỗi giọt máu cho đi – Một cuộc đời ở lại</p>
            </div>
            <div class="banner-illustration">
                <!-- High-fidelity inline SVG hand and blood drop illustration -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" fill="none" style="width: 100%; height: 100%;">
                    <!-- Soft circular shadow background -->
                    <circle cx="100" cy="115" r="50" fill="#fee2e2" opacity="0.6"/>
                    <!-- Small background floating hearts -->
                    <path d="M45 70c-3-3-8-3-11 0-3 3-3 8 0 11l11 11 11-11c3-3 3-8 0-11-3-3-8-3-11 0z" fill="#fca5a5" opacity="0.6"/>
                    <path d="M155 80c-2.5-2.5-6.5-2.5-9 0s-2.5 6.5 0 9l9 9 9-9c2.5-2.5 2.5-6.5 0-9s-6.5-2.5-9 0z" fill="#fca5a5" opacity="0.8"/>
                    <path d="M110 50c-2-2-5-2-7 0s-2 5 0 7l7 7 7-7c2-2 2-5 0-7s-5-2-7 0z" fill="#fca5a5" opacity="0.7"/>
                    
                    <!-- Caring hand vector -->
                    <path d="M60 145c20-2 40-5 50-15s15-25 35-15 15 25 5 35-40 20-65 20-25-10-25-25z" fill="#ffedd5" stroke="#fed7aa" stroke-width="2" stroke-linejoin="round"/>
                    <path d="M70 142c15-4 28-8 38-3s12 12 8 20" fill="none" stroke="#fed7aa" stroke-width="2" stroke-linecap="round"/>
                    
                    <!-- Floating giant blood drop with heart inside -->
                    <path d="M100 40c0 0 35 35 35 60a35 35 0 11-70 0c0-25 35-60 35-60z" fill="url(#bloodGrad)" filter="url(#dropShadow)"/>
                    <!-- Glowing inner highlight of the drop -->
                    <path d="M85 85c-5 10-5 25 5 30 10 5 15-5 10-15s-10-15-15-15z" fill="#fff" opacity="0.2"/>
                    
                    <!-- Pure white heart inside the drop -->
                    <path d="M100 95.5c-4-4-10-4-14 0-4 4-4 10 0 14l14 14 14-14c4-4 4-10 0-14-4-4-10-4-14 0z" fill="#ffffff"/>
                    
                    <!-- Gradients and filters definition -->
                    <defs>
                        <linearGradient id="bloodGrad" x1="100" y1="40" x2="100" y2="135" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#ff4d4d"/>
                            <stop offset="100%" stop-color="#cc0000"/>
                        </linearGradient>
                        <filter id="dropShadow" x="50" y="35" width="100" height="110" filterUnits="userSpaceOnUse">
                            <feDropShadow dx="0" dy="8" stdDeviation="6" flood-color="#dropColor" flood-opacity="0.3"/>
                        </filter>
                    </defs>
                </svg>
            </div>
        </section>

        <!-- SEARCH FIELD SECTION -->
        <section class="search-card">
            <h3 class="search-title">Tìm kiếm chương trình hiến máu</h3>
            <form action="" method="GET" class="search-row">
                <!-- Text Input -->
                <div class="form-group">
                    <svg class="form-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                    </svg>
                    <input class="form-input" type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Nhập tên chương trình, địa điểm...">
                </div>

                <!-- Location Dropdown -->
                <div class="form-group">
                    <svg class="form-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <select class="form-input form-select" name="dia_diem">
                        <option value="tat-ca">Tất cả địa điểm</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc }}" {{ request('dia_diem') == $loc ? 'selected' : '' }}>{{ $loc }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Time Dropdown -->
                <div class="form-group">
                    <svg class="form-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <select class="form-input form-select" name="thoi_gian">
                        <option>Tất cả thời gian</option>
                        <option>Tháng này</option>
                        <option>Tháng sau</option>
                    </select>
                </div>

                <!-- Search button -->
                <button type="submit" class="btn-submit-search">Tìm kiếm</button>
            </form>
        </section>

        <!-- DONOR STATS CARDS -->
        <section class="metrics-grid">
            <!-- Metric 1: Registered programs -->
            <div class="metric-card">
                <div class="metric-icon-circle red">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-title">Tổng chương trình đã tham gia</span>
                    <span class="metric-value"><span>{{ $metrics['chuong_trinh_tg'] }}</span> chương trình</span>
                </div>
            </div>

            <!-- Metric 2: Total blood volume -->
            <div class="metric-card">
                <div class="metric-icon-circle red">
                    <!-- Blood drop icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-title">Tổng lượng máu đã hiến</span>
                    <span class="metric-value"><span>{{ $metrics['luong_mau'] }}</span> ml</span>
                </div>
            </div>

            <!-- Metric 3: Last donation date -->
            <div class="metric-card">
                <div class="metric-icon-circle orange">
                    <!-- Ribbon award icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9-3.75h-9m9 0a3 3 0 003-3V5.625c0-.621-.504-1.125-1.125-1.125H5.625c-.621 0-1.125.504-1.125 1.125V12a3 3 0 003 3m9-3.75V5.625" />
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-title">Lần hiến gần nhất</span>
                    <span class="metric-value" style="color: var(--warning);">{{ $metrics['lan_gan_nhat'] }}</span>
                </div>
            </div>

            <!-- Metric 4: Blood Type -->
            <div class="metric-card">
                <div class="metric-icon-circle green">
                    <!-- Heart icon inside circle -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                    </svg>
                </div>
                <div class="metric-info">
                    <span class="metric-title">Nhóm máu</span>
                    <span class="metric-value" style="color: var(--success);">{{ $metrics['nhom_mau'] }}</span>
                </div>
            </div>
        </section>

        <!-- UPCOMING PROGRAMS CONTAINER -->
        <section class="programs-header">
            <h3 class="programs-title">Chương trình hiến máu sắp diễn ra</h3>
            <a href="{{ route('admin.chuong-trinh.index') }}" class="btn-view-all">Xem tất cả</a>
        </section>

        <!-- PROGRAMS LIST -->
        <section class="programs-list">
            @forelse ($programs as $index => $prog)
                <div class="program-row">
                    <!-- Left: Banner Thumb -->
                    <div class="program-row-thumbnail" style="background: {{ $prog->Banner }}">
                        <div class="logo">HMTN</div>
                        <div class="heart">♥</div>
                        <div class="label">Hiến máu</div>
                    </div>
                    
                    <!-- Middle: Details -->
                    <div class="program-row-content">
                        <a href="#" class="program-row-name">{{ $prog->TenChuongTrinh }}</a>
                        
                        <div class="program-row-meta">
                            <div class="meta-item">
                                <!-- Organization Building icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 16px; height: 16px; color: var(--neutral-grey); flex-shrink: 0;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                <span>{{ $prog->TenDonVi }}</span>
                            </div>
                            
                            <div class="meta-item">
                                <!-- Location Pin icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>
                                    {{ $prog->DiaChi }}
                                    @if(!empty($prog->BanDo))
                                        &nbsp;•&nbsp; 
                                        <a href="{{ $prog->BanDo }}" target="_blank" style="color: var(--primary); font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px; color: var(--primary);">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                            </svg>
                                            Xem bản đồ
                                        </a>
                                    @endif
                                </span>
                            </div>
                            
                            <div class="meta-item">
                                <!-- Calendar -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>
                                    {{ \Carbon\Carbon::parse($prog->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }} 
                                    &nbsp;|&nbsp; 
                                    {{ \Carbon\Carbon::parse($prog->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('H:i') }} - {{ \Carbon\Carbon::parse($prog->ThoiGianKetThuc)->timezone('Asia/Ho_Chi_Minh')->format('H:i') }}
                                </span>
                            </div>
                        </div>

                        <div class="program-row-badges">
                            <span class="row-badge badge-spots">Còn {{ $prog->ChoTrong }} chỗ</span>
                            
                            @if ($prog->TrangThai == 1)
                                <span class="row-badge badge-status-pending">Chờ duyệt</span>
                            @else
                                <span class="row-badge badge-status-approved">Đã duyệt</span>
                            @endif
                        </div>
                    </div>

                    <!-- Right: CTA Actions -->
                    <div class="program-row-actions">
                        <a href="#" class="link-details">Xem chi tiết</a>
                    </div>
                </div>
            @empty
                <div class="program-row" style="justify-content: center; padding: 40px; color: var(--neutral-grey);">
                    Không tìm thấy chương trình hiến máu sắp diễn ra nào phù hợp.
                </div>
            @endforelse
        </section>

    </main>

    <!-- RIGHT PROFILE & INFO COLUMN -->
    <aside class="right-column">
        
        <!-- PROFILE PANEL -->
        <section class="right-panel-card">
            <h3 class="panel-heading">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Thông tin cá nhân
            </h3>
            <div class="profile-panel-info">
                <div class="profile-panel-avatar">
                    <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop" alt="{{ $user->HoTen }}">
                </div>
                <h4 class="profile-panel-name">{{ $user->HoTen }}</h4>
                
                <div class="profile-panel-contact">
                    <div class="contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span>{{ $user->SoDienThoai }}</span>
                    </div>
                    <div class="contact-item">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span style="word-break: break-all;">{{ $user->Email }}</span>
                    </div>
                </div>
                
                <a href="#" class="link-profile">Xem hồ sơ &gt;</a>
            </div>
        </section>

        <!-- REMINDERS PANEL -->
        <section class="right-panel-card">
            <h3 class="panel-heading">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Lịch nhắc hiến máu
            </h3>
            
            <div class="reminder-box">
                <span class="reminder-desc">Bạn có thể hiến máu sau</span>
                <span class="reminder-countdown">68 ngày nữa</span>
                <span class="reminder-date">(Dự kiến: 15/08/2025)</span>
            </div>
            
            <div class="standard-card-blue">
                <span>Nam: 3 tháng/lần</span>
                <span>Nữ: 4 tháng/lần</span>
            </div>
        </section>

        <!-- QUICK GUIDES PANEL -->
        <section class="right-panel-card">
            <h3 class="panel-heading">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.168.477 4 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4 1.253" />
                </svg>
                Hướng dẫn nhanh
            </h3>
            
            <ul class="guide-list">
                <li class="guide-item">
                    <a href="#">
                        <div class="guide-icon-wrapper orange">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 00-2 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <span>Quy trình hiến máu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="chevron-right">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </li>
                <li class="guide-item">
                    <a href="#">
                        <div class="guide-icon-wrapper blue">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <span>Điều kiện hiến máu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="chevron-right">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </li>
                <li class="guide-item">
                    <a href="#">
                        <div class="guide-icon-wrapper teal">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                        <span>Sau khi hiến máu</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="chevron-right">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </li>
                <li class="guide-item">
                    <a href="#">
                        <div class="guide-icon-wrapper purple">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                        <span>Câu hỏi thường gặp</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="chevron-right">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </li>
            </ul>
        </section>

        <!-- SUPPORT CARD -->
        <section class="help-card">
            <div class="help-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 010 12.728M16.463 8.288a5.25 5.25 0 010 7.424M6.75 8.25l4.72-4.72a.75.75 0 011.28.53v15.88a.75.75 0 01-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.01 9.01 0 012.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75z" />
                </svg>
            </div>
            <span class="help-title">Cần hỗ trợ?</span>
            <span class="help-sub">Liên hệ với chúng tôi</span>
            <a href="#" class="btn-help-contact">Liên hệ ngay</a>
        </section>
        
    </aside>
@endsection
