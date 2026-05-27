@extends('frontend.layouts.app')

@section('title', 'Hỏi - Đáp - BloodCare')

@push('styles')
<style>
    /* BANNER */
    .banner{
      height:320px;
      background:
      linear-gradient(rgba(229,57,53,0.82),rgba(229,57,53,0.82)),
      url('https://images.unsplash.com/photo-1615461066841-6116e61058f4?q=80&w=1600&auto=format&fit=crop');
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
      font-size:58px;
      margin-bottom:15px;
    }

    .banner p{
      font-size:18px;
      opacity:0.95;
    }

    /* FAQ */
    .faq-section{
      width:1100px;
      max-width:100%;
      margin:auto;
      padding:90px 20px;
    }

    .section-title{
      text-align:center;
      margin-bottom:60px;
    }

    .section-title h2{
      font-size:42px;
      margin-bottom:15px;
    }

    .section-title p{
      color:#777;
      line-height:1.8;
      max-width:700px;
      margin:auto;
    }

    .faq-container{
      display:flex;
      flex-direction:column;
      gap:22px;
    }

    .faq-item{
      background:white;
      border-radius:22px;
      overflow:hidden;
      border:1px solid #eee;
      box-shadow:0 8px 20px rgba(0,0,0,0.05);
    }

    .faq-question{
      padding:26px 30px;
      display:flex;
      justify-content:space-between;
      align-items:center;
      cursor:pointer;
    }

    .faq-question h3{
      font-size:23px;
      color:#1f4ea3;
      font-weight:600;
    }

    .faq-question span{
      font-size:30px;
      color:#777;
      transition:0.3s;
    }

    .faq-answer{
      padding:0 30px 28px;
      color:#555;
      line-height:1.9;
      display:none;
    }

    .faq-item.active .faq-answer{
      display:block;
    }

    .faq-item.active .faq-question span{
      transform:rotate(180deg);
    }

    @media(max-width:992px){
      .banner h1{
        font-size:42px;
      }
      .section-title h2{
        font-size:34px;
      }
      .faq-question h3{
        font-size:18px;
      }
    }
</style>
@endpush

@section('content')
  <!-- BANNER -->
  <section class="banner">
    <div>
      <h1>Hỏi - Đáp hiến máu</h1>
      <p>
        Những câu hỏi thường gặp dành cho người tham gia hiến máu tình nguyện.
      </p>
    </div>
  </section>

  <!-- FAQ -->
  <section class="faq-section">
    <div class="section-title">
      <h2>Lưu ý quan trọng</h2>
      <p>
        Tìm hiểu những thông tin cần thiết trước khi tham gia hiến máu.
      </p>
    </div>

    <div class="faq-container">
      <!-- ITEM -->
      <div class="faq-item active">
        <div class="faq-question">
          <h3>1. Ai có thể tham gia hiến máu?</h3>
          <span>⌄</span>
        </div>
        <div class="faq-answer">
          <p>
            - Tất cả mọi người từ 18 - 60 tuổi, có sức khỏe tốt và tự nguyện hiến máu.
          </p>
          <p>
            - Cân nặng tối thiểu 45kg.
          </p>
          <p>
            - Không mắc các bệnh lây truyền qua đường máu.
          </p>
        </div>
      </div>

      <!-- ITEM -->
      <div class="faq-item">
        <div class="faq-question">
          <h3>2. Ai là người không nên hiến máu?</h3>
          <span>⌄</span>
        </div>
        <div class="faq-answer">
          <p>
            Người mắc bệnh tim mạch, huyết áp, HIV,
            viêm gan B hoặc đang có vấn đề sức khỏe không ổn định.
          </p>
        </div>
      </div>

      <!-- ITEM -->
      <div class="faq-item">
        <div class="faq-question">
          <h3>3. Hiến máu có ảnh hưởng sức khỏe không?</h3>
          <span>⌄</span>
        </div>
        <div class="faq-answer">
          <p>
            Hiến máu đúng quy trình hoàn toàn không gây hại sức khỏe.
            Lượng máu đã hiến sẽ được cơ thể tái tạo sau một thời gian ngắn.
          </p>
        </div>
      </div>

      <!-- ITEM -->
      <div class="faq-item">
        <div class="faq-question">
          <h3>4. Trước khi hiến máu cần chuẩn bị gì?</h3>
          <span>⌄</span>
        </div>
        <div class="faq-answer">
          <p>
            Ngủ đủ giấc, ăn nhẹ trước khi hiến máu,
            không uống rượu bia và mang theo CCCD/CMND.
          </p>
        </div>
      </div>

      <!-- ITEM -->
      <div class="faq-item">
        <div class="faq-question">
          <h3>5. Sau khi hiến máu nên làm gì?</h3>
          <span>⌄</span>
        </div>
        <div class="faq-answer">
          <p>
            Nghỉ ngơi, uống nhiều nước,
            tránh vận động mạnh trong vài giờ đầu sau hiến máu.
          </p>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
  <script>
    const faqItems = document.querySelectorAll(".faq-item");
    faqItems.forEach(item => {
      item.querySelector(".faq-question").onclick = () => {
        item.classList.toggle("active");
      };
    });
  </script>
@endpush