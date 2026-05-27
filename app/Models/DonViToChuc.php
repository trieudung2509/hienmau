<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DonViToChuc extends Model
{
    use SoftDeletes;

    protected $table = 'DonViToChuc';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'TenDonVi',
        'MaDonVi',
        'Loai',
        'Email',
        'SoDienThoai',
        'DiaChi',
        'MoTa',
        'NguoiDaiDien',
        'TrangThai',
        'OwnerUserId',
    ];

    public function ownerUser(): BelongsTo
    {
        return $this->belongsTo(NguoiDung::class, 'OwnerUserId', 'Id');
    }

    public function chuongTrinhHienMaus(): HasMany
    {
        return $this->hasMany(ChuongTrinhHienMau::class, 'DonViToChucId', 'Id');
    }
}
