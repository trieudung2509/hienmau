<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NguoiHienMau extends Model
{
    use SoftDeletes;

    protected $table = 'NguoiHienMau';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'PublicId',
        'NguoiDungId',
        'CCCD',
        'NgaySinh',
        'GioiTinh',
        'NhomMau',
        'DiaChi',
        'CanNang',
        'NgheNghiep',
        'LanHienGanNhat',
        'SoLanDaHien',
        'TrangThaiSucKhoe',
    ];

    protected $casts = [
        'NgaySinh' => 'date',
        'LanHienGanNhat' => 'datetime',
        'CanNang' => 'decimal:2',
    ];

    public function nguoiDung(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiDungId', 'Id');
    }

    public function dangKyHienMaus(): HasMany
    {
        return $this->hasMany(DangKyHienMau::class, 'NguoiHienMauId', 'Id');
    }

    public function hoSoHienMaus(): HasMany
    {
        return $this->hasMany(HoSoHienMau::class, 'NguoiHienMauId', 'Id');
    }
}
