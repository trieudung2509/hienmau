<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HoSoSucKhoe extends Model
{
    use SoftDeletes;

    protected $table = 'HoSoSucKhoe';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'DangKyId',
        'HuyetAp',
        'NhipTim',
        'NhietDo',
        'CanNang',
        'Hemoglobin',
        'KetQua',
        'LyDoTuChoi',
        'Nhommau',
        'NguoiKham',
        'ThoiGianKham',
    ];

    protected $casts = [
        'NhietDo' => 'decimal:1',
        'CanNang' => 'decimal:2',
        'Hemoglobin' => 'decimal:2',
        'ThoiGianKham' => 'datetime',
    ];

    public function dangKyHienMau(): BelongsTo
    {
        return $this->belongsTo(DangKyHienMau::class, 'DangKyId', 'Id');
    }

    public function hoSoHienMaus(): HasMany
    {
        return $this->hasMany(HoSoHienMau::class, 'HoSoSucKhoeId', 'Id');
    }
}
