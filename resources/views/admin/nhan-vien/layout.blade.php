@php
    $now = \Carbon\Carbon::now(config('app.timezone'));
    $globalPrograms = DB::table('ChuongTrinhHienMau')
        ->whereNull('deleted_at')
        ->where('ThoiGianBatDau', '<=', $now)
        ->where('ThoiGianKetThuc', '>=', $now)
        ->select('Id', 'TenChuongTrinh')
        ->get();
    $globalDonors = DB::table('NguoiHienMau as nhm')
        ->join('NguoiDung as nd', 'nhm.NguoiDungId', '=', 'nd.Id')
        ->select('nhm.Id', 'nd.HoTen', 'nd.SoDienThoai')
        ->get();
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang nhân viên | Cổng thông tin Hiến Máu Tình Nguyện')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --primary-light: #eff6ff;
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
            --sidebar-bg: #111c43;
            --sidebar-active: #2563eb;
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
            background-color: #f6f8fb;
            color: var(--neutral-dark);
            min-height: 100vh;
            display: flex;
        }

        /* SIDEBAR STYLE */
        .sidebar {
            width: 260px;
            background-color: var(--sidebar-bg);
            color: #fff;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: relative;
            box-shadow: 4px 0 24px rgba(17, 28, 67, 0.15);
            z-index: 10;
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
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
            color: #ffffff;
        }

        .brand-sub {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .sidebar-section-title {
            padding: 20px 24px 8px 24px;
            font-size: 10px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 12px;
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
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .menu-item a:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .menu-item.active a {
            background: var(--sidebar-active);
            color: #fff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }

        .menu-item a svg {
            width: 20px;
            height: 20px;
            opacity: 0.8;
            transition: all 0.2s ease;
        }

        .menu-item.active a svg {
            opacity: 1;
        }

        .sidebar-footer {
            padding: 16px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 10px;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background-color: rgba(239, 68, 68, 0.1);
            color: #f87171;
        }

        /* MAIN CONTENT AREA */
        .main-wrapper {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background-color: #f6f8fb;
        }

        /* NAVBAR */
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

        /* CONTAINER BODY */
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
        .stat-icon.purple { background-color: rgba(139, 92, 246, 0.08); color: #8b5cf6; }
        .stat-icon.red { background-color: rgba(239, 68, 68, 0.08); color: var(--danger); }

        /* Highlight cancelled registration rows */
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

        /* CARD BOX STYLES */
        .card-box {
            background-color: #fff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
        }

        /* FILTER CARD STYLE */
        .filter-card {
            background-color: #fff;
            border-radius: 16px;
            padding: 20px 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
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
            grid-template-columns: 2fr 1.5fr auto;
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

        .filter-select {
            padding-left: 14px;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 14px;
            padding-right: 40px;
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
            margin-left: 6px;
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

        .page-item.active a {
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

        /* INDEX DASHBOARD EXTRA STYLES */
        .content-columns {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 24px;
        }

        @media (max-width: 992px) {
            .content-columns {
                grid-template-columns: 1fr;
            }
        }

        .search-dossier-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .dossier-heading {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 700;
            color: var(--neutral-dark);
        }

        .dossier-tabs {
            display: flex;
            gap: 8px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
        }

        .dossier-tab-btn {
            background: none;
            border: none;
            cursor: pointer;
            padding: 6px 16px;
            font-family: var(--font-main);
            font-size: 13px;
            font-weight: 600;
            color: var(--neutral-grey);
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .dossier-tab-btn.active {
            background-color: var(--primary-light);
            color: var(--primary);
        }

        .dossier-tab-btn:hover:not(.active) {
            background-color: var(--neutral-light);
            color: var(--neutral-dark);
        }

        .dossier-body {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .dossier-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-group-dossier {
            position: relative;
            display: flex;
            align-items: center;
        }

        .dossier-input-icon {
            position: absolute;
            left: 14px;
            width: 18px;
            height: 18px;
            color: var(--neutral-grey);
            pointer-events: none;
        }

        .dossier-input {
            width: 100%;
            height: 44px;
            padding: 8px 16px 8px 44px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            font-family: var(--font-main);
            font-size: 14px;
            font-weight: 500;
            color: var(--neutral-dark);
            outline: none;
            transition: all 0.2s ease;
        }

        .dossier-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .dossier-divider {
            text-align: center;
            font-size: 12px;
            color: var(--neutral-grey);
            font-weight: 600;
            text-transform: uppercase;
            position: relative;
        }

        .dossier-divider::before, .dossier-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: var(--border-color);
        }

        .dossier-divider::before { left: 0; }
        .dossier-divider::after { right: 0; }

        .btn-dossier-search {
            height: 44px;
            background-color: var(--primary);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: var(--font-main);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        .btn-dossier-search:hover {
            background-color: var(--primary-hover);
        }

        .dossier-promo-panel {
            background-color: #f8fafc;
            border: 1px dashed var(--border-color);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 12px;
        }

        .promo-text {
            font-size: 12px;
            color: var(--neutral-grey);
            font-weight: 500;
            line-height: 1.4;
        }

        .right-column-wrapper {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .today-prog-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            padding: 24px;
            color: #fff;
            display: flex;
            flex-direction: column;
            gap: 16px;
            box-shadow: var(--shadow-md);
        }

        .today-prog-heading {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255, 255, 255, 0.5);
        }

        .today-prog-name {
            font-family: var(--font-heading);
            font-size: 20px;
            font-weight: 700;
        }

        .today-prog-details {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .today-prog-details div {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13.5px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
        }

        .today-prog-details svg {
            width: 18px;
            height: 18px;
            color: var(--primary);
        }

        .today-prog-link {
            color: var(--primary-light);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-top: 4px;
        }

        .quick-actions-box {
            background-color: #fff;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            box-shadow: var(--shadow-sm);
        }

        .action-row-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            border-radius: 12px;
            text-decoration: none;
            color: var(--neutral-dark);
            transition: all 0.2s ease;
        }

        .action-row-btn:hover {
            background-color: var(--neutral-light);
        }

        .action-row-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .action-row-btn.green .action-row-icon { background-color: rgba(16, 185, 129, 0.08); color: var(--success); }
        .action-row-btn.blue .action-row-icon { background-color: rgba(37, 99, 235, 0.08); color: var(--primary); }
        .action-row-btn.orange .action-row-icon { background-color: rgba(245, 158, 11, 0.08); color: var(--warning); }

        .action-row-icon svg {
            width: 20px;
            height: 20px;
        }

        .action-row-info {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            line-height: 1.2;
        }

        .action-row-title {
            font-size: 13.5px;
            font-weight: 700;
        }

        .action-row-subtitle {
            font-size: 11px;
            color: var(--neutral-grey);
            margin-top: 2px;
        }

        .action-row-chevron {
            width: 16px;
            height: 16px;
            color: var(--neutral-grey);
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
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--neutral-grey);
            border-left: 3px solid var(--primary);
            padding-left: 8px;
            margin-bottom: 12px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        @media (max-width: 576px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        .detail-item {
            background-color: var(--neutral-light);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            position: relative;
            overflow: hidden;
        }

        .detail-item-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--neutral-grey);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-item-value {
            font-size: 14px;
            font-weight: 700;
            color: var(--neutral-dark);
        }

        /* Health Parameters Visual Badges */
        .health-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }

        @media (max-width: 576px) {
            .health-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .health-card {
            background-color: #fff;
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 8px;
            box-shadow: var(--shadow-sm);
        }

        .health-card-icon {
            width: 36px;
            height: 36px;
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
        .health-card-icon.blue { background-color: rgba(59, 130, 246, 0.08); color: #3b82f6; }

        .health-card-icon svg {
            width: 18px;
            height: 18px;
        }

        .health-card-title {
            font-size: 11px;
            font-weight: 700;
            color: var(--neutral-grey);
            text-transform: uppercase;
        }

        .health-card-value {
            font-family: var(--font-heading);
            font-size: 16px;
            font-weight: 700;
            color: var(--neutral-dark);
        }

        .note-card {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .note-card-title {
            font-size: 12px;
            font-weight: 700;
            color: #d97706;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .note-card-title svg {
            width: 16px;
            height: 16px;
        }

        .note-card-value {
            font-size: 13.5px;
            font-weight: 500;
            color: #92400e;
            line-height: 1.4;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: flex-end;
            background-color: var(--neutral-light);
        }

        .btn-modal-close-action {
            background-color: var(--neutral-dark);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: var(--font-main);
            font-size: 14px;
            font-weight: 600;
            padding: 10px 20px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-modal-close-action:hover {
            background-color: #1e293b;
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
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

        <div class="sidebar-section-title">Danh mục</div>
        <ul class="sidebar-menu">
            <li class="menu-item {{ Request::routeIs('nhan-vien.index') ? 'active' : '' }}">
                <a href="{{ route('nhan-vien.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    <span>Trang chủ</span>
                </a>
            </li>
            <li class="menu-item {{ Request::routeIs('nhan-vien.ho-so') ? 'active' : '' }}">
                <a href="{{ route('nhan-vien.ho-so') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                    </svg>
                    <span>Tra cứu hồ sơ</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="#" onclick="event.preventDefault(); openCreateModal();">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Thêm mới hồ sơ</span>
                </a>
            </li>
            <!-- <li class="menu-item">
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
            </li> -->
        </ul>

        <div class="sidebar-footer">
            <a href="#" class="btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                <span>Đăng xuất</span>
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- MAIN BODY CONTAINER -->
    <div class="main-wrapper">
        <!-- TOP NAVBAR -->
        <header class="top-navbar">
            <div class="navbar-left">
                <button class="menu-toggle-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <h1 class="navbar-title">@yield('navbar_title', 'Trang nhân viên')</h1>
            </div>
            
            <div class="header-actions">
                <div class="notification-bell">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                    <span class="bell-badge">2</span>
                </div>
                
                <div class="user-profile-header" onclick="openStaffProfileModal()" style="cursor: pointer; transition: all 0.2s ease;" title="Chỉnh sửa thông tin cá nhân">
                    <div class="user-avatar-header">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=256&auto=format&fit=crop" alt="{{ $staff->HoTen }}">
                    </div>
                    <div class="user-info-header">
                        <span class="user-name-header">{{ $staff->HoTen }}</span>
                        <span class="user-role-header">{{ $staff->TenVaiTro }}</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- DASHBOARD BODY -->
        <div class="dashboard-body">
            @if (session('success'))
                <div style="background-color: var(--success-light); border: 1px solid rgba(16, 185, 129, 0.2); color: var(--success); padding: 16px; border-radius: 12px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px; font-weight: 600; font-size: 14px; box-shadow: var(--shadow-sm); animation: fadeIn 0.3s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 20px; height: 20px; flex-shrink: 0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    @yield('scripts')

    <!-- PREMIUM CREATE HEALTH DOSSIER MODAL -->
    <div id="createModal" class="modal-backdrop">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header">
                <div class="modal-header-profile">
                    <div class="brand-logo" style="width: 36px; height: 36px; box-shadow: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 20px; height: 20px; color: #fff;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="modal-name-wrap">
                        <span class="modal-name">Thêm hồ sơ sức khỏe mới</span>
                        <span class="modal-sub">Nhập thông tin kiểm tra sức khỏe và kết quả hiến máu</span>
                    </div>
                </div>
                <button class="btn-modal-close" onclick="closeCreateModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('nhan-vien.ho-so.store') }}" method="POST">
                @csrf
                @if (session('success'))
                    <div class="alert alert-success" style="margin: 0 20px 16px 20px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger" style="margin: 0 20px 16px 20px; background-color: var(--danger-light); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--danger); padding: 14px 16px; border-radius: 12px; font-weight: 600; font-size: 14px;">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger" style="margin: 0 20px 16px 20px; background-color: var(--danger-light); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--danger); padding: 14px 16px; border-radius: 12px; font-weight: 600; font-size: 14px;">
                        <strong>Vui lòng kiểm tra lại thông tin:</strong>
                        <ul style="margin: 8px 0 0 18px;">
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="modal-body" style="gap: 16px;">
                    <!-- Program & Donor -->
                    <div>
                        <h4 class="modal-section-title">Thông tin người hiến & Chương trình</h4>
                        <div class="details-grid" style="grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div class="form-group-filter" style="grid-column: span 2;">
                                <h5 style="margin: 4px 0 0 0; color: var(--primary); font-size: 14px; font-weight: 600; border-bottom: 1px solid var(--neutral-border); padding-bottom: 6px;">Thông tin cá nhân (Hệ thống tự động đăng ký tài khoản)</h5>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_hoten" class="filter-label">Họ và tên <span style="color: var(--danger);">*</span></label>
                                <input type="text" id="c_hoten" name="hoten" class="filter-input" style="padding-left: 14px; background-color: var(--neutral-light);" placeholder="Nguyễn Văn A" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_sodienthoai" class="filter-label">Số điện thoại <span style="color: var(--danger);">*</span></label>
                                <input type="text" id="c_sodienthoai" name="sodienthoai" class="filter-input" style="padding-left: 14px; background-color: var(--neutral-light);" placeholder="0912345678" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_email" class="filter-label">Email <span style="color: var(--danger);">*</span></label>
                                <input type="email" id="c_email" name="email" class="filter-input" style="padding-left: 14px; background-color: var(--neutral-light);" placeholder="email@gmail.com" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_cccd" class="filter-label">Số CCCD <span style="color: var(--danger);">*</span></label>
                                <input type="text" id="c_cccd" name="cccd" class="filter-input" style="padding-left: 14px; background-color: var(--neutral-light);" placeholder="Căn cước công dân 12 số" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_ngaysinh" class="filter-label">Ngày sinh <span style="color: var(--danger);">*</span></label>
                                <input type="date" id="c_ngaysinh" name="ngaysinh" class="filter-input" style="padding-left: 14px; background-color: var(--neutral-light);" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_gioitinh" class="filter-label">Giới tính <span style="color: var(--danger);">*</span></label>
                                <select id="c_gioitinh" name="gioitinh" class="filter-input filter-select" style="padding-left: 14px; background-color: var(--neutral-light);" required>
                                    <option value="1">Nam</option>
                                    <option value="2">Nữ</option>
                                    <option value="3">Khác</option>
                                </select>
                            </div>
                            <div class="form-group-filter" style="grid-column: span 2;">
                                <label for="c_diachi" class="filter-label">Địa chỉ thường trú <span style="color: var(--danger);">*</span></label>
                                <input type="text" id="c_diachi" name="diachi" class="filter-input" style="padding-left: 14px; background-color: var(--neutral-light);" placeholder="Địa chỉ của người hiến..." required>
                            </div>
                            <div class="form-group-filter" style="grid-column: span 2; margin-top: 8px;">
                                <h5 style="margin: 4px 0 0 0; color: var(--primary); font-size: 14px; font-weight: 600; border-bottom: 1px solid var(--neutral-border); padding-bottom: 6px;">Đăng ký chương trình</h5>
                            </div>
                            <div class="form-group-filter" style="grid-column: span 2;">
                                <label for="c_chuong_trinh" class="filter-label">Chương trình hiến máu <span style="color: var(--danger);">*</span></label>
                                <select id="c_chuong_trinh" name="chuong_trinh_id" class="filter-input filter-select" style="padding-left: 14px; background-color: var(--neutral-light);" required>
                                    <option value="">-- Chọn chương trình --</option>
                                    @foreach ($globalPrograms as $prog)
                                        <option value="{{ $prog->Id }}">{{ $prog->TenChuongTrinh }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Clinical Pre-Donation Screening -->
                    <div>
                        <h4 class="modal-section-title">Kết quả kiểm tra sức khỏe trước hiến</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div class="form-group-filter">
                                <label for="c_nhom_mau" class="filter-label">Nhóm máu <span style="color: var(--danger);">*</span></label>
                                <select id="c_nhom_mau" name="nhom_mau" class="filter-input filter-select" style="padding-left: 14px; background-color: var(--neutral-light);" required>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_huyet_ap" class="filter-label">Huyết áp (mmHg) <span style="color: var(--danger);">*</span></label>
                                <input type="text" id="c_huyet_ap" name="huyet_ap" class="filter-input" style="padding-left: 14px;" placeholder="Ví dụ: 120/80" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_nhip_tim" class="filter-label">Nhịp tim (bpm) <span style="color: var(--danger);">*</span></label>
                                <input type="number" id="c_nhip_tim" name="nhip_tim" class="filter-input" style="padding-left: 14px;" placeholder="bpm" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_nhiet_do" class="filter-label">Nhiệt độ (°C) <span style="color: var(--danger);">*</span></label>
                                <input type="number" step="0.1" id="c_nhiet_do" name="nhiet_do" class="filter-input" style="padding-left: 14px;" placeholder="°C" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_can_nang" class="filter-label">Cân nặng (kg) <span style="color: var(--danger);">*</span></label>
                                <input type="number" step="0.1" id="c_can_nang" name="can_nang" class="filter-input" style="padding-left: 14px;" placeholder="kg" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_hemoglobin" class="filter-label">Hemoglobin (g/dL) <span style="color: var(--danger);">*</span></label>
                                <input type="number" step="0.1" id="c_hemoglobin" name="hemoglobin" class="filter-input" style="padding-left: 14px;" placeholder="g/dL" required>
                            </div>
                            <div class="form-group-filter" style="grid-column: span 2;">
                                <label for="c_nguoi_kham" class="filter-label">Bác sĩ khám tuyển <span style="color: var(--danger);">*</span></label>
                                <input type="text" id="c_nguoi_kham" name="nguoi_kham" class="filter-input" style="padding-left: 14px;" value="{{ $staff->HoTen }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Donation Outcome -->
                    <div>
                        <h4 class="modal-section-title">Kết quả hiến máu</h4>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div class="form-group-filter">
                                <label for="c_luong_mau" class="filter-label">Lượng máu hiến <span style="color: var(--danger);">*</span></label>
                                <select id="c_luong_mau" name="luong_mau" class="filter-input filter-select" style="padding-left: 14px; background-color: var(--neutral-light);" required>
                                    <option value="350">350 ml</option>
                                    <option value="250">250 ml</option>
                                    <option value="450">450 ml</option>
                                    <option value="0">0 ml (Không hiến)</option>
                                </select>
                            </div>
                            <div class="form-group-filter">
                                <label for="c_ket_qua" class="filter-label">Kết quả hiến máu <span style="color: var(--danger);">*</span></label>
                                <select id="c_ket_qua" name="ket_qua_sau_hien" class="filter-input filter-select" style="padding-left: 14px; background-color: var(--neutral-light);" required>
                                    <option value="1">Thành công</option>
                                    <option value="2">Thất bại / Hủy</option>
                                </select>
                            </div>
                            <div class="form-group-filter" style="grid-column: span 2;">
                                <label for="c_ghi_chu" class="filter-label">Ghi chú lâm sàng</label>
                                <textarea id="c_ghi_chu" name="ghi_chu" class="filter-input" style="padding-left: 14px; height: 60px; padding-top: 8px; resize: none;" placeholder="Nhập ghi chú..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn-filter-reset" onclick="closeCreateModal()" style="height: 40px;">Hủy bỏ</button>
                    <button type="submit" class="btn-filter-submit" style="height: 40px; margin-left: 8px;">Lưu hồ sơ</button>
                </div>
            </form>
        </div>
    </div>

    <!-- STAFF PROFILE EDIT MODAL -->
    <div id="staffProfileModal" class="modal-backdrop">
        <div class="modal-content" style="max-width: 550px;">
            <div class="modal-header">
                <div class="modal-header-profile">
                    <div class="modal-avatar" style="background: linear-gradient(135deg, var(--primary) 0%, #1d4ed8 100%);">
                        {{ mb_substr($staff->HoTen, 0, 1) }}
                    </div>
                    <div class="modal-name-wrap">
                        <span class="modal-name">Thông tin cá nhân</span>
                        <span class="modal-sub">{{ $staff->TenVaiTro }}</span>
                    </div>
                </div>
                <button class="btn-modal-close" onclick="closeStaffProfileModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('nhan-vien.profile.update') }}" method="POST">
                @csrf
                <div class="modal-body" style="gap: 16px; padding: 20px;">
                    @if ($errors->any() && ($errors->has('HoTen') || $errors->has('Email') || $errors->has('SoDienThoai') || $errors->has('NgaySinh') || $errors->has('GioiTinh') || $errors->has('MatKhau')))
                        <div class="alert alert-danger" style="margin-bottom: 16px; background-color: var(--danger-light); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--danger); padding: 10px 14px; border-radius: 8px; font-size: 13px; font-weight: 600;">
                            <ul style="margin: 0 0 0 16px; padding: 0;">
                                @foreach ($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div style="display: grid; grid-template-columns: 1fr; gap: 12px;">
                        <div class="form-group-filter">
                            <label for="p_hoten" class="filter-label">Họ và tên <span style="color: var(--danger);">*</span></label>
                            <input type="text" id="p_hoten" name="HoTen" class="filter-input" style="padding-left: 14px;" value="{{ old('HoTen', $staff->HoTen) }}" required>
                        </div>
                        
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div class="form-group-filter">
                                <label for="p_sodienthoai" class="filter-label">Số điện thoại <span style="color: var(--danger);">*</span></label>
                                <input type="text" id="p_sodienthoai" name="SoDienThoai" class="filter-input" style="padding-left: 14px;" value="{{ old('SoDienThoai', $staff->SoDienThoai) }}" required>
                            </div>
                            <div class="form-group-filter">
                                <label for="p_email" class="filter-label">Email <span style="color: var(--danger);">*</span></label>
                                <input type="email" id="p_email" name="Email" class="filter-input" style="padding-left: 14px;" value="{{ old('Email', $staff->Email) }}" required>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px;">
                            <div class="form-group-filter">
                                <label for="p_ngaysinh" class="filter-label">Ngày sinh</label>
                                <input type="date" id="p_ngaysinh" name="NgaySinh" class="filter-input" style="padding-left: 14px;" value="{{ old('NgaySinh', $staff->NgaySinh ? \Carbon\Carbon::parse($staff->NgaySinh)->format('Y-m-d') : '') }}">
                            </div>
                            <div class="form-group-filter">
                                <label for="p_gioitinh" class="filter-label">Giới tính</label>
                                <select id="p_gioitinh" name="GioiTinh" class="filter-input filter-select" style="padding-left: 14px; background-color: var(--neutral-light);">
                                    <option value="">-- Chọn giới tính --</option>
                                    <option value="1" {{ old('GioiTinh', $staff->GioiTinh) == 1 ? 'selected' : '' }}>Nam</option>
                                    <option value="2" {{ old('GioiTinh', $staff->GioiTinh) == 2 ? 'selected' : '' }}>Nữ</option>
                                    <option value="3" {{ old('GioiTinh', $staff->GioiTinh) == 3 ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group-filter">
                            <label for="p_matkhau" class="filter-label">Mật khẩu mới (Để trống nếu không muốn đổi)</label>
                            <input type="password" id="p_matkhau" name="MatKhau" class="filter-input" style="padding-left: 14px;" placeholder="Nhập ít nhất 6 ký tự...">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer" style="padding: 16px 20px;">
                    <button type="button" class="btn-filter-reset" onclick="closeStaffProfileModal()" style="height: 40px;">Hủy bỏ</button>
                    <button type="submit" class="btn-filter-submit" style="height: 40px; margin-left: 8px;">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL SCRIPTS -->
    <script>
        const createModal = document.getElementById('createModal');
        const staffProfileModal = document.getElementById('staffProfileModal');

        function openCreateModal() {
            if (createModal) createModal.classList.add('open');
        }
        function closeCreateModal() {
            if (createModal) createModal.classList.remove('open');
        }

        function openStaffProfileModal() {
            if (staffProfileModal) staffProfileModal.classList.add('open');
        }
        function closeStaffProfileModal() {
            if (staffProfileModal) staffProfileModal.classList.remove('open');
        }

        @if ($errors->any())
            @if ($errors->has('HoTen') || $errors->has('Email') || $errors->has('SoDienThoai') || $errors->has('NgaySinh') || $errors->has('GioiTinh') || $errors->has('MatKhau'))
                window.addEventListener('load', function() {
                    openStaffProfileModal();
                });
            @else
                window.addEventListener('load', function() {
                    openCreateModal();
                });
            @endif
        @endif

        // Close when clicking backdrop
        if (createModal) {
            createModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCreateModal();
                }
            });
        }

        if (staffProfileModal) {
            staffProfileModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeStaffProfileModal();
                }
            });
        }

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCreateModal();
                closeStaffProfileModal();
            }
        });
    </script>
</body>
</html>
