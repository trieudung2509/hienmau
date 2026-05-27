@extends('frontend.layouts.app')

@section('title', 'Hiến Máu Bệnh viện E Hà Nội')

@section('content')
  <!-- HERO -->

  <section class="hero">

    <div class="container hero-wrapper">

      <div class="hero-text">

        <small>Hiến máu vì cộng đồng</small>

        <h1>
          Hiến máu hôm nay <br>
          <span>Sẻ chia sự sống</span>
        </h1>

        <p>
          Mỗi giọt máu cho đi, một cuộc đời ở lại.
          Tham gia hiến máu giúp đỡ những người đang cần bạn.
        </p>

        <div class="hero-buttons">
          <a href="{{ route('dang-ky') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none;">Đăng ký hiến máu</a>
          <button class="btn btn-outline">Tìm hiểu thêm</button>
        </div>

      </div>

      <div class="hero-card">

        <div class="blood-drop"></div>

        <div class="blood-types">
          <div>A+</div>
          <div>A-</div>
          <div>B+</div>
          <div>B-</div>
          <div>AB+</div>
          <div>AB-</div>
          <div>O+</div>
          <div>O-</div>
        </div>

      </div>

    </div>

  </section>

  <!-- STATS -->

  <section class="stats">

    <div class="container">

      <div class="stats-grid">

        <div class="stat-box">
          <h2>12,450+</h2>
          <p>Người hiến máu</p>
        </div>

        <div class="stat-box">
          <h2>860+</h2>
          <p>Chương trình</p>
        </div>

        <div class="stat-box">
          <h2>18,230+</h2>
          <p>Đơn vị máu đã hiến</p>
        </div>

        <div class="stat-box">
          <h2>95%</h2>
          <p>Tỷ lệ hài lòng</p>
        </div>

      </div>

    </div>

  </section>

  <!-- STEPS -->

  <section class="steps">

    <div class="container">

      <div class="section-title">
        <h2>Hiến máu dễ dàng</h2>
        <p>Chỉ với 3 bước đơn giản, bạn có thể sẻ chia sự sống.</p>
      </div>

      <div class="steps-grid">

        <div class="step-card">

          <div class="step-number">1</div>

          <div class="step-icon">👤</div>

          <h3>Đăng ký</h3>

          <p>
            Tạo tài khoản và điền thông tin hiến máu.
          </p>

        </div>

        <div class="step-card">

          <div class="step-number">2</div>

          <div class="step-icon">📅</div>

          <h3>Tham gia</h3>

          <p>
            Chọn chương trình phù hợp và đặt lịch hẹn.
          </p>

        </div>

        <div class="step-card">

          <div class="step-number">3</div>

          <div class="step-icon">🩸</div>

          <h3>Hiến máu</h3>

          <p>
            Góp phần cứu sống nhiều người cần máu.
          </p>

        </div>

      </div>

    </div>

  </section>

  <!-- CTA -->

  <section class="cta">

    <div class="container">

      <h2>Sẵn sàng bắt đầu?</h2>

      <p>
        Đăng ký chỉ mất 2 phút. Hành động nhỏ, ý nghĩa lớn.
      </p>

      <button class="btn btn-outline" style="background:white;">
        Tham gia ngay
      </button>

    </div>

  </section>
@endsection