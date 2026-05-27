<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập - BloodCare</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>

    *{
      margin:0;
      padding:0;
      box-sizing:border-box;
      font-family:'Poppins',sans-serif;
    }

    body{
      height:100vh;
      background:
      linear-gradient(rgba(229,57,53,0.85),rgba(229,57,53,0.85)),
      url('https://images.unsplash.com/photo-1584515933487-779824d29309?q=80&w=1600&auto=format&fit=crop');

      background-size:cover;
      background-position:center;

      display:flex;
      justify-content:center;
      align-items:center;
      overflow:hidden;
    }

    .login-container{
      width:1000px;
      max-width:95%;

      background:white;
      border-radius:35px;
      overflow:hidden;

      display:grid;
      grid-template-columns:1fr 1fr;

      box-shadow:0 15px 40px rgba(0,0,0,0.2);
    }

    /* LEFT */

    .login-left{
      background:linear-gradient(135deg,#ff5f6d,#e53935);
      color:white;
      padding:70px 50px;

      display:flex;
      flex-direction:column;
      justify-content:center;
      position:relative;
    }

    .login-left h1{
      font-size:52px;
      line-height:1.2;
      margin-bottom:25px;
    }

    .login-left p{
      font-size:17px;
      line-height:1.8;
      opacity:0.95;
    }

    .blood-icon{
      margin-top:40px;
      width:120px;
      height:150px;
      background:white;
      border-radius:50% 50% 50% 50% / 60% 60% 40% 40%;
      transform:rotate(180deg);
      position:relative;
    }

    .blood-icon::after{
      content:'❤';
      position:absolute;
      top:38px;
      left:38px;
      color:#e53935;
      font-size:38px;
      transform:rotate(180deg);
    }

    /* RIGHT */

    .login-right{
      padding:70px 55px;

      display:flex;
      flex-direction:column;
      justify-content:center;
    }

    .login-right h2{
      font-size:40px;
      margin-bottom:10px;
      color:#222;
    }

    .login-right .subtitle{
      color:#777;
      margin-bottom:35px;
      line-height:1.7;
    }

    .form-group{
      margin-bottom:22px;
    }

    .form-group label{
      display:block;
      margin-bottom:10px;
      font-weight:600;
      color:#333;
    }

    .form-group input{
      width:100%;
      padding:16px;
      border:1px solid #ddd;
      border-radius:14px;
      outline:none;
      font-size:15px;
      transition:0.3s;
    }

    .form-group input:focus{
      border-color:#e53935;
      box-shadow:0 0 0 4px rgba(229,57,53,0.12);
    }

    .options{
      display:flex;
      justify-content:space-between;
      align-items:center;
      margin-bottom:28px;
      font-size:14px;
    }

    .options a{
      color:#e53935;
      font-weight:500;
    }

    .remember{
      display:flex;
      align-items:center;
      gap:8px;
      color:#555;
    }

    .login-btn{
      width:100%;
      padding:16px;
      border:none;
      border-radius:14px;
      background:linear-gradient(to right,#ff5f6d,#e53935);
      color:white;
      font-size:16px;
      font-weight:600;
      cursor:pointer;
      transition:0.3s;
      box-shadow:0 10px 20px rgba(229,57,53,0.25);
    }

    .login-btn:hover{
      transform:translateY(-2px);
      opacity:0.95;
    }

    .register{
      margin-top:30px;
      text-align:center;
      color:#666;
      font-size:15px;
    }

    .register a{
      color:#e53935;
      font-weight:600;
    }

    /* RESPONSIVE */

    @media(max-width:900px){

      .login-container{
        grid-template-columns:1fr;
      }

      .login-left{
        display:none;
      }

      .login-right{
        padding:50px 30px;
      }

      .login-right h2{
        font-size:32px;
      }

    }

  </style>

</head>

<body>

  <div class="login-container">

    <!-- LEFT -->

    <div class="login-left">

      <h1>
        Chào mừng trở lại!
      </h1>

      <p>
        Đăng nhập để tham gia các chương trình hiến máu,
        cập nhật thông tin mới nhất và lan tỏa yêu thương
        đến cộng đồng.
      </p>

      <div class="blood-icon"></div>

    </div>

    <!-- RIGHT -->

    <div class="login-right">

      <h2>Đăng nhập</h2>

      <p class="subtitle">
        Vui lòng đăng nhập để tiếp tục sử dụng hệ thống.
      </p>

      @if ($errors->any())
        <div style="margin-bottom: 20px; padding: 12px 14px; border-radius: 12px; background-color: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; font-size: 14px; font-weight: 600;">
          {{ $errors->first() }}
        </div>
      @endif

      @if (session('success'))
        <div style="margin-bottom: 20px; padding: 12px 14px; border-radius: 12px; background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; font-size: 14px; font-weight: 600;">
          {{ session('success') }}
        </div>
      @endif

      <form method="POST" action="{{ route('dang-nhap.submit') }}">
        @csrf

        <div class="form-group">
          <label>Email hoặc Số điện thoại</label>
          <input type="text" name="email_or_phone" value="{{ old('email_or_phone') }}" placeholder="Nhập email hoặc số điện thoại..." required>
        </div>

        <div class="form-group">
          <label>Mật khẩu</label>
          <input type="password" name="password" placeholder="Nhập mật khẩu..." required>
        </div>

        <div class="options">

          <label class="remember">
            <input type="checkbox">
            Ghi nhớ tài khoản
          </label>

          <a href="#">
            Quên mật khẩu?
          </a>

        </div>

        <button type="submit" class="login-btn">
          Đăng nhập
        </button>

      </form>

      <div class="register">
        Chưa có tài khoản?
        <a href="{{ route('dang-ky') }}">Đăng ký ngay</a>
      </div>

    </div>

  </div>

</body>
</html>