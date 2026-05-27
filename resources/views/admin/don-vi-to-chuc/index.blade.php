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

@section('title', 'Quan ly don vi to chuc')
@section('navbar-title', 'Quan ly don vi to chuc')
@section('navbar-subtitle', 'Tao moi, cap nhat va quan ly thong tin cac don vi to chuc.')


@section('content')
<div class="content-container">
    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="card">
        <h2 class="card-title">Bo loc don vi</h2>
        <p class="card-subtitle">Tim kiem nhanh theo tu khoa va trang thai.</p>

        <form method="get" action="{{ route('admin.don-vi-to-chuc.index') }}" class="filters-form">
            <div class="form-group">
                <label class="form-label" for="keyword">Tu khoa</label>
                <input class="form-input" id="keyword" name="keyword" type="text" value="{{ request('keyword') }}" placeholder="Ten, ma, email, so dien thoai">
            </div>

            <div class="form-group">
                <label class="form-label" for="trang_thai">Trang thai</label>
                <select class="form-select" id="trang_thai" name="trang_thai">
                    <option value="">Tat ca</option>
                    <option value="1" {{ request('trang_thai') == '1' ? 'selected' : '' }}>Dang hoat dong</option>
                    <option value="2" {{ request('trang_thai') == '2' ? 'selected' : '' }}>Tam ngung</option>
                </select>
            </div>

            <button class="btn-primary" type="submit">Loc</button>
        </form>
    </div>

    <div class="grid-two">
        <div class="card">
            <h2 class="card-title">{{ $editDonVi ? 'Cap nhat don vi' : 'Tao moi don vi' }}</h2>
            <p class="card-subtitle">
                {{ $editDonVi ? 'Chinh sua thong tin don vi to chuc.' : 'Nhap thong tin don vi to chuc moi.' }}
            </p>

            <form method="post" action="{{ $editDonVi ? route('admin.don-vi-to-chuc.update', $editDonVi->Id) : route('admin.don-vi-to-chuc.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="TenDonVi">Ten don vi</label>
                    <input class="form-input" id="TenDonVi" name="TenDonVi" type="text" value="{{ old('TenDonVi', $editDonVi->TenDonVi ?? '') }}" placeholder="Nhap ten don vi">
                </div>

                <div class="form-group">
                    <label class="form-label" for="MaDonVi">Ma don vi</label>
                    <input class="form-input" id="MaDonVi" name="MaDonVi" type="text" value="{{ old('MaDonVi', $editDonVi->MaDonVi ?? '') }}" placeholder="VD: DVTC-001">
                </div>

                <div class="form-group">
                    <label class="form-label" for="Loai">Loai don vi</label>
                    <input class="form-input" id="Loai" name="Loai" type="text" value="{{ old('Loai', $editDonVi->Loai ?? '') }}" placeholder="Benh vien, Trung tam...">
                </div>

                <div class="form-group">
                    <label class="form-label" for="NguoiDaiDien">Nguoi dai dien</label>
                    <input class="form-input" id="NguoiDaiDien" name="NguoiDaiDien" type="text" value="{{ old('NguoiDaiDien', $editDonVi->NguoiDaiDien ?? '') }}" placeholder="Ten nguoi dai dien">
                </div>

                <div class="form-group">
                    <label class="form-label" for="Email">Email</label>
                    <input class="form-input" id="Email" name="Email" type="email" value="{{ old('Email', $editDonVi->Email ?? '') }}" placeholder="email@donvi.com">
                </div>

                <div class="form-group">
                    <label class="form-label" for="SoDienThoai">So dien thoai</label>
                    <input class="form-input" id="SoDienThoai" name="SoDienThoai" type="text" value="{{ old('SoDienThoai', $editDonVi->SoDienThoai ?? '') }}" placeholder="So dien thoai">
                </div>

                <div class="form-group">
                    <label class="form-label" for="DiaChi">Dia chi</label>
                    <input class="form-input" id="DiaChi" name="DiaChi" type="text" value="{{ old('DiaChi', $editDonVi->DiaChi ?? '') }}" placeholder="Dia chi chi tiet">
                </div>

                <div class="form-group">
                    <label class="form-label" for="HinhAnh">Anh dai dien don vi</label>
                    <div class="image-preview" style="margin-bottom: 10px;">
                        <img id="HinhAnhPreview" src="{{ old('HinhAnh', $editDonVi->HinhAnh ?? '') }}" alt="Preview" style="max-width: 120px; max-height: 120px; border-radius: 8px; object-fit: cover; border: 1px solid #ddd; {{ old('HinhAnh', $editDonVi->HinhAnh ?? '') ? '' : 'display: none;' }}">
                    </div>
                    <input class="form-input" id="HinhAnh" name="HinhAnh" type="text" value="{{ old('HinhAnh', $editDonVi->HinhAnh ?? '') }}" placeholder="VD: https://images.unsplash.com/... hoặc chọn file bên dưới" style="margin-bottom: 8px;">
                    <input class="form-input" id="HinhAnhFile" name="HinhAnhFile" type="file" accept="image/*" style="padding: 4px;">
                </div>

                <div class="form-group">
                    <label class="form-label" for="OwnerUserId">Tai khoan chu so huu</label>
                    <select class="form-select" id="OwnerUserId" name="OwnerUserId">
                        <option value="">Chon tai khoan</option>
                        @foreach ($owners as $owner)
                            <option value="{{ $owner->Id }}" {{ old('OwnerUserId', $editDonVi->OwnerUserId ?? '') == $owner->Id ? 'selected' : '' }}>
                                {{ $owner->HoTen }} - {{ $owner->TenVaiTro }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="TrangThai">Trang thai</label>
                    <select class="form-select" id="TrangThai" name="TrangThai">
                        <option value="1" {{ old('TrangThai', $editDonVi->TrangThai ?? '1') == '1' ? 'selected' : '' }}>Dang hoat dong</option>
                        <option value="2" {{ old('TrangThai', $editDonVi->TrangThai ?? '1') == '2' ? 'selected' : '' }}>Tam ngung</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="MoTa">Mo ta</label>
                    <textarea class="form-textarea" id="MoTa" name="MoTa" placeholder="Mo ta ngan ve don vi">{{ old('MoTa', $editDonVi->MoTa ?? '') }}</textarea>
                </div>

                <div style="display: flex; gap: 10px; align-items: center; margin-top: 1rem;">
                    <button class="btn-primary" type="submit">
                        {{ $editDonVi ? 'Cap nhat don vi' : 'Tao don vi' }}
                    </button>
                    @if ($editDonVi)
                        <a href="{{ route('admin.don-vi-to-chuc.index') }}" style="font-size: 13px; font-weight: 600; color: var(--neutral-grey); text-decoration: none;">Huy</a>
                    @endif
                </div>
            </form>
        </div>

        <div class="card">
            <h2 class="card-title">Danh sach don vi</h2>
            <p class="card-subtitle">Cap nhat nhanh thong tin tung don vi.</p>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Don vi</th>
                            <th>Loai</th>
                            <th>Lien he</th>
                            <th>Chu so huu</th>
                            <th>Trang thai</th>
                            <th>Thao tac</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($donVis as $dv)
                            <tr>
                                <td>
                                    <div style="display: flex; gap: 12px; align-items: center;">
                                        @if ($dv->HinhAnh)
                                            <img src="{{ $dv->HinhAnh }}" alt="Logo" style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid var(--border-color); flex-shrink: 0;">
                                        @else
                                            <div style="width: 48px; height: 48px; border-radius: 8px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #64748b; flex-shrink: 0;">🏢</div>
                                        @endif
                                        <div>
                                            <strong>{{ $dv->TenDonVi }}</strong><br>
                                            <span style="color: var(--neutral-grey);">{{ $dv->MaDonVi }}</span>
                                            <div style="margin-top: 6px; color: var(--neutral-grey);">{{ $dv->DiaChi }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $dv->Loai }}</td>
                                <td>
                                    <div>{{ $dv->NguoiDaiDien }}</div>
                                    <div style="color: var(--neutral-grey);">{{ $dv->Email }}</div>
                                    <div style="color: var(--neutral-grey);">{{ $dv->SoDienThoai }}</div>
                                </td>
                                <td>{{ $dv->ChuSoHuu ?? '—' }}</td>
                                <td>
                                    @if ($dv->TrangThai == 1)
                                        <span class="badge badge-active">Dang hoat dong</span>
                                    @else
                                        <span class="badge badge-inactive">Tam ngung</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a class="btn-action" href="{{ route('admin.don-vi-to-chuc.index', ['edit_id' => $dv->Id]) }}">Cap nhat</a>
                                        <form method="post" action="{{ route('admin.don-vi-to-chuc.destroy', $dv->Id) }}" onsubmit="return confirm('Xoa don vi nay?');">
                                            @csrf
                                            <button class="btn-action btn-danger" type="submit">Xoa</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center; color: var(--neutral-grey); padding: 24px;">Chua co don vi to chuc nao.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 16px;">
                {{ $donVis->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('HinhAnhFile').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('HinhAnhPreview');
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('HinhAnh').addEventListener('input', function(event) {
        const url = event.target.value;
        const previewImg = document.getElementById('HinhAnhPreview');
        if (url) {
            previewImg.src = url;
            previewImg.style.display = 'block';
        } else {
            previewImg.style.display = 'none';
        }
    });
</script>
@endsection
