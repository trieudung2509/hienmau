<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Đăng ký tài khoản - Hiến Máu Bệnh Viện E</title>

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
      min-height:100vh;
      background:linear-gradient(to right,#fff6f6,#ffeaea);
      display:flex;
      justify-content:center;
      align-items:center;
      padding:40px 20px;
    }

    .container{
      width:1200px;
      max-width:100%;
      background:white;
      border-radius:30px;
      overflow:hidden;
      display:grid;
      grid-template-columns:1fr 1.1fr;
      box-shadow:0 15px 40px rgba(0,0,0,0.08);
    }

    /* LEFT */
    .left{
      background:linear-gradient(135deg,#ff4b5c,#e53935);
      color:white;
      padding:60px;
      position:relative;
      overflow:hidden;
      display:flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .left::before{
      content:'';
      width:350px;
      height:350px;
      background:rgba(255,255,255,0.08);
      border-radius:50%;
      position:absolute;
      top:-120px;
      right:-120px;
    }

    .logo{
      font-size:26px;
      font-weight:700;
      margin-bottom:50px;
      position:relative;
      z-index:2;
    }
    
    .logo a {
      color: white;
      text-decoration: none;
    }

    .left-content {
      position:relative;
      z-index:2;
      margin-top: auto;
      margin-bottom: auto;
    }

    .left h1{
      font-size:42px;
      line-height:1.3;
      margin-bottom:25px;
    }

    .left p{
      line-height:1.9;
      opacity:0.95;
    }

    /* RIGHT */
    .right{
      padding:50px;
      max-height: 100vh;
      overflow-y: auto;
    }

    .top-login{
      text-align:right;
      margin-bottom:20px;
      font-size: 14px;
      font-weight: 500;
      color: #555;
    }

    .top-login a{
      color:#e53935;
      text-decoration:none;
      font-weight:600;
      margin-left: 5px;
    }

    .form-title h2{
      font-size:36px;
      margin-bottom:10px;
      color: #1e293b;
      font-weight: 700;
    }

    .form-title p{
      color:#777;
      margin-bottom:30px;
      font-size: 14px;
    }

    /* ROLE */
    .role-box{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:20px;
      margin-bottom:35px;
    }

    .role{
      border:2px solid #f1f5f9;
      border-radius:20px;
      padding:25px 20px;
      cursor:pointer;
      transition:all 0.3s ease;
      text-align:center;
      background: #fafafa;
    }

    .role.active{
      border-color:#ff4b5c;
      background:#fff5f5;
      box-shadow: 0 10px 20px rgba(255, 75, 92, 0.08);
    }

    .role-icon{
      font-size:40px;
      margin-bottom:12px;
    }

    .role h3{
      margin-bottom:8px;
      font-size: 16px;
      font-weight: 700;
      color: #1e293b;
    }

    .role p{
      color:#64748b;
      font-size:12px;
      line-height:1.6;
    }

    /* FORM */
    .row{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:20px;
    }

    .form-group{
      margin-bottom:20px;
    }

    .form-group label{
      display:block;
      margin-bottom:8px;
      font-weight:600;
      font-size: 14px;
      color: #334155;
    }

    .form-group input,
    .form-group select,
    .form-group textarea{
      width:100%;
      padding:14px;
      border:1px solid #cbd5e1;
      border-radius:12px;
      outline:none;
      font-size:14px;
      transition:all 0.3s;
      background: #f8fafc;
    }

    .form-group textarea{
      resize:none;
      height:110px;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus{
      border-color:#ff4b5c;
      background: #fff;
      box-shadow:0 0 0 4px rgba(255,75,92,0.1);
    }

    .form-group input.is-invalid,
    .form-group select.is-invalid,
    .form-group textarea.is-invalid{
      border-color: #ef4444;
      background-color: #fef2f2;
    }

    .invalid-feedback{
      color: #ef4444;
      font-size: 12px;
      font-weight: 600;
      margin-top: 6px;
      display: block;
    }

    .submit-btn{
      width:100%;
      background:linear-gradient(to right,#ff4b5c,#e53935);
      color:white;
      border:none;
      padding:16px;
      border-radius:14px;
      font-size:16px;
      font-weight:600;
      cursor:pointer;
      margin-top:10px;
      box-shadow: 0 4px 12px rgba(229, 57, 53, 0.2);
      transition: all 0.3s ease;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 18px rgba(229, 57, 53, 0.35);
    }

    .note{
      text-align:center;
      color:#64748b;
      margin-top:20px;
      line-height:1.7;
      font-size: 13px;
    }

    .note span{
      color:#e53935;
      font-weight:600;
    }

    .hidden{
      display:none;
    }

    /* ERROR BANNER */
    .alert-banner {
      background-color: #fef2f2;
      border: 1px solid rgba(239, 68, 68, 0.2);
      border-radius: 12px;
      padding: 12px 16px;
      color: #991b1b;
      display: flex;
      gap: 10px;
      align-items: flex-start;
      margin-bottom: 24px;
    }

    @media(max-width:992px){
      .container{
        grid-template-columns:1fr;
      }

      .left{
        display:none;
      }

      .row,
      .role-box{
        grid-template-columns:1fr;
      }

      .right{
        padding:35px 25px;
      }
    }
  </style>
</head>

<body>

  <div class="container">

    <!-- LEFT -->
    <div class="left">
      <div class="logo">
        <a href="{{ route('home') }}">🩸 Hiến Máu Bệnh Viện E</a>
      </div>

      <div class="left-content">
        <h1>
          Chào mừng bạn đến với Hiến Máu tình nguyện
        </h1>
        <p style="margin-top: 15px;">
          Tham gia cộng đồng hiến máu tình nguyện và kết nối với các chương trình hiến máu trên toàn quốc. Hành động nhỏ, ý nghĩa lớn.
        </p>
      </div>
      
      <div style="font-size: 12px; opacity: 0.7; z-index: 2;">
        © 2026 Bệnh Viện E Hà Nội.
      </div>
    </div>

    <!-- RIGHT -->
    <div class="right">

      <div class="top-login">
        Đã có tài khoản?
        <a href="{{ route('dang-nhap') }}">Đăng nhập</a>
      </div>

      <div class="form-title">
        <h2>Đăng ký tài khoản</h2>
        <p>
          Vui lòng chọn vai trò để tiếp tục đăng ký.
        </p>
      </div>

      @if ($errors->has('error'))
        <div class="alert-banner">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px; color: #ef4444;">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
          </svg>
          <span style="font-size: 13px; font-weight: 600;">{{ $errors->first('error') }}</span>
        </div>
      @endif

      <!-- ROLE -->
      <div class="role-box">
        <div class="role {{ old('role_type', 'donor') === 'donor' ? 'active' : '' }}" id="donorRole">
          <div class="role-icon">🧑</div>
          <h3>Người hiến máu</h3>
          <p>
            Đăng ký tham gia hiến máu và các chương trình thiện nguyện.
          </p>
        </div>

        <div class="role {{ old('role_type') === 'organization' ? 'active' : '' }}" id="orgRole">
          <div class="role-icon">🏢</div>
          <h3>Đơn vị tổ chức</h3>
          <p>
            Đăng ký để tổ chức và quản lý chương trình hiến máu.
          </p>
        </div>
      </div>

      <!-- FORM -->
      <form action="{{ route('dang-ky.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="role_type" id="role_type" value="{{ old('role_type', 'donor') }}">

        <!-- DONOR FORM -->
        <div id="donorForm" class="{{ old('role_type', 'donor') === 'donor' ? '' : 'hidden' }}">
          <div class="row">
            <div class="form-group">
              <label for="HoTen">Họ và tên <span style="color:#e53935;">*</span></label>
              <input type="text" id="HoTen" name="HoTen" value="{{ old('HoTen') }}" class="@error('HoTen') is-invalid @enderror" placeholder="Nhập họ và tên...">
              @error('HoTen')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="SoDienThoai">Số điện thoại <span style="color:#e53935;">*</span></label>
              <input type="text" id="SoDienThoai" name="SoDienThoai" value="{{ old('SoDienThoai') }}" class="@error('SoDienThoai') is-invalid @enderror" placeholder="Ví dụ: 0912345678">
              @error('SoDienThoai')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-group">
            <label for="Email">Email <span style="color:#e53935;">*</span></label>
            <input type="email" id="Email" name="Email" value="{{ old('Email') }}" class="@error('Email') is-invalid @enderror" placeholder="example@gmail.com">
            @error('Email')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="row">
            <div class="form-group">
              <label for="NgaySinh">Ngày sinh <span style="color:#e53935;">*</span></label>
              <input type="date" id="NgaySinh" name="NgaySinh" value="{{ old('NgaySinh') }}" class="@error('NgaySinh') is-invalid @enderror">
              @error('NgaySinh')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="GioiTinh">Giới tính <span style="color:#e53935;">*</span></label>
              <select id="GioiTinh" name="GioiTinh" class="@error('GioiTinh') is-invalid @enderror">
                <option value="">Chọn giới tính</option>
                <option value="Nam" {{ old('GioiTinh') === 'Nam' ? 'selected' : '' }}>Nam</option>
                <option value="Nữ" {{ old('GioiTinh') === 'Nữ' ? 'selected' : '' }}>Nữ</option>
                <option value="Khác" {{ old('GioiTinh') === 'Khác' ? 'selected' : '' }}>Khác</option>
              </select>
              @error('GioiTinh')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <label for="MatKhau">Mật khẩu <span style="color:#e53935;">*</span></label>
              <input type="password" id="MatKhau" name="MatKhau" class="@error('MatKhau') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự...">
              @error('MatKhau')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="MatKhau_confirmation">Xác nhận mật khẩu <span style="color:#e53935;">*</span></label>
              <input type="password" id="MatKhau_confirmation" name="MatKhau_confirmation" placeholder="Nhập lại mật khẩu...">
            </div>
          </div>
        </div>

        <!-- ORGANIZATION FORM -->
        <div id="organizationForm" class="{{ old('role_type') === 'organization' ? '' : 'hidden' }}">
          <div class="form-group">
            <label for="TenDonVi">Tên tổ chức / Đơn vị <span style="color:#e53935;">*</span></label>
            <input type="text" id="TenDonVi" name="TenDonVi" value="{{ old('TenDonVi') }}" class="@error('TenDonVi') is-invalid @enderror" placeholder="Nhập tên tổ chức...">
            @error('TenDonVi')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="row">
            <div class="form-group">
              <label for="OrgEmail">Email tổ chức <span style="color:#e53935;">*</span></label>
              <input type="email" id="OrgEmail" name="Email" value="{{ old('role_type') === 'organization' ? old('Email') : '' }}" class="@error('Email') is-invalid @enderror" placeholder="email@tochuc.org">
              @error('Email')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="OrgSoDienThoai">Số điện thoại <span style="color:#e53935;">*</span></label>
              <input type="text" id="OrgSoDienThoai" name="SoDienThoai" value="{{ old('role_type') === 'organization' ? old('SoDienThoai') : '' }}" class="@error('SoDienThoai') is-invalid @enderror" placeholder="Ví dụ: 02438523798">
              @error('SoDienThoai')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="row">
            <div class="form-group">
              <label for="NguoiDaiDien">Người đại diện <span style="color:#e53935;">*</span></label>
              <input type="text" id="NguoiDaiDien" name="NguoiDaiDien" value="{{ old('NguoiDaiDien') }}" class="@error('NguoiDaiDien') is-invalid @enderror" placeholder="Họ tên người đại diện...">
              @error('NguoiDaiDien')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="Loai">Loại tổ chức <span style="color:#e53935;">*</span></label>
              <select id="Loai" name="Loai" class="@error('Loai') is-invalid @enderror">
                <option value="">Chọn loại tổ chức</option>
                <option value="Trường học" {{ old('Loai') === 'Trường học' ? 'selected' : '' }}>Trường học</option>
                <option value="Bệnh viện" {{ old('Loai') === 'Bệnh viện' ? 'selected' : '' }}>Bệnh viện</option>
                <option value="Doanh nghiệp" {{ old('Loai') === 'Doanh nghiệp' ? 'selected' : '' }}>Doanh nghiệp</option>
                <option value="Khác" {{ old('Loai') === 'Khác' ? 'selected' : '' }}>Khác</option>
              </select>
              @error('Loai')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-group">
            <label for="DiaChi">Địa chỉ tổ chức <span style="color:#e53935;">*</span></label>
            <input type="text" id="DiaChi" name="DiaChi" value="{{ old('DiaChi') }}" class="@error('DiaChi') is-invalid @enderror" placeholder="Nhập địa chỉ đầy đủ...">
            @error('DiaChi')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="MoTa">Mô tả ngắn về tổ chức</label>
            <textarea id="MoTa" name="MoTa" placeholder="Giới thiệu ngắn gọn về chức năng/hoạt động của tổ chức...">{{ old('MoTa') }}</textarea>
            @error('MoTa')
              <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>

          <div class="row">
            <div class="form-group">
              <label for="OrgMatKhau">Mật khẩu <span style="color:#e53935;">*</span></label>
              <input type="password" id="OrgMatKhau" name="MatKhau" class="@error('MatKhau') is-invalid @enderror" placeholder="Tối thiểu 6 ký tự...">
              @error('MatKhau')
                <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label for="OrgMatKhau_confirmation">Xác nhận mật khẩu <span style="color:#e53935;">*</span></label>
              <input type="password" id="OrgMatKhau_confirmation" name="MatKhau_confirmation" placeholder="Nhập lại mật khẩu...">
            </div>
          </div>
        </div>

        <button type="submit" class="submit-btn">
          Đăng ký tài khoản
        </button>
      </form>

      <div class="note">
        Với tài khoản tổ chức,
        <span>admin sẽ xét duyệt trước khi sử dụng.</span>
      </div>

    </div>

  </div>

  <script>
    const donorRole = document.getElementById("donorRole");
    const orgRole = document.getElementById("orgRole");

    const donorForm = document.getElementById("donorForm");
    const organizationForm = document.getElementById("organizationForm");
    const roleTypeInput = document.getElementById("role_type");

    donorRole.onclick = () => {
      donorRole.classList.add("active");
      orgRole.classList.remove("active");

      donorForm.classList.remove("hidden");
      organizationForm.classList.add("hidden");
      roleTypeInput.value = "donor";
    }

    orgRole.onclick = () => {
      orgRole.classList.add("active");
      donorRole.classList.remove("active");

      organizationForm.classList.remove("hidden");
      donorForm.classList.add("hidden");
      roleTypeInput.value = "organization";
    }
  </script>

</body>
</html>
