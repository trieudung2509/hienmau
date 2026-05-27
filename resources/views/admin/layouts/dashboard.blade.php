<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Cổng thông tin Hiến Máu Tình Nguyện</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @if(($role ?? '') === 'admin')
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @endif
    
    <!-- Shared Master Styles -->
    <style>
        :root {
            --primary: {{ $primaryColor ?? '#2563eb' }};
            --primary-hover: {{ $primaryHoverColor ?? '#1d4ed8' }};
            --primary-light: {{ $primaryLightColor ?? '#eff6ff' }};
            --success: #10b981;
            --success-light: #ecfdf5;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            --info: #3b82f6;
            --info-light: #eff6ff;
            --neutral-dark: #0f172a;
            --neutral-grey: #64748b;
            --neutral-light: #f8fafc;
            --border-color: #e2e8f0;
            --sidebar-bg: {{ $sidebarBg ?? '#111c43' }};
            --sidebar-active: {{ $sidebarActive ?? '#2563eb' }};
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --font-heading: 'Outfit', sans-serif;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.03);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            background-color: {{ $bodyBg ?? '#f3f6ff' }};
            color: var(--neutral-dark);
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR BASE STYLE */
        .sidebar {
            width: 260px;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: relative;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-family: var(--font-heading);
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }

        .brand-sub {
            font-size: 10px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex-grow: 1;
        }

        .menu-item a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .menu-item a svg {
            width: 20px;
            height: 20px;
            opacity: 0.8;
            transition: all 0.2s ease;
        }

        .sidebar-footer {
            padding: 16px 12px;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        /* MAIN CONTENT STYLE */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        /* HEADER / NAVBAR */
        .top-navbar {
            height: 70px;
            background-color: #fff;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            flex-shrink: 0;
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .menu-toggle-btn {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--neutral-grey);
            padding: 4px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .menu-toggle-btn:hover {
            background-color: #f1f5f9;
            color: var(--neutral-dark);
        }

        .menu-toggle-btn svg {
            width: 20px;
            height: 20px;
        }

        .navbar-title {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 700;
            color: var(--neutral-dark);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--neutral-light);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            border: 1px solid var(--border-color);
        }

        .notification-bell:hover {
            background-color: #e2e8f0;
        }

        .notification-bell svg {
            width: 20px;
            height: 20px;
            color: var(--neutral-grey);
        }

        .bell-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            background-color: var(--danger);
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        .user-profile-header {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .user-profile-link {
            text-decoration: none;
            color: inherit;
        }

        .user-avatar-header {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background-color: #3b82f6;
            overflow: hidden;
            border: 2px solid #fff;
            box-shadow: var(--shadow-sm);
        }

        .user-avatar-header img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info-header {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .user-name-header {
            font-size: 13px;
            font-weight: 700;
            color: var(--neutral-dark);
        }

        .user-role-header {
            font-size: 11px;
            color: var(--neutral-grey);
            font-weight: 500;
        }

        /* Sidebar dynamic styles */
        /* Dark Theme */
        .sidebar.sidebar-dark {
            background-color: #111c43;
            color: #fff;
            border-right: none;
            box-shadow: 4px 0 24px rgba(17, 28, 67, 0.15);
        }
        .sidebar.sidebar-dark .sidebar-brand {
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .sidebar.sidebar-dark .brand-name {
            color: #ffffff;
        }
        .sidebar.sidebar-dark .brand-sub {
            color: rgba(255, 255, 255, 0.6);
        }
        .sidebar.sidebar-dark .menu-item a {
            color: rgba(255, 255, 255, 0.7);
        }
        .sidebar.sidebar-dark .menu-item a:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }
        .sidebar.sidebar-dark .menu-item.active a {
            background: var(--sidebar-active);
            color: #fff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }
        .sidebar.sidebar-dark .menu-item.active a svg {
            color: #fff;
            opacity: 1;
        }
        .sidebar.sidebar-dark .sidebar-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        .sidebar.sidebar-dark .btn-logout {
            color: rgba(255, 255, 255, 0.6);
            border: none;
            background: transparent;
            justify-content: flex-start;
        }
        .sidebar.sidebar-dark .btn-logout:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: #f87171;
        }

        /* Light Theme */
        .sidebar.sidebar-light {
            background-color: #ffffff;
            color: var(--neutral-dark);
            border-right: 1px solid #e2e8f0;
        }
        .sidebar.sidebar-light .sidebar-brand {
            border-bottom: 1px solid #f1f5f9;
        }
        .sidebar.sidebar-light .brand-name {
            color: #ef4444;
        }
        .sidebar.sidebar-light .brand-sub {
            color: var(--neutral-grey);
        }
        .sidebar.sidebar-light .menu-item a {
            color: #475569;
        }
        .sidebar.sidebar-light .menu-item a:hover {
            background-color: #f8fafc;
            color: var(--primary);
        }
        .sidebar.sidebar-light .menu-item.active a {
            background-color: var(--sidebar-active);
            color: var(--primary);
        }
        .sidebar.sidebar-light .menu-item.active a svg {
            color: var(--primary);
            opacity: 1;
        }
        .sidebar.sidebar-light .sidebar-footer {
            border-top: 1px solid #f1f5f9;
        }
        .sidebar.sidebar-light .btn-logout {
            color: var(--primary);
            border: 1px solid rgba(239, 68, 68, 0.2);
            background-color: #fff;
            justify-content: center;
        }
        .sidebar.sidebar-light .btn-logout:hover {
            background-color: var(--primary-light);
            border-color: var(--primary);
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- SHARED SIDEBAR -->
    <aside class="sidebar {{ $sidebarClass ?? 'sidebar-dark' }}">
        <div class="sidebar-brand">
            <div class="brand-logo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 24px; height: 24px; color: #fff;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </div>
            <div class="brand-text">
                <span class="brand-name">HIẾN MÁU</span>
                <span class="brand-sub">Tình Nguyện</span>
            </div>
        </div>

        <ul class="sidebar-menu">
            @if(($role ?? '') === 'admin')
                <!-- ADMIN MENU ITEMS -->
                <li class="menu-item {{ Request::routeIs('admin.home') || Request::is('/') ? 'active' : '' }}">
                    <a href="{{ route('admin.home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                        </svg>
                        <span>Trang chủ</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.nguoi-dung.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.nguoi-dung.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a.75.75 0 000-1.5H5.83a2.25 2.25 0 001.422-1.373l.635-1.905a3.75 3.75 0 016.924 0l.635 1.905A2.25 2.25 0 0016.87 17.25H18.75A.75.75 0 0020.25 18v.75H18z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                        <span>Quản lý tài khoản</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.don-vi-to-chuc.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.don-vi-to-chuc.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a.75.75 0 000-1.5H5.83a2.25 2.25 0 001.422-1.373l.635-1.905a3.75 3.75 0 016.924 0l.635 1.905A2.25 2.25 0 0016.87 17.25H18.75A.75.75 0 0020.25 18v.75H18z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 12.75a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                        <span>Quản lý đơn vị tổ chức</span>
                    </a>
                </li>
                <li class="menu-item {{ (Request::routeIs('admin.chuong-trinh.index') && request('tab') !== 'cho-duyet') ? 'active' : '' }}">
                    <a href="{{ route('admin.chuong-trinh.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>Quản lý chương trình</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.thong-ke') ? 'active' : '' }}">
                    <a href="{{ route('admin.thong-ke') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        <span>Thống kê</span>
                    </a>
                </li>
                <li class="menu-item {{ Request::routeIs('admin.ho-so.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.ho-so.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Quản lý hồ sơ</span>
                    </a>
                </li>
            @elseif(($role ?? '') === 'nhan-vien')
                <!-- STAFF MENU ITEMS -->
                <li class="menu-item active">
                    <a href="{{ route('nhan-vien.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span>Trang chủ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                        </svg>
                        <span>Tra cứu hồ sơ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Thêm mới hồ sơ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7C4.547 9.547 4.5 10.768 4.5 12s.047 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.092-1.209.138-2.43.138-3.662z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Cập nhật hồ sơ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span>Danh sách người tham gia</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        <span>Thống kê</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Lịch sử hoạt động</span>
                    </a>
                </li>

            @else
                <!-- DONOR / PARTICIPANT MENU ITEMS -->
                <li class="menu-item {{ Request::routeIs('admin.home') || Request::is('admin/trang-chu') ? 'active' : '' }}">
                    <a href="{{ route('admin.home') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span>Trang chủ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                        </svg>
                        <span>Tra cứu hồ sơ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Thêm mới hồ sơ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7C4.547 9.547 4.5 10.768 4.5 12s.047 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.092-1.209.138-2.43.138-3.662z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Cập nhật hồ sơ</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span>Danh sách người tham gia</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        <span>Thống kê</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Lịch sử hoạt động</span>
                    </a>
                </li>
            @endif
        </ul>

        <div class="sidebar-footer">
            @if(($role ?? '') === 'admin')
                <form method="post" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout" style="width: 100%;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="20">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            @else
                <a href="#" class="btn-logout" style="width: 100%">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                    <span>Đăng xuất</span>
                </a>
            @endif
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <div class="main-wrapper">
        <!-- TOP NAVBAR -->
        <header class="top-navbar">
            <div class="navbar-left">
                <button class="menu-toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="navbar-title-box">
                    <span class="navbar-title">@yield('navbar-title')</span>
                    @hasSection('navbar-subtitle')
                        <span class="navbar-subtitle-text" style="font-size: 11px; color: var(--neutral-grey); display: block; margin-top: 2px;">@yield('navbar-subtitle')</span>
                    @endif
                </div>
            </div>
            
            <div class="header-actions">
                <div class="notification-bell">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span class="bell-badge">3</span>
                </div>
                
                <a class="user-profile-header user-profile-link" href="{{ route('profile.edit', ['role' => $role ?? 'donor']) }}">
                    <div class="user-avatar-header">
                        <img src="{{ $userAvatar ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&q=80&w=256' }}" alt="Avatar">
                    </div>
                    <div class="user-info-header">
                        <span class="user-name-header">{{ $userName ?? 'Người dùng' }}</span>
                        <span class="user-role-header">{{ $userRole ?? 'Khách' }}</span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" class="chevron-down" style="width: 14px; height: 14px; color: var(--neutral-grey); margin-left: 4px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </a>
            </div>
        </header>

        <!-- PAGE BODY -->
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
