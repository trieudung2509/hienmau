<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'BloodCare - Đơn Vị Tổ Chức')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #f7f7f7;
      display: flex;
    }

    a {
      text-decoration: none;
    }

    /* ================= SIDEBAR TRÁI ================= */
    .sidebar {
      width: 280px;
      height: 100vh;
      background: white;
      padding: 25px 20px;
      box-shadow: 5px 0 20px rgba(0,0,0,0.02);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: fixed;
      left: 0;
      top: 0;
    }

    .brand-box {
      display: flex;
      align-items: center;
      gap: 12px;
      padding-left: 10px;
      margin-bottom: 30px;
    }

    .brand-logo {
      width: 40px;
      height: 40px;
      background: #e53935;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 20px;
    }

    .brand-name h2 {
      font-size: 22px;
      color: #333;
      font-weight: 700;
    }

    .brand-name p {
      font-size: 11px;
      color: #888;
      margin-top: -2px;
    }

    .menu {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .menu a {
      padding: 14px 18px;
      border-radius: 14px;
      color: #666;
      font-weight: 500;
      font-size: 14px;
      transition: 0.3s;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .menu a:hover,
    .menu .active {
      background: #fff0f0;
      color: #e53935;
      font-weight: 600;
    }

    /* ================= KHÔNG GIAN LÀM VIỆC CHÍNH ================= */
    .container {
      margin-left: 280px;
      width: calc(100% - 280px);
      display: flex;
      padding: 30px;
      gap: 30px;
    }

    .content-area {
      flex: 1;
      min-width: 0;
    }

    /* Header thanh điều hướng trên cùng */
    .topbar {
      background: white;
      border-radius: 22px;
      padding: 20px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 5px 20px rgba(0,0,0,0.03);
      margin-bottom: 25px;
    }

    .topbar-title {
      display: flex;
      align-items: center;
      gap: 12px;
      color: #333;
      font-size: 15px;
      font-weight: 500;
    }

    .topbar-title i {
      color: #888;
      cursor: pointer;
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 20px;
    }

    .noti-btn {
      position: relative;
      color: #666;
      font-size: 18px;
      cursor: pointer;
    }

    .noti-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      width: 15px;
      height: 15px;
      background: #e53935;
      color: white;
      font-size: 9px;
      font-weight: 700;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .user-profile-header {
      display: flex;
      align-items: center;
      gap: 12px;
      padding-left: 20px;
      border-left: 1px solid #eee;
    }

    .user-info-header {
      text-align: right;
    }

    .user-info-header h4 {
      font-size: 13px;
      color: #333;
      font-weight: 600;
    }

    .user-info-header p {
      font-size: 11px;
      color: #888;
    }

    .avatar-wrap-header {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #e6f7ed;
      color: #2e7d32;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
    }

    /* Banner chào mừng hồng nhạt */
    .welcome-banner {
      background: linear-gradient(to right, #fff4f4, #ffe9e9);
      border-radius: 22px;
      padding: 30px;
      margin-bottom: 25px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 1px solid #ffe1e1;
    }

    .welcome-text h2 {
      font-size: 24px;
      color: #e53935;
      font-weight: 700;
      margin-bottom: 6px;
    }

    .welcome-text p {
      color: #666;
      font-size: 13px;
    }

    .blood-drop-art {
      font-size: 55px;
      color: #ff4b5c;
      filter: drop-shadow(0 5px 10px rgba(255, 75, 92, 0.2));
    }

    /* Khối thẻ đếm số liệu */
    .stat-cards {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
      margin-bottom: 25px;
    }

    .stat-card {
      background: white;
      padding: 20px 15px;
      border-radius: 22px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.03);
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .stat-icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
    }

    .stat-info h3 {
      font-size: 20px;
      font-weight: 700;
      color: #333;
      line-height: 1.2;
    }

    .stat-info p {
      color: #777;
      font-size: 11px;
      margin-top: 2px;
    }

    /* Tiêu đề trang và nút hành động */
    .page-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }

    .page-title h2 {
      font-size: 22px;
      color: #333;
      font-weight: 700;
    }

    .page-title p {
      font-size: 13px;
      color: #777;
      margin-top: 2px;
    }

    .btn-create-proposal {
      background: #e53935;
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 14px;
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 8px;
      transition: 0.3s;
      box-shadow: 0 4px 15px rgba(229, 57, 53, 0.2);
    }

    .btn-create-proposal:hover {
      background: #c62828;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(229, 57, 53, 0.3);
    }

    /* Bộ lọc tìm kiếm */
    .filter-box {
      background: white;
      border-radius: 22px;
      padding: 20px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.03);
      margin-bottom: 25px;
    }

    .filter-box h3 {
      font-size: 13px;
      color: #e53935;
      font-weight: 700;
      text-transform: uppercase;
      margin-bottom: 12px;
    }

    .filter-row {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr auto;
      gap: 12px;
    }

    /* Nếu không có thoi_gian (3 cột lọc + 1 nút tìm kiếm) */
    .filter-box form:not(:has(select[name="thoi_gian"])) .filter-row {
      grid-template-columns: 2fr 1.5fr auto;
    }

    .input-group {
      position: relative;
    }

    .input-group i {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      font-size: 13px;
    }

    .filter-row input, .filter-row select {
      width: 100%;
      padding: 11px 15px 11px 38px;
      background: #fdfdfd;
      border: 1px solid #eee;
      border-radius: 12px;
      font-size: 13px;
      color: #444;
      outline: none;
      transition: 0.2s;
    }

    .filter-row select {
      padding-left: 35px;
    }

    .filter-row input:focus, .filter-row select:focus {
      border-color: #ffb3b3;
      background: white;
    }

    .search-btn {
      background: #e53935;
      color: white;
      border: none;
      padding: 0 24px;
      border-radius: 12px;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s;
    }

    .search-btn:hover {
      background: #c62828;
    }

    /* Alert styles */
    .alert-success {
      background: #e6f7ed;
      color: #2e7d32;
      padding: 15px 20px;
      border-radius: 14px;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 20px;
      border: 1px solid #c8e6c9;
    }

    .alert-error {
      background: #ffebee;
      color: #c62828;
      padding: 15px 20px;
      border-radius: 14px;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 20px;
      border: 1px solid #ffcdd2;
    }

    /* Danh sách chương trình */
    .program-section {
      background: white;
      border-radius: 22px;
      padding: 25px 30px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.03);
    }

    .section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .section-header h3 {
      font-size: 16px;
      color: #333;
      font-weight: 700;
    }

    .section-header a {
      font-size: 12px;
      color: #e53935;
      font-weight: 500;
    }

    .program-list {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .program-card {
      border: 1px solid #f0f0f0;
      border-radius: 16px;
      padding: 15px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      transition: 0.3s;
    }

    .program-card:hover {
      border-color: #ddd;
      box-shadow: 0 4px 12px rgba(0,0,0,0.02);
    }

    .program-main {
      display: flex;
      align-items: center;
      gap: 20px;
      flex: 1;
    }

    .program-img {
      width: 100px;
      height: 65px;
      border-radius: 10px;
      object-fit: cover;
    }

    .program-details h4 {
      font-size: 14px;
      color: #333;
      font-weight: 700;
      margin-bottom: 4px;
    }

    .program-meta {
      font-size: 12px;
      color: #666;
      display: flex;
      flex-direction: column;
      gap: 2px;
    }

    .program-meta span {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .program-meta i {
      color: #999;
      width: 14px;
      text-align: center;
    }

    .badge-row {
      display: flex;
      gap: 6px;
      margin-top: 6px;
    }

    .badge {
      padding: 2px 10px;
      border-radius: 6px;
      font-size: 10px;
      font-weight: 600;
    }

    .badge-running { background: #e3f2fd; color: #1e88e5; }
    .badge-pending { background: #fff8e1; color: #ffb300; }
    .badge-approved { background: #e8f5e9; color: #2e7d32; }

    .program-stats {
      text-align: right;
      padding-right: 30px;
    }

    .stat-item-inner {
      font-size: 13px;
      color: #666;
      font-weight: 500;
    }

    .stat-item-inner i {
      color: #888;
      margin-right: 4px;
    }

    .arrow-btn {
      color: #ccc;
      font-size: 16px;
      cursor: pointer;
      transition: 0.2s;
    }

    .program-card:hover .arrow-btn {
      color: #e53935;
      transform: translateX(3px);
    }

    /* Panel bên phải (Thông tin đơn vị, lịch hiến máu,...) */
    .content-right {
      width: 320px;
      display: flex;
      flex-direction: column;
      gap: 25px;
      flex-shrink: 0;
    }

    .panel-card {
      background: white;
      border-radius: 22px;
      padding: 25px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.035);
    }

    .panel-card h3 {
      font-size: 15px;
      color: #333;
      font-weight: 700;
      margin-bottom: 20px;
      border-bottom: 1px solid #f9f9f9;
      padding-bottom: 10px;
    }

    .unit-box {
      text-align: center;
      padding-bottom: 20px;
      border-bottom: 1px dashed #eee;
      margin-bottom: 20px;
    }

    .unit-icon-box {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: #fff0f0;
      color: #e53935;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin: 0 auto 12px;
      box-shadow: 0 4px 10px rgba(229,57,53,0.05);
    }

    .unit-box h4 {
      font-size: 15px;
      color: #333;
      font-weight: 700;
    }

    .unit-tag {
      font-size: 11px;
      color: #e53935;
      font-weight: 600;
      background: #fff0f0;
      padding: 2px 10px;
      border-radius: 6px;
      display: inline-block;
      margin-top: 6px;
    }

    .unit-contact-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .unit-contact-list li {
      font-size: 12px;
      color: #666;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .unit-contact-list i {
      color: #999;
      width: 14px;
    }

    .guide-links {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .guide-link-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 0;
      border-bottom: 1px solid #f9f9f9;
      transition: 0.2s;
    }

    .guide-link-item:hover {
      padding-left: 4px;
    }

    .guide-link-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .guide-link-left i {
      font-size: 15px;
    }

    .guide-link-left span {
      font-size: 12px;
      color: #555;
      font-weight: 500;
    }

    .guide-link-left .icon-orange { color: #ff9800; }
    .guide-link-left .icon-blue { color: #1e88e5; }
    .guide-link-left .icon-green { color: #4caf50; }
    .guide-link-left .icon-yellow { color: #fdd835; }

    .guide-link-item .fa-chevron-right {
      font-size: 10px;
      color: #ccc;
    }

    /* Khối lịch hiến máu gần nhất */
    .recent-date-box {
      background: #fdfdfd;
      border: 1px solid #f0f0f0;
      border-radius: 16px;
      padding: 20px;
      text-align: center;
      margin-bottom: 15px;
    }

    .recent-date-box i {
      font-size: 26px;
      color: #e53935;
      margin-bottom: 8px;
    }

    .recent-date-box h4 {
      font-size: 20px;
      color: #e53935;
      font-weight: 700;
    }

    .recent-date-box p {
      font-size: 12px;
      color: #666;
      margin-top: 2px;
    }

    .recent-date-box span {
      font-size: 11px;
      color: #999;
      display: block;
      margin-top: 2px;
    }

    .view-detail-btn {
      width: 100%;
      background: #fff0f0;
      color: #e53935;
      border: none;
      padding: 10px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.2s;
    }

    .view-detail-btn:hover {
      background: #e53935;
      color: white;
    }

    /* ================= MODAL TẠO ĐỀ XUẤT ================= */
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(4px);
    }

    .modal-content {
      background-color: #fdfdfd;
      margin: 3% auto;
      padding: 30px;
      border: 1px solid #ffcccc;
      width: 650px;
      border-radius: 22px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      position: relative;
    }

    .close-btn {
      position: absolute;
      right: 25px;
      top: 25px;
      font-size: 20px;
      color: #888;
      cursor: pointer;
      transition: 0.2s;
    }

    .close-btn:hover {
      color: #e53935;
    }

    .modal-title {
      font-size: 20px;
      color: #e53935;
      font-weight: 700;
      margin-bottom: 20px;
      border-bottom: 1px solid #ffebeb;
      padding-bottom: 10px;
    }

    .modal-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
    }

    .form-group-full {
      grid-column: span 2;
    }

    .form-label {
      display: block;
      font-size: 12px;
      font-weight: 600;
      color: #555;
      margin-bottom: 6px;
    }

    .form-input {
      width: 100%;
      padding: 10px 12px;
      border: 1px solid #eee;
      border-radius: 10px;
      font-size: 13px;
      outline: none;
      transition: 0.2s;
      background: #fafafa;
    }

    .form-input:focus {
      border-color: #ffb3b3;
      background: white;
    }

    textarea.form-input {
      height: 80px;
      resize: vertical;
    }

    .submit-row {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 20px;
    }

    .btn-cancel {
      padding: 10px 20px;
      background: #f5f5f5;
      color: #666;
      border-radius: 10px;
      border: none;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      transition: 0.2s;
    }

    .btn-cancel:hover {
      background: #eee;
    }

    .btn-submit {
      padding: 10px 20px;
      background: #e53935;
      color: white;
      border-radius: 10px;
      border: none;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      transition: 0.2s;
    }

    .btn-submit:hover {
      background: #c62828;
    }

    /* Responsive cho màn hình vừa và nhỏ */
    @media (max-width: 1366px) {
      .container { flex-direction: column; }
      .content-right { width: 100%; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
      .unit-box { border-bottom: none; margin-bottom: 0; padding-bottom: 10px; }
    }

    @media (max-width: 1024px) {
      .sidebar { display: none; }
      .container { margin-left: 0; width: 100%; }
      .stat-cards { grid-template-columns: repeat(3, 1fr); }
      .filter-row { grid-template-columns: 1fr 1fr; }
      .search-btn { grid-column: span 2; padding: 12px; }
      .content-right { grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>

  <div class="sidebar">
    <div>
      <div class="brand-box">
        <div class="brand-logo"><i class="fa-solid fa-heart-pulse"></i></div>
        <div class="brand-name">
          <h2>BloodCare</h2>
          <p>Đơn vị tổ chức</p>
        </div>
      </div>

      <div class="menu">
        <a href="{{ route('don-vi-to-chuc.index') }}" class="{{ Request::routeIs('don-vi-to-chuc.index') ? 'active' : '' }}"><i class="fa-solid fa-house"></i> Trang chủ</a>
        <a href="{{ route('don-vi-to-chuc.chuong-trinh') }}" class="{{ Request::routeIs('don-vi-to-chuc.chuong-trinh') ? 'active' : '' }}"><i class="fa-solid fa-file-signature"></i> Đề xuất chương trình</a>
      </div>
    </div>

    <div class="logout-box">
      <form method="post" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="menu logout w-full" style="background: #fff0f0; color: #e53935 !important; font-weight: 600; border: none; cursor: pointer; display: flex; align-items: center; gap: 12px; padding: 14px 18px; border-radius: 14px; width: 100%; text-align: left; font-family: 'Poppins', sans-serif; font-size: 14px;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</button>
      </form>
    </div>
  </div>

  @yield('content')

  @yield('scripts')
</body>
</html>
