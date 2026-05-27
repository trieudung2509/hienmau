@extends('frontend.layouts.app')

@section('title', 'Liên hệ - BloodCare')

@push('styles')
<style>
    /* BANNER */
    .banner{
      height:350px;
      background:
      linear-gradient(rgba(229,57,53,0.85),rgba(229,57,53,0.85)),
      url('https://images.unsplash.com/photo-1516549655169-df83a0774514?q=80&w=1600&auto=format&fit=crop');
      background-size:cover;
      background-position:center;
      display:flex;
      justify-content:center;
      align-items:center;
      text-align:center;
      color:white;
      padding:20px;
    }

    .banner h1{
      font-size:56px;
      margin-bottom:15px;
    }

    .banner p{
      font-size:18px;
      opacity:0.95;
    }

    /* CONTACT */
    .contact-section{
      width:1200px;
      max-width:100%;
      margin:auto;
      padding:90px 20px;
    }

    .contact-wrapper{
      display:grid;
      grid-template-columns:1fr 1.2fr;
      gap:35px;
    }

    /* LEFT */
    .contact-info{
      background:white;
      border-radius:30px;
      padding:45px;
      box-shadow:0 10px 25px rgba(0,0,0,0.06);
    }

    .contact-info h2{
      font-size:34px;
      margin-bottom:35px;
    }

    .info-item{
      display:flex;
      gap:18px;
      margin-bottom:30px;
      align-items:flex-start;
    }

    .info-icon{
      width:60px;
      height:60px;
      background:#ffeaea;
      border-radius:18px;
      display:flex;
      justify-content:center;
      align-items:center;
      font-size:28px;
      color:#e53935;
      flex-shrink:0;
    }

    .info-text h3{
      margin-bottom:8px;
      font-size:20px;
    }

    .info-text p{
      color:#666;
      line-height:1.8;
    }

    /* FORM */
    .contact-form{
      background:white;
      border-radius:30px;
      padding:45px;
      box-shadow:0 10px 25px rgba(0,0,0,0.06);
    }

    .contact-form h2{
      font-size:34px;
      margin-bottom:35px;
    }

    .row{
      display:grid;
      grid-template-columns:1fr 1fr;
      gap:20px;
    }

    .form-group{
      margin-bottom:22px;
    }

    .form-group label{
      display:block;
      margin-bottom:10px;
      font-weight:600;
    }

    .form-group input,
    .form-group textarea{
      width:100%;
      padding:15px;
      border:1px solid #ddd;
      border-radius:14px;
      outline:none;
      transition:0.3s;
      font-size:15px;
    }

    .form-group textarea{
      resize:none;
      height:150px;
    }

    .form-group input:focus,
    .form-group textarea:focus{
      border-color:#e53935;
      box-shadow:0 0 0 4px rgba(229,57,53,0.1);
    }

    .submit-btn{
      width:100%;
      border:none;
      background:linear-gradient(to right,#ff4b5c,#e53935);
      color:white;
      padding:17px;
      border-radius:15px;
      font-size:17px;
      font-weight:600;
      cursor:pointer;
      transition:0.3s;
    }

    .submit-btn:hover{
      opacity:0.92;
    }

    /* MAP */
    .map{
      margin-top:70px;
      border-radius:30px;
      overflow:hidden;
      box-shadow:0 10px 25px rgba(0,0,0,0.06);
    }

    iframe{
      width:100%;
      height:450px;
      border:none;
    }

    /* RESPONSIVE */
    @media(max-width:992px){
      .contact-wrapper{
        grid-template-columns:1fr;
      }
      .row{
        grid-template-columns:1fr;
      }
      .banner h1{
        font-size:42px;
      }
      .contact-form,
      .contact-info{
        padding:35px 25px;
      }
    }
</style>
@endpush

@section('content')
  <!-- BANNER -->
  <section class="banner">
    <div>
      <h1>Liên hệ với chúng tôi</h1>
      <p>
        Bệnh Viện luôn sẵn sàng hỗ trợ và giải đáp mọi thắc mắc của bạn.
      </p>
    </div>
  </section>

  <!-- CONTACT -->
  <section class="contact-section">
    <div class="contact-wrapper">
      <!-- LEFT -->
      <div class="contact-info">
        <h2>Thông tin liên hệ</h2>

        <div class="info-item">
          <div class="info-icon">📍</div>
          <div class="info-text">
            <h3>Địa chỉ</h3>
            <p>87-89 Trần Cung, Cầu Giấy, Hà Nội</p>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon">📞</div>
          <div class="info-text">
            <h3>Hotline</h3>
            <p>081.846.7686 hoặc 086.889.1318</p>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon">✉️</div>
          <div class="info-text">
            <h3>Email</h3>
            <p>bvetuvanonline@gmail.com</p>
          </div>
        </div>

        <div class="info-item">
          <div class="info-icon">🕒</div>
          <div class="info-text">
            <h3>Giờ làm việc</h3>
            <p>
              Thứ 2 - Chủ nhật <br>
              07:00 - 16:30
            </p>
          </div>
        </div>
      </div>

      <!-- RIGHT -->
      <div class="contact-form">
        <h2>Gửi tin nhắn</h2>

        @if(session('success'))
          <div style="background-color: #d1fae5; color: #065f46; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-weight: 600;">
            ✅ {{ session('success') }}
          </div>
        @endif

        @if($errors->any())
          <div style="background-color: #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 20px; font-weight: 600;">
            ⚠️ Vui lòng kiểm tra lại thông tin gửi đi.
          </div>
        @endif

        <form action="{{ route('frontend.lien-he.submit') }}" method="POST">
          @csrf

          <div class="row">
            <div class="form-group">
              <label>Họ và tên <span style="color: #e53935;">*</span></label>
              <input type="text" name="HoTen" value="{{ old('HoTen') }}" placeholder="Nhập họ tên" style="@error('HoTen') border-color: #ef4444; @enderror">
              @error('HoTen')
                <span style="color: #ef4444; font-size: 13px; font-weight: 500; margin-top: 5px; display: block;">{{ $message }}</span>
              @enderror
            </div>

            <div class="form-group">
              <label>Email <span style="color: #e53935;">*</span></label>
              <input type="email" name="Email" value="{{ old('Email') }}" placeholder="Nhập email" style="@error('Email') border-color: #ef4444; @enderror">
              @error('Email')
                <span style="color: #ef4444; font-size: 13px; font-weight: 500; margin-top: 5px; display: block;">{{ $message }}</span>
              @enderror
            </div>
          </div>

          <div class="form-group">
            <label>Số điện thoại <span style="color: #e53935;">*</span></label>
            <input type="text" name="SoDienThoai" value="{{ old('SoDienThoai') }}" placeholder="Nhập số điện thoại" style="@error('SoDienThoai') border-color: #ef4444; @enderror">
            @error('SoDienThoai')
              <span style="color: #ef4444; font-size: 13px; font-weight: 500; margin-top: 5px; display: block;">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label>Tiêu đề <span style="color: #e53935;">*</span></label>
            <input type="text" name="TieuDe" value="{{ old('TieuDe') }}" placeholder="Nhập tiêu đề" style="@error('TieuDe') border-color: #ef4444; @enderror">
            @error('TieuDe')
              <span style="color: #ef4444; font-size: 13px; font-weight: 500; margin-top: 5px; display: block;">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label>Nội dung <span style="color: #e53935;">*</span></label>
            <textarea name="NoiDung" placeholder="Nhập nội dung liên hệ..." style="@error('NoiDung') border-color: #ef4444; @enderror">{{ old('NoiDung') }}</textarea>
            @error('NoiDung')
              <span style="color: #ef4444; font-size: 13px; font-weight: 500; margin-top: 5px; display: block;">{{ $message }}</span>
            @enderror
          </div>

          <button type="submit" class="submit-btn">
            Gửi liên hệ
          </button>
        </form>
      </div>
    </div>

    <!-- MAP -->
    <div class="map">
      <iframe
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.5586214433606!2d105.78627831083604!3d21.050339580523524!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135ab2bc3ed7b2f%3A0x8479b93f543c3f15!2zQuG7h25oIHZp4buHbiBF!5e0!3m2!1svi!2s!4v1779708573951!5m2!1svi!2s"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </section>
@endsection