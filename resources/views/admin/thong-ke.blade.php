@extends('admin.layouts.dashboard', [
    'role' => 'admin',
    'sidebarClass' => 'sidebar-dark',
    'primaryColor' => '#2563eb',
    'primaryHoverColor' => '#1d4ed8',
    'primaryLightColor' => '#eff6ff',
    'sidebarBg' => '#111c43',
    'sidebarActive' => '#2563eb',
    'bodyBg' => '#f3f6ff',
    'userName' => 'System Admin',
    'userRole' => 'Quản trị viên',
])

@section('title', 'Thống kê')
@section('navbar-title', 'Thống kê')
@section('navbar-subtitle', 'Theo dõi, phân tích số liệu hiến máu và chương trình hiến máu toàn hệ thống.')

@section('content')
<div class="content-container">

    <!-- FILTERS BAR CARD -->
    <section class="filters-card" style="margin-bottom: 8px;">
        <form action="" method="GET" class="filters-form" style="grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) auto;">
            <!-- Date range selection -->
            <div class="form-group">
                <label class="form-label" for="khoang_thoi_gian">Khoảng thời gian</label>
                <div class="input-wrapper">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <select class="form-input form-select form-input-icon" id="khoang_thoi_gian" name="khoang_thoi_gian" onchange="updateDateInputs(this.value)">
                        <option value="2025" selected>01/01/2025 - 31/12/2025</option>
                        <option value="2024">01/01/2024 - 31/12/2024</option>
                        <option value="custom">Tùy chọn khoảng ngày</option>
                    </select>
                </div>
            </div>

            <!-- Custom date inputs (hidden by default unless custom is picked) -->
            <div id="custom-date-inputs" style="display: none; gap: 16px; align-items: flex-end;">
                <div class="form-group">
                    <label class="form-label" for="tu_ngay">Từ ngày</label>
                    <input class="form-input" type="date" id="tu_ngay" name="tu_ngay" value="{{ $tuNgay }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="den_ngay">Đến ngày</label>
                    <input class="form-input" type="date" id="den_ngay" name="den_ngay" value="{{ $denNgay }}">
                </div>
            </div>

            <!-- Program selection -->
            <div class="form-group">
                <label class="form-label" for="chuong_trinh_id">Chọn chương trình</label>
                <select class="form-input form-select" id="chuong_trinh_id" name="chuong_trinh_id">
                    <option value="">Tất cả chương trình</option>
                    @foreach($allPrograms as $prog)
                        <option value="{{ $prog->Id }}" {{ $chuongTrinhId == $prog->Id ? 'selected' : '' }}>{{ $prog->TenChuongTrinh }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter submit button -->
            <div class="form-group actions-group" style="margin-bottom: 2px;">
                <button type="submit" class="btn-action btn-filter" style="background-color: var(--primary); color: #fff; border: none; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Lọc dữ liệu
                </button>
            </div>
        </form>
    </section>

    <!-- TOP STATISTICS CARDS GRID -->
    <section class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
        <!-- Card 1: Total Programs -->
        <div class="stat-card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <div class="stat-icon blue" style="background-color: #eff6ff; color: #2563eb;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title" style="color: var(--neutral-grey); font-weight: 500;">Tổng chương trình</span>
                <span class="stat-value" style="font-size: 28px; font-weight: 700; color: var(--neutral-dark);">{{ $totalPrograms }}</span>
                <span class="stat-label" style="color: var(--success); font-weight: 600;">
                    <span style="font-size: 14px;">↑</span> 8% so với cùng kỳ 2024
                </span>
            </div>
        </div>

        <!-- Card 2: Total Participants -->
        <div class="stat-card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <div class="stat-icon green" style="background-color: #ecfdf5; color: #10b981;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title" style="color: var(--neutral-grey); font-weight: 500;">Tổng người tham gia</span>
                <span class="stat-value" style="font-size: 28px; font-weight: 700; color: var(--neutral-dark);">{{ number_format($totalParticipants, 0, ',', '.') }}</span>
                <span class="stat-label" style="color: var(--success); font-weight: 600;">
                    <span style="font-size: 14px;">↑</span> 12% so với cùng kỳ 2024
                </span>
            </div>
        </div>

        <!-- Card 3: Blood Volume -->
        <div class="stat-card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <div class="stat-icon red" style="background-color: #fef2f2; color: #ef4444;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title" style="color: var(--neutral-grey); font-weight: 500;">Tổng lượng máu thu được</span>
                <span class="stat-value" style="font-size: 28px; font-weight: 700; color: var(--neutral-dark);">
                    {{ number_format($totalBlood, 0, ',', '.') }} <span style="font-size: 16px; font-weight: 500; color: var(--neutral-grey);">ml</span>
                </span>
                <span class="stat-label" style="color: var(--success); font-weight: 600;">
                    <span style="font-size: 14px;">↑</span> 15% so với cùng kỳ 2024
                </span>
            </div>
        </div>

        <!-- Card 4: Donations count -->
        <div class="stat-card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <div class="stat-icon orange" style="background-color: #fffbeb; color: #f59e0b;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="stat-info">
                <span class="stat-title" style="color: var(--neutral-grey); font-weight: 500;">Số lượt hiến máu</span>
                <span class="stat-value" style="font-size: 28px; font-weight: 700; color: var(--neutral-dark);">{{ number_format($totalDonations, 0, ',', '.') }}</span>
                <span class="stat-label" style="color: var(--success); font-weight: 600;">
                    <span style="font-size: 14px;">↑</span> 10% so với cùng kỳ 2024
                </span>
            </div>
        </div>
    </section>

    <!-- CHARTS PANEL ROW -->
    <div style="display: grid; grid-template-columns: 7fr 5fr; gap: 24px;">
        <!-- Left: Monthly Blood Volume Bar Chart -->
        <div class="table-card" style="padding: 24px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-family: var(--font-heading); font-size: 16px; font-weight: 700; color: var(--neutral-dark);">Lượng máu thu được theo tháng (ml)</h3>
                <select class="form-input form-select-sm" style="width: 120px; background-color: var(--neutral-light);">
                    <option>Theo tháng</option>
                    <option>Theo quý</option>
                </select>
            </div>
            <div style="position: relative; height: 300px; width: 100%;">
                <canvas id="monthlyVolumeChart"></canvas>
            </div>
        </div>

        <!-- Right: Program Status Doughnut Chart -->
        <div class="table-card" style="padding: 24px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <h3 style="font-family: var(--font-heading); font-size: 16px; font-weight: 700; color: var(--neutral-dark); margin-bottom: 20px;">Tỷ lệ trạng thái chương trình</h3>
            <div style="display: flex; align-items: center; justify-content: space-between; height: 260px; width: 100%;">
                <!-- Doughnut Canvas wrapper -->
                <div style="position: relative; height: 220px; width: 220px; flex-shrink: 0;">
                    <canvas id="programStatusChart"></canvas>
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                        <span style="display: block; font-size: 12px; font-weight: 600; color: var(--neutral-grey); text-transform: uppercase;">Tổng</span>
                        <span style="font-size: 32px; font-weight: 800; color: var(--neutral-dark); line-height: 1.2;">{{ $totalPrograms }}</span>
                    </div>
                </div>

                <!-- Doughnut Custom Legend list -->
                <div style="display: flex; flex-direction: column; gap: 12px; width: calc(100% - 240px); font-size: 13px; font-weight: 600; color: #475569;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #3b82f6;"></span>
                            <span>Đã kết thúc</span>
                        </div>
                        <span>{{ $status5 }} ({{ round(($status5 / $totalPrograms)*100, 1) }}%)</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #10b981;"></span>
                            <span>Đang diễn ra</span>
                        </div>
                        <span>{{ $status3 }} ({{ round(($status3 / $totalPrograms)*100, 1) }}%)</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #f59e0b;"></span>
                            <span>Chờ duyệt</span>
                        </div>
                        <span>{{ $status1 }} ({{ round(($status1 / $totalPrograms)*100, 1) }}%)</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="width: 10px; height: 10px; border-radius: 50%; background-color: #8b5cf6;"></span>
                            <span>Đã hủy</span>
                        </div>
                        <span>{{ $status4 }} ({{ round(($status4 / $totalPrograms)*100, 1) }}%)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLES GRID ROW -->
    <div style="display: grid; grid-template-columns: 7fr 5fr; gap: 24px;">
        <!-- Left: Top 5 Blood Donating Programs -->
        <div class="table-card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-family: var(--font-heading); font-size: 16px; font-weight: 700; color: var(--neutral-dark);">Top 5 chương trình thu được nhiều máu nhất</h3>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">STT</th>
                            <th>Tên chương trình</th>
                            <th>Ngày tổ chức</th>
                            <th>Người tham gia</th>
                            <th style="text-align: right;">Lượng máu (ml)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topPrograms as $idx => $p)
                            <tr>
                                <td class="stt-cell">{{ $idx + 1 }}</td>
                                <td style="font-weight: 700;">{{ $p['name'] }}</td>
                                <td style="color: var(--neutral-grey); font-weight: 600;">{{ $p['date'] }}</td>
                                <td style="font-weight: 600;">{{ $p['participants'] }}</td>
                                <td style="text-align: right;">
                                    <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 4px;">
                                        <span style="font-weight: 700; color: #1e293b;">{{ number_format($p['ml'], 0, ',', '.') }}</span>
                                        <div style="width: 100px; height: 6px; background-color: var(--border-color); border-radius: 4px; overflow: hidden; position: relative;">
                                            <!-- Colored progress bar according to index -->
                                            <div style="width: {{ $p['percent'] }}%; height: 100%; border-radius: 4px; background: 
                                                @if($idx == 0) linear-gradient(90deg, #f87171, #ef4444)
                                                @elseif($idx == 1) linear-gradient(90deg, #34d399, #10b981)
                                                @elseif($idx == 2) linear-gradient(90deg, #60a5fa, #3b82f6)
                                                @elseif($idx == 3) linear-gradient(90deg, #fbbf24, #f59e0b)
                                                @else linear-gradient(90deg, #a78bfa, #8b5cf6)
                                                @endif;">
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Show all footer link -->
            <div style="padding: 16px; text-align: center; border-top: 1px solid var(--border-color);">
                <a href="{{ route('admin.chuong-trinh.index') }}" style="font-size: 13px; font-weight: 700; color: var(--primary); text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                    Xem tất cả
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 14px; height: 14px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Right: Blood Group statistics -->
        <div class="table-card" style="box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <div style="padding: 20px 24px; border-bottom: 1px solid var(--border-color);">
                <h3 style="font-family: var(--font-heading); font-size: 16px; font-weight: 700; color: var(--neutral-dark);">Thống kê theo nhóm máu</h3>
            </div>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Nhóm máu</th>
                            <th>Số người</th>
                            <th style="text-align: right;">Lượng máu (ml)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalBloodPeople = 0;
                            $totalBloodMl = 0;
                        @endphp
                        @foreach($bloodTypes as $group => $data)
                            @php
                                $totalBloodPeople += $data['people'];
                                $totalBloodMl += $data['ml'];
                            @endphp
                            <tr>
                                <td style="font-weight: 800; font-size: 15px; color: var(--neutral-dark);">Nhóm {{ $group }}</td>
                                <td style="font-weight: 600; color: #475569;">{{ number_format($data['people'], 0, ',', '.') }}</td>
                                <td style="text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; align-items: center; gap: 12px;">
                                        <div style="width: 80px; height: 6px; background-color: var(--border-color); border-radius: 4px; overflow: hidden; position: relative;">
                                            <div style="width: {{ min(100, ($data['ml'] / 5000) * 100) }}%; height: 100%; border-radius: 4px; background-color: 
                                                @if($group == 'A') #ef4444
                                                @elseif($group == 'O') #10b981
                                                @elseif($group == 'B') #3b82f6
                                                @else #f59e0b
                                                @endif;">
                                            </div>
                                        </div>
                                        <span style="font-weight: 700; color: #1e293b; min-width: 60px; text-align: right;">{{ number_format($data['ml'], 0, ',', '.') }}</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <!-- Grand Total Row -->
                        <tr style="background-color: #fafbfc; border-top: 2px solid var(--border-color);">
                            <td style="font-weight: 800; font-size: 15px; color: var(--neutral-dark);">Tổng</td>
                            <td style="font-weight: 800; color: var(--danger); font-size: 15px;">{{ number_format($totalBloodPeople, 0, ',', '.') }}</td>
                            <td style="text-align: right; font-weight: 800; color: var(--danger); font-size: 15px;">{{ number_format($totalBloodMl, 0, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- BOTTOM TREND LINE CHART SECTION -->
    <div class="table-card" style="padding: 24px; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
        <h3 style="font-family: var(--font-heading); font-size: 16px; font-weight: 700; color: var(--neutral-dark); margin-bottom: 24px;">Xu hướng tham gia hiến máu theo năm</h3>
        <div style="display: grid; grid-template-columns: 8fr 4fr; gap: 40px; align-items: center;">
            <!-- Line Chart Canvas wrapper -->
            <div style="position: relative; height: 260px; width: 100%;">
                <canvas id="yearlyTrendChart"></canvas>
            </div>

            <!-- Stats Box (Right Panel) -->
            <div style="background-color: var(--neutral-light); border: 1px solid var(--border-color); border-radius: 16px; padding: 24px; text-align: center; box-shadow: var(--shadow-sm);">
                <span style="font-size: 13px; font-weight: 600; color: var(--neutral-grey); text-transform: uppercase; letter-spacing: 0.5px;">Tăng trưởng 2025 so với 2024</span>
                <div style="display: flex; flex-direction: column; gap: 8px; margin: 16px 0;">
                    <span style="font-size: 36px; font-weight: 800; color: var(--primary); display: inline-flex; align-items: center; justify-content: center; gap: 6px;">
                        <span style="font-size: 28px;">↑</span> 11,96%
                    </span>
                    <span style="font-size: 14px; font-weight: 600; color: var(--neutral-grey);">(+250 người tham gia)</span>
                </div>
                <!-- Mini trend background element -->
                <div style="display: flex; justify-content: center; align-items: flex-end; gap: 4px; height: 40px;">
                    <div style="width: 20px; height: 15px; background-color: #cbd5e1; border-radius: 2px;"></div>
                    <div style="width: 20px; height: 22px; background-color: #cbd5e1; border-radius: 2px;"></div>
                    <div style="width: 20px; height: 28px; background-color: #cbd5e1; border-radius: 2px;"></div>
                    <div style="width: 20px; height: 34px; background-color: #cbd5e1; border-radius: 2px;"></div>
                    <div style="width: 20px; height: 40px; background-color: var(--primary); border-radius: 2px;"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- 1. Monthly Volume Bar Chart ---
        const monthlyCtx = document.getElementById('monthlyVolumeChart').getContext('2d');
        
        // Define beautiful blue-gradient color mapping
        const barGradient = monthlyCtx.createLinearGradient(0, 0, 0, 300);
        barGradient.addColorStop(0, '#60a5fa');
        barGradient.addColorStop(1, '#3b82f6');

        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
                datasets: [{
                    label: 'Lượng máu (ml)',
                    data: @json($monthlyVolume),
                    backgroundColor: barGradient,
                    hoverBackgroundColor: '#2563eb',
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.55
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                weight: '600',
                                size: 12
                            },
                            boxWidth: 12,
                            boxHeight: 12,
                            borderRadius: 2
                        }
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', weight: '700' },
                        bodyFont: { family: 'Plus Jakarta Sans', weight: '600' },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', weight: '600', size: 11 },
                            color: '#64748b'
                        }
                    },
                    y: {
                        border: { dash: [5, 5] },
                        grid: { color: '#e2e8f0' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', weight: '600', size: 11 },
                            color: '#64748b'
                        }
                    }
                }
            }
        });

        // --- 2. Program Status Doughnut Chart ---
        const statusCtx = document.getElementById('programStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Đã kết thúc', 'Đang diễn ra', 'Chờ duyệt', 'Đã hủy'],
                datasets: [{
                    data: [{{ $status5 }}, {{ $status3 }}, {{ $status1 }}, {{ $status4 }}],
                    backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', weight: '700' },
                        bodyFont: { family: 'Plus Jakarta Sans', weight: '600' },
                        padding: 10,
                        cornerRadius: 8
                    }
                }
            }
        });

        // --- 3. Yearly Trend Line Chart ---
        const yearlyCtx = document.getElementById('yearlyTrendChart').getContext('2d');
        
        // Define beautiful soft blue gradient fill for line chart
        const areaGradient = yearlyCtx.createLinearGradient(0, 0, 0, 240);
        areaGradient.addColorStop(0, 'rgba(37, 99, 235, 0.16)');
        areaGradient.addColorStop(1, 'rgba(37, 99, 235, 0.01)');

        new Chart(yearlyCtx, {
            type: 'line',
            data: {
                labels: ['2021', '2022', '2023', '2024', '2025'],
                datasets: [{
                    label: 'Người tham gia',
                    data: @json($yearlyTrend),
                    borderColor: '#2563eb',
                    borderWidth: 3,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    tension: 0.35, // Smooth Bezier curves
                    fill: true,
                    backgroundColor: areaGradient
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { family: 'Plus Jakarta Sans', weight: '700' },
                        bodyFont: { family: 'Plus Jakarta Sans', weight: '600' },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', weight: '600', size: 12 },
                            color: '#64748b'
                        }
                    },
                    y: {
                        border: { dash: [5, 5] },
                        grid: { color: '#e2e8f0' },
                        ticks: {
                            font: { family: 'Plus Jakarta Sans', weight: '600', size: 12 },
                            color: '#64748b'
                        }
                    }
                }
            }
        });
    });

    // Handle date selection toggle helpers
    function updateDateInputs(value) {
        const customDiv = document.getElementById('custom-date-inputs');
        if (value === 'custom') {
            customDiv.style.display = 'inline-flex';
        } else {
            customDiv.style.display = 'none';
        }
    }
</script>
@endpush
