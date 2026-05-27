<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChuongTrinhHienMau extends Model
{
    use SoftDeletes;

    protected $table = 'ChuongTrinhHienMau';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'PublicId',
        'TenChuongTrinh',
        'MoTa',
        'Banner',
        'DonViToChucId',
        'DiaChi',
        'ThoiGianBatDau',
        'ThoiGianKetThuc',
        'ThoiGianMoDangKy',
        'DangDienRa',
        'SoLuongDuKien',
        'TrangThai',
        'NguoiTaoId',
    ];

    protected $casts = [
        'ThoiGianBatDau' => 'datetime',
        'ThoiGianKetThuc' => 'datetime',
        'ThoiGianMoDangKy' => 'datetime',
        'DangDienRa' => 'boolean',
    ];

    public function donViToChuc(): BelongsTo
    {
        return $this->belongsTo(DonViToChuc::class, 'DonViToChucId', 'Id');
    }

    public function nguoiTao(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiTaoId', 'Id');
    }

    public function lichSuDuyets(): HasMany
    {
        return $this->hasMany(LichSuDuyetChuongTrinh::class, 'ChuongTrinhId', 'Id');
    }

    public function dangKyHienMaus(): HasMany
    {
        return $this->hasMany(DangKyHienMau::class, 'ChuongTrinhId', 'Id');
    }

    public function hoSoHienMaus(): HasMany
    {
        return $this->hasMany(HoSoHienMau::class, 'ChuongTrinhId', 'Id');
    }
}
