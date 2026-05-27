<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TapTin extends Model
{
    use SoftDeletes;

    protected $table = 'TapTin';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'TenFile',
        'DuongDan',
        'LoaiFile',
        'KichThuoc',
        'NguoiTaiLenId',
        'NgayTaiLen',
    ];

    protected $casts = [
        'NgayTaiLen' => 'datetime',
    ];

    public function nguoiTaiLen(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiTaiLenId', 'Id');
    }
}
