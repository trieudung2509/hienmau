@extends('frontend.layouts.app')

@section('title', 'Lịch sử đăng ký hiến máu')

@push('styles')
<style>
    .history-page {
        padding: 60px 0;
        background-color: #f8fafc;
        min-height: 80vh;
    }

    .history-header {
        margin-bottom: 32px;
        background: linear-gradient(135deg, #fff5f5 0%, #fff0f0 100%);
        border: 1px solid #fee2e2;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
        height: 140px;
        z-index: 0;
    }

    .header-text h1 {
        font-family: var(--font-heading, 'Inter', sans-serif);
        font-size: 28px;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .header-text p {
        color: #64748b;
        font-size: 15px;
        font-weight: 500;
    }

    .header-stats {
        display: flex;
        gap: 16px;
    }

    .stat-pill {
        background-color: #ffffff;
        border: 1px solid #fee2e2;
        border-radius: 12px;
        padding: 12px 20px;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }

    .stat-pill .value {
        font-size: 20px;
        font-weight: 800;
        color: #e53935;
    }

    .stat-pill .label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 2px;
    }

    .history-card {
        background-color: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 32px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .history-table-wrapper {
        overflow-x: auto;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 14px;
    }

    .history-table th, .history-table td {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        vertical-align: middle;
    }

    .history-table th {
        background-color: #f8fafc;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
    }

    .history-table tr:last-child td {
        border-bottom: none;
    }

    .history-table tr:hover td {
        background-color: #fff8f8;
    }

    .history-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        border-radius: 20px;
    }

    .btn-cancel {
        background-color: #ffffff;
        color: #dc2626;
        border: 1px solid #fca5a5;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-cancel:hover {
        background-color: #fee2e2;
        border-color: #dc2626;
        transform: translateY(-1px);
    }

    .alert-success {
        background-color: #d1fae5;
        border: 1px solid rgba(16, 185, 129, 0.2);
        color: #065f46;
        padding: 16px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-primary {
        background-color: #e53935;
        color: #ffffff;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 700;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #b71c1c;
        transform: translateY(-1px);
    }
</style>
@endpush

@section('content')
<div class="history-page">
    <div class="container">
        
        @if(session('success'))
            <div class="alert-success">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif

        <header class="history-header">
            <div class="header-text">
                <h1>Lịch sử đăng ký hiến máu</h1>
                <p>Quản lý các chương trình bạn đã tham gia hoặc đăng ký đặt lịch hẹn.</p>
            </div>
            
            @php
                $totalRegs = count($registrations);
                $totalDonated = 0;
                foreach($registrations as $r) {
                    if($r->HoSoId) $totalDonated++;
                }
            @endphp
            <div class="header-stats">
                <div class="stat-pill">
                    <span class="value">{{ $totalRegs }}</span>
                    <span class="label">Đã đăng ký</span>
                </div>
                <div class="stat-pill">
                    <span class="value">{{ $totalDonated }}</span>
                    <span class="label">Đã hiến máu</span>
                </div>
            </div>
        </header>

        <div class="history-card">
            @if(count($registrations) > 0)
                <div class="history-table-wrapper">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>Tên chương trình</th>
                                <th>Đơn vị tổ chức</th>
                                <th>Thời gian diễn ra</th>
                                <th>Địa điểm</th>
                                <th>Thời gian đăng ký</th>
                                <th>Trạng thái</th>
                                <th style="text-align: center;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $reg)
                                @php
                                    $status = 'Chờ hiến';
                                    $statusStyle = 'background-color: #fef3c7; color: #d97706;';
                                    $canCancel = true;
                                    
                                    if ($reg->HoSoId) {
                                        $status = 'Đã hiến';
                                        $statusStyle = 'background-color: #d1fae5; color: #059669;';
                                        $canCancel = false;
                                    } elseif ((int)$reg->TrangThai === 2) {
                                        $status = 'Đã duyệt';
                                        $statusStyle = 'background-color: #e0f2fe; color: #0284c7;';
                                    } elseif ((int)$reg->TrangThai === 0) {
                                        $status = 'Hủy đăng ký';
                                        $statusStyle = 'background-color: #fee2e2; color: #dc2626;';
                                        $canCancel = false;
                                    }
                                @endphp
                                <tr>
                                    <td style="font-weight: 700; color: #1e293b;">
                                        <a href="{{ route('frontend.chuong-trinh.show', $reg->ChuongTrinhId) }}" style="color: inherit; text-decoration: none; border-bottom: 1px dashed transparent; transition: all 0.2s;" onmouseover="this.style.color='#e53935'" onmouseout="this.style.color='inherit'">
                                            {{ $reg->TenChuongTrinh }}
                                        </a>
                                    </td>
                                    <td>{{ $reg->TenDonVi }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reg->ThoiGianBatDau)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</td>
                                    <td>{{ $reg->DiaChi }}</td>
                                    <td>{{ \Carbon\Carbon::parse($reg->ThoiGianDangKy)->timezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="history-badge" style="{{ $statusStyle }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if($canCancel)
                                            <form method="POST" action="{{ route('frontend.lich-su-dang-ky.cancel', $reg->Id) }}" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đăng ký tham gia chương trình này không? Hành động này không thể hoàn tác.');" style="margin: 0; padding: 0; display: inline-block;">
                                                @csrf
                                                <button type="submit" class="btn-cancel">
                                                    <span>🚫</span> Hủy đăng ký
                                                </button>
                                            </form>
                                        @else
                                            <span style="color: #cbd5e1; font-weight: 600; font-size: 13px;">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 48px; color: #64748b;">
                    <p style="font-size: 18px; font-weight: 700; margin-bottom: 8px; color: #1e293b;">Bạn chưa đăng ký chương trình nào</p>
                    <p style="font-size: 14px; margin-bottom: 24px;">Đăng ký tham gia hiến máu cứu người ngay hôm nay để sẻ chia sự sống.</p>
                    <a href="{{ route('frontend.chuong-trinh.index') }}" class="btn-primary">
                        👉 Khám phá chương trình
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
