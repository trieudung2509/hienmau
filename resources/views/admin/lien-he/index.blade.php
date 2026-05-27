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

@section('title', 'Quản lý liên hệ')
@section('navbar-title', 'Quản lý liên hệ')
@section('navbar-subtitle', 'Theo dõi, phản hồi thông tin và thắc mắc từ người tham gia hệ thống.')

@push('styles')
<style>
    .content-container {
        padding: 32px;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* FILTERS CARD */
    .filters-card {
        background-color: #fff;
        border-radius: 16px;
        padding: 20px 24px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
    }

    .filters-form {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 16px;
        align-items: flex-end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-family: var(--font-heading);
        font-size: 13px;
        font-weight: 600;
        color: var(--neutral-grey);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid var(--border-color);
        border-radius: 10px;
        outline: none;
        font-family: var(--font-main);
        font-size: 14px;
        color: var(--neutral-dark);
        background-color: var(--neutral-light);
        transition: all 0.2s ease;
    }

    .form-input:focus {
        border-color: var(--primary);
        background-color: #fff;
        box-shadow: 0 0 0 4px var(--primary-light);
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 10px;
        font-family: var(--font-heading);
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-search {
        background-color: var(--primary);
        color: #fff;
    }

    .btn-search:hover {
        background-color: var(--primary-hover);
    }

    /* TABLE */
    .table-card {
        background-color: #fff;
        border-radius: 16px;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .admin-table th {
        background-color: var(--neutral-light);
        padding: 16px 24px;
        font-family: var(--font-heading);
        font-size: 12px;
        font-weight: 700;
        color: var(--neutral-grey);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid var(--border-color);
    }

    .admin-table td {
        padding: 20px 24px;
        font-size: 14px;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
    }

    .admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    .admin-table tbody tr:hover td {
        background-color: var(--neutral-light);
    }

    /* BADGES */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 99px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1;
    }

    .status-badge.unread {
        background-color: var(--warning-light);
        color: var(--warning);
    }

    .status-badge.read {
        background-color: var(--success-light);
        color: var(--success);
    }

    /* BUTTONS TABLE */
    .btn-table {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        border: 1px solid transparent;
        background: none;
    }

    .btn-view {
        background-color: var(--primary-light);
        color: var(--primary);
    }

    .btn-view:hover {
        background-color: var(--primary);
        color: #fff;
    }

    .btn-read-check {
        background-color: var(--success-light);
        color: var(--success);
    }

    .btn-read-check:hover {
        background-color: var(--success);
        color: #fff;
    }

    .btn-delete {
        background-color: var(--danger-light);
        color: var(--danger);
    }

    .btn-delete:hover {
        background-color: var(--danger);
        color: #fff;
    }

    /* MODAL */
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        transition: opacity 0.3s ease;
        padding: 20px;
    }

    .modal-backdrop.show {
        display: flex;
        opacity: 1;
    }

    .modal-box {
        background-color: #fff;
        border-radius: 20px;
        width: 100%;
        max-width: 650px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
        overflow: hidden;
        transform: translateY(-30px);
        transition: transform 0.3s ease;
    }

    .modal-backdrop.show .modal-box {
        transform: translateY(0);
    }

    .modal-header {
        padding: 24px 32px;
        background-color: var(--neutral-light);
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-family: var(--font-heading);
        font-size: 20px;
        font-weight: 700;
        color: var(--neutral-dark);
    }

    .modal-close {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--neutral-grey);
        font-size: 24px;
        line-height: 1;
        transition: color 0.2s ease;
    }

    .modal-close:hover {
        color: var(--danger);
    }

    .modal-body {
        padding: 32px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        max-height: 70vh;
        overflow-y: auto;
    }

    .modal-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 16px;
        background-color: var(--neutral-light);
        padding: 16px 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .meta-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--neutral-grey);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--neutral-dark);
    }

    .modal-message {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .message-title {
        font-family: var(--font-heading);
        font-size: 14px;
        font-weight: 700;
        color: var(--neutral-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid var(--primary-light);
        padding-bottom: 8px;
    }

    .message-text {
        font-size: 14px;
        line-height: 1.8;
        color: #475569;
        white-space: pre-wrap;
        background-color: var(--neutral-light);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--border-color);
    }

    .pagination-wrapper {
        padding: 20px 24px;
        border-top: 1px solid var(--border-color);
        background-color: var(--neutral-light);
    }
</style>
@endpush

@section('content')
<div class="content-container">

    <!-- SEARCH FILTERS -->
    <section class="filters-card">
        <form action="" method="GET" class="filters-form">
            <div class="form-group">
                <label class="form-label" for="keyword">Tìm kiếm liên hệ</label>
                <input class="form-input" type="text" id="keyword" name="keyword" value="{{ request('keyword') }}" placeholder="Nhập tên người gửi, email, tiêu đề hoặc nội dung...">
            </div>
            <button type="submit" class="btn-action btn-search">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="width: 18px; height: 18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z" />
                </svg>
                Tìm kiếm
            </button>
        </form>
    </section>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))
        <div style="background-color: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 16px 24px; border-radius: 12px; font-weight: 600; box-shadow: var(--shadow-sm);">
            ✅ {{ session('success') }}
        </div>
    @endif

    <!-- CONTACT TABLE -->
    <section class="table-card">
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;">STT</th>
                        <th>Người gửi</th>
                        <th>Tiêu đề</th>
                        <th>Ngày gửi</th>
                        <th style="width: 130px; text-align: center;">Trạng thái</th>
                        <th style="width: 250px; text-align: right;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $idx => $contact)
                        <tr>
                            <td style="text-align: center; font-weight: 600; color: var(--neutral-grey);">
                                {{ ($contacts->currentPage() - 1) * $contacts->perPage() + $idx + 1 }}
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <span style="font-weight: 700; color: var(--neutral-dark);">{{ $contact->HoTen }}</span>
                                    <div style="display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--neutral-grey); font-weight: 500;">
                                        <span>{{ $contact->Email }}</span>
                                        <span style="color: var(--border-color);">|</span>
                                        <span>{{ $contact->SoDienThoai }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="font-weight: 600; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                {{ $contact->TieuDe }}
                            </td>
                            <td style="color: var(--neutral-grey); font-weight: 600;">
                                {{ \Carbon\Carbon::parse($contact->created_at)->format('H:i - d/m/Y') }}
                            </td>
                            <td style="text-align: center;">
                                @if($contact->TrangThai == 0)
                                    <span class="status-badge unread">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--warning);"></span>
                                        Chưa đọc
                                    </span>
                                @else
                                    <span class="status-badge read">
                                        <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--success);"></span>
                                        Đã đọc
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <div style="display: inline-flex; gap: 8px;">
                                    <button class="btn-table btn-view" onclick="openDetailsModal({{ json_encode($contact) }})">
                                        Xem thêm
                                    </button>

                                    @if($contact->TrangThai == 0)
                                        <form method="POST" action="{{ route('admin.lien-he.read', $contact->Id) }}" style="margin: 0; padding: 0;">
                                            @csrf
                                            <button type="submit" class="btn-table btn-read-check" title="Đánh dấu đã đọc">
                                                Đã đọc
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.lien-he.destroy', $contact->Id) }}" style="margin: 0; padding: 0;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin nhắn liên hệ này không?')">
                                        @csrf
                                        <button type="submit" class="btn-table btn-delete" title="Xóa tin nhắn">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 48px; color: var(--neutral-grey); font-weight: 600;">
                                📥 Không tìm thấy tin nhắn liên hệ nào.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($contacts->hasPages())
            <div class="pagination-wrapper">
                {{ $contacts->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </section>

</div>

<!-- DETAILS MODAL -->
<div class="modal-backdrop" id="detailsModal" onclick="closeDetailsModal(event)">
    <div class="modal-box">
        <div class="modal-header">
            <span class="modal-title">Chi tiết tin nhắn liên hệ</span>
            <button class="modal-close" onclick="forceCloseModal()">×</button>
        </div>
        <div class="modal-body">
            <div class="modal-meta-grid">
                <div class="meta-item">
                    <span class="meta-label">Người gửi</span>
                    <span class="meta-value" id="modalSender">Nguyễn Văn An</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Email</span>
                    <span class="meta-value" id="modalEmail">an@gmail.com</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Số điện thoại</span>
                    <span class="meta-value" id="modalPhone">0987654321</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Ngày gửi</span>
                    <span class="meta-value" id="modalDate">12:30 - 25/05/2026</span>
                </div>
                <div class="meta-item" style="display: flex; align-items: flex-start; justify-content: flex-start;">
                    <span class="meta-label" style="display: block; margin-bottom: 4px;">Trạng thái</span>
                    <span id="modalStatusContainer">
                        <span class="status-badge unread">Chưa đọc</span>
                    </span>
                </div>
            </div>

            <div class="modal-meta-grid" style="grid-template-columns: 1fr; margin-top: -10px;">
                <div class="meta-item">
                    <span class="meta-label">Tiêu đề liên hệ</span>
                    <span class="meta-value" id="modalSubject" style="font-size: 14px; font-weight: 700; color: var(--neutral-dark);">Hỏi về lịch hiến máu</span>
                </div>
            </div>

            <div class="modal-message">
                <span class="message-title">Nội dung liên hệ</span>
                <div class="message-text" id="modalContent">
                    Nội dung chi tiết ở đây...
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const backdrop = document.getElementById('detailsModal');

    function openDetailsModal(contact) {
        // Populate modal data
        document.getElementById('modalSender').textContent = contact.HoTen;
        document.getElementById('modalEmail').textContent = contact.Email;
        document.getElementById('modalPhone').textContent = contact.SoDienThoai;
        
        // Format Date
        const dateObj = new Date(contact.created_at);
        const hours = String(dateObj.getHours()).padStart(2, '0');
        const minutes = String(dateObj.getMinutes()).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const year = dateObj.getFullYear();
        document.getElementById('modalDate').textContent = `${hours}:${minutes} - ${day}/${month}/${year}`;
        
        document.getElementById('modalSubject').textContent = contact.TieuDe;
        document.getElementById('modalContent').textContent = contact.NoiDung;

        // Render Status Badge
        const statusContainer = document.getElementById('modalStatusContainer');
        if (contact.TrangThai == 0) {
            statusContainer.innerHTML = `
                <span class="status-badge unread">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--warning);"></span>
                    Chưa đọc
                </span>
            `;
            
            // Proactively auto-mark as read by submitting the form after short delay
            // This is a premium touch! When they open the details modal, we can automatically trigger a background read mark
            // or let the user do it manually. We'll let them click the button or keep it as is.
        } else {
            statusContainer.innerHTML = `
                <span class="status-badge read">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--success);"></span>
                    Đã đọc
                </span>
            `;
        }

        // Show Modal
        backdrop.style.display = 'flex';
        // Trigger reflow
        backdrop.offsetHeight;
        backdrop.classList.add('show');
    }

    function closeDetailsModal(event) {
        if (event.target === backdrop) {
            forceCloseModal();
        }
    }

    function forceCloseModal() {
        backdrop.classList.remove('show');
        setTimeout(() => {
            backdrop.style.display = 'none';
        }, 300);
    }
</script>
@endpush
