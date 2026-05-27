<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LichSuDuyetChuongTrinh extends Model
{
    use SoftDeletes;

    protected $table = 'LichSuDuyetChuongTrinh';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'ChuongTrinhId',
        'NguoiDuyetId',
        'TrangThai',
        'GhiChu',
        'ThoiGian',
    ];

    protected $casts = [
        'ThoiGian' => 'datetime',
    ];

    public function chuongTrinh(): BelongsTo
    {
        return $this->belongsTo(ChuongTrinhHienMau::class, 'ChuongTrinhId', 'Id');
    }

    public function nguoiDuyet(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiDuyetId', 'Id');
    }
}
