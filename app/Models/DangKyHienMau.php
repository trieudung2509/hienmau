<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DangKyHienMau extends Model
{
    use SoftDeletes;

    protected $table = 'DangKyHienMau';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'ChuongTrinhId',
        'NguoiHienMauId',
        'ThoiGianDangKy',
        'TrangThai',
        'GhiChu',
    ];

    protected $casts = [
        'ThoiGianDangKy' => 'datetime',
    ];

    public function chuongTrinh(): BelongsTo
    {
        return $this->belongsTo(ChuongTrinhHienMau::class, 'ChuongTrinhId', 'Id');
    }

    public function nguoiHienMau(): BelongsTo
    {
        return $this->belongsTo(NguoiHienMau::class, 'NguoiHienMauId', 'Id');
    }

    public function hoSoSucKhoe(): HasOne
    {
        return $this->hasOne(HoSoSucKhoe::class, 'DangKyId', 'Id');
    }
}
