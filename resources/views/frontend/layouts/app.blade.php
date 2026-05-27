<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Hiến Máu Bệnh viện E Hà Nội')</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>

    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:'Poppins', sans-serif;
    }

    body{
      background:#fff;
      color:#222;
    }

    a{
      text-decoration:none;
    }

    .container{
      width:1200px;
      max-width:95%;
      margin:auto;
    }

    /* HEADER */

    header{
      height:80px;
      background:#fff;
      border-bottom:1px solid #eee;
      position:sticky;
      top:0;
      z-index:999;
    }

    .navbar{
      height:100%;
      display:flex;
      justify-content:space-between;
      align-items:center;
    }

    .logo{
      display:flex;
      align-items:center;
      gap:10px;
      font-size:24px;
      font-weight:700;
      color:#e53935;
    }

    .logo span{
      color:#222;
      font-size:14px;
      font-weight:500;
    }

    nav{
      display:flex;
      gap:35px;
    }

    nav a{
      color:#333;
      font-weight:500;
      transition:0.3s;
    }

    nav a:hover{
      color:#e53935;
    }

    .header-btn{
      display:flex;
      gap:15px;
    }

    .btn{
      padding:10px 22px;
      border-radius:10px;
      border:none;
      cursor:pointer;
      font-weight:600;
      transition:0.3s;
    }

    .btn-outline{
      border:2px solid #e53935;
      color:#e53935;
      background:white;
    }

    .btn-outline:hover{
      background:#fff1f1;
    }

    .btn-primary{
      background:#e53935;
      color:white;
      box-shadow:0 5px 15px rgba(229,57,53,0.3);
    }

    .btn-primary:hover{
      background:#c62828;
    }

    /* HERO */

    .hero{
      background:linear-gradient(to right,#fff5f5,#ffeaea);
      padding:90px 0;
    }

    .hero-wrapper{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:60px;
    }

    .hero-text{
      flex:1;
    }

    .hero-text small{
      color:#e53935;
      font-weight:600;
    }

    .hero-text h1{
      font-size:62px;
      line-height:1.2;
      margin:15px 0;
    }

    .hero-text h1 span{
      color:#e53935;
    }

    .hero-text p{
      color:#666;
      line-height:1.8;
      margin-bottom:35px;
    }

    .hero-buttons{
      display:flex;
      gap:20px;
    }

    .hero-card{
      width:350px;
      background:white;
      border-radius:25px;
      padding:40px;
      text-align:center;
      box-shadow:0 10px 30px rgba(0,0,0,0.08);
    }

    .blood-drop{
      width:90px;
      height:120px;
      background:#ff3b5c;
      margin:auto;
      border-radius:50% 50% 50% 50% / 60% 60% 40% 40%;
      transform:rotate(180deg);
      position:relative;
    }

    .blood-drop::after{
      content:'';
      position:absolute;
      width:40px;
      height:40px;
      background:white;
      border-radius:50%;
      top:35px;
      left:25px;
    }

    .blood-types{
      margin-top:30px;
      display:grid;
      grid-template-columns:repeat(4,1fr);
      gap:12px;
    }

    .blood-types div{
      background:#fff2f2;
      padding:10px;
      border-radius:10px;
      color:#e53935;
      font-weight:600;
    }

    /* STATS */

    .stats{
      padding:80px 0;
      background:#fff;
    }

    .stats-grid{
      display:grid;
      grid-template-columns:repeat(4,1fr);
      gap:25px;
    }

    .stat-box{
      text-align:center;
      padding:30px;
      border-radius:20px;
      background:#fff;
      box-shadow:0 5px 20px rgba(0,0,0,0.05);
    }

    .stat-box h2{
      color:#e53935;
      font-size:42px;
      margin:10px 0;
    }

    .stat-box p{
      color:#666;
    }

    /* STEPS */

    .steps{
      padding:90px 0;
      background:#fff7f7;
    }

    .section-title{
      text-align:center;
      margin-bottom:60px;
    }

    .section-title h2{
      font-size:42px;
      margin-bottom:10px;
    }

    .section-title p{
      color:#777;
    }

    .steps-grid{
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:30px;
    }

    .step-card{
      background:white;
      padding:40px 30px;
      border-radius:25px;
      text-align:center;
      position:relative;
      box-shadow:0 10px 25px rgba(0,0,0,0.05);
    }

    .step-number{
      width:45px;
      height:45px;
      background:#e53935;
      color:white;
      border-radius:50%;
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:bold;
      position:absolute;
      top:-20px;
      left:50%;
      transform:translateX(-50%);
    }

    .step-icon{
      font-size:45px;
      margin:20px 0;
    }

    .step-card h3{
      margin-bottom:15px;
    }

    .step-card p{
      color:#666;
      line-height:1.7;
    }

    /* CTA */

    .cta{
      background:#d81f26;
      color:white;
      padding:70px 0;
      text-align:center;
    }

    .cta h2{
      font-size:45px;
      margin-bottom:20px;
    }

    .cta p{
      margin-bottom:30px;
      opacity:0.9;
    }

    /* FOOTER */

    footer{
      background:#08142c;
      color:white;
      padding:70px 0 30px;
    }

    .footer-grid{
      display:grid;
      grid-template-columns:repeat(3,1fr);
      gap:40px;
      margin-bottom:40px;
    }

    .footer-col h3{
      margin-bottom:20px;
      color:#ff5a5f;
    }

    .footer-col p,
    .footer-col a{
      color:#ccc;
      line-height:2;
    }

    .copyright{
      border-top:1px solid rgba(255,255,255,0.1);
      padding-top:20px;
      text-align:center;
      color:#aaa;
    }

    @media(max-width:992px){

      .hero-wrapper{
        flex-direction:column;
      }

      .stats-grid,
      .steps-grid,
      .footer-grid{
        grid-template-columns:1fr;
      }

      nav{
        display:none;
      }

      .hero-text h1{
        font-size:42px;
      }

    }

    /* USER DROPDOWN */
    .user-dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 48px;
      background: white;
      border: 1px solid #eee;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      width: 220px;
      z-index: 1000;
      overflow: hidden;
      padding: 6px 0;
    }

    .dropdown-menu.show {
      display: block;
      animation: dropdownFadeIn 0.2s ease-out;
    }

    .dropdown-item {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 16px;
      color: #333;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none;
      transition: background 0.2s;
    }

    .dropdown-item:hover {
      background: #fdf2f2;
      color: #e53935;
    }

    .dropdown-divider {
      height: 1px;
      background: #eee;
      margin: 6px 0;
    }

    @keyframes dropdownFadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

  </style>
  @stack('styles')
</head>

<body>

  <!-- HEADER -->

  <header>

    <div class="container navbar">

      <div class="logo">
        <a href="{{ route('home') }}" style="color:#e53935; font-weight:700; font-size:24px; display:flex; align-items:center;">🩸 Hiến Máu Bệnh Viện E Hà Nội</a>
      </div>

      <nav>
        <a href="{{ route('home') }}">Trang chủ</a>
        <a href="{{ route('frontend.chuong-trinh.index') }}">Chương trình</a>
        <a href="#">Liên hệ</a>
      </nav>

      @if(session()->has('admin_user'))
        <div class="user-dropdown">
          <button class="btn btn-outline dropdown-toggle" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; border-color: #e53935; color: #e53935;">
            👤 {{ session('admin_user.name') }}
            <span style="font-size: 10px;">▼</span>
          </button>
          <div class="dropdown-menu">
            <a href="{{ route('profile.edit', ['role' => 'donor']) }}" class="dropdown-item">
              <span>✏️</span> Thông tin cá nhân
            </a>
            <a href="{{ route('frontend.lich-su-dang-ky') }}" class="dropdown-item">
              <span>📅</span> Lịch sử đăng ký
            </a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin: 0; padding: 0;">
              @csrf
              <button type="submit" class="dropdown-item" style="border: none; background: none; width: 100%; text-align: left; cursor: pointer; color: #e53935; font-weight: 700;">
                <span>🚪</span> Đăng xuất
              </button>
            </form>
          </div>
        </div>
      @else
        <div class="header-btn">
          <a href="{{ route('dang-nhap') }}" class="btn btn-outline" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Đăng nhập</a>
          <a href="{{ route('dang-ky') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Đăng ký</a>
        </div>
      @endif

    </div>

  </header>

  @yield('content')

  <!-- FOOTER -->

  <footer>

    <div class="container">

      <div class="footer-grid">

        <div class="footer-col">

          <h3>🩸 Hiến Máu Bệnh Viện E Hà Nội</h3>

          <p>
            Hệ thống quản lý hiến máu tình nguyện toàn quốc.
          </p>

        </div>

        <div class="footer-col">

          <h3>Chức năng</h3>

          <p>Chương trình</p>
          <p>Tin tức</p>
          <p>Liên hệ</p>

        </div>

        <div class="footer-col">

          <h3>Liên hệ</h3>

          <p>📍 97 Trần Cung, Hà Nội</p>
          <p>📞 1900 888 999</p>
          <p>✉ support@hienmau.vn</p>

        </div>

      </div>

      <div class="copyright">
        © 2026 Hiến Máu Bệnh Viện E Hà Nội - Hệ thống quản lý hiến máu
      </div>

    </div>

  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggle = document.querySelector('.dropdown-toggle');
      const menu = document.querySelector('.dropdown-menu');
      if (toggle && menu) {
        toggle.addEventListener('click', function (e) {
          e.stopPropagation();
          menu.classList.toggle('show');
        });
        document.addEventListener('click', function () {
          menu.classList.remove('show');
        });
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
