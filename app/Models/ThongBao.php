<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThongBao extends Model
{
    use SoftDeletes;

    protected $table = 'ThongBao';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'TieuDe',
        'NoiDung',
        'LoaiThongBao',
        'NgayGui',
        'NguoiGuiId',
        'NguoiNhanId',
        'DaDoc',
        'ThoiGianDoc',
    ];

    protected $casts = [
        'NgayGui' => 'datetime',
        'DaDoc' => 'boolean',
        'ThoiGianDoc' => 'datetime',
    ];

    public function nguoiGui(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiGuiId', 'Id');
    }

    public function nguoiNhan(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'NguoiNhanId', 'Id');
    }
}
