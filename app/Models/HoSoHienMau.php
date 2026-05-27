<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoSoHienMau extends Model
{
    use SoftDeletes;

    protected $table = 'HoSoHienMau';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'NguoiHienMauId',
        'ChuongTrinhId',
        'HoSoSucKhoeId',
        'LuongMau',
        'ThoiGianHien',
        'KetQuaSauHien',
        'GhiChu',
    ];

    protected $casts = [
        'ThoiGianHien' => 'datetime',
    ];

    public function nguoiHienMau(): BelongsTo
    {
        return $this->belongsTo(NguoiHienMau::class, 'NguoiHienMauId', 'Id');
    }

    public function chuongTrinh(): BelongsTo
    {
        return $this->belongsTo(ChuongTrinhHienMau::class, 'ChuongTrinhId', 'Id');
    }

    public function hoSoSucKhoe(): BelongsTo
    {
        return $this->belongsTo(HoSoSucKhoe::class, 'HoSoSucKhoeId', 'Id');
    }
}
