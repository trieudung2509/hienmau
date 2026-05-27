<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class NguoiDung extends Model
{
    use SoftDeletes;

    protected $table = 'NguoiDung';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'HoTen',
        'Email',
        'SoDienThoai',
        'MatKhauHash',
        'VaiTroId',
        'TrangThai',
    ];

    protected $hidden = [
        'MatKhauHash',
    ];

    public function vaiTro(): BelongsTo
    {
        return $this->belongsTo(VaiTro::class, 'VaiTroId', 'Id');
    }

    public function donViToChucs(): HasMany
    {
        return $this->hasMany(DonViToChuc::class, 'OwnerUserId', 'Id');
    }

    public function chuongTrinhsTao(): HasMany
    {
        return $this->hasMany(ChuongTrinhHienMau::class, 'NguoiTaoId', 'Id');
    }

    public function lichSuDuyets(): HasMany
    {
        return $this->hasMany(LichSuDuyetChuongTrinh::class, 'NguoiDuyetId', 'Id');
    }

    public function nguoiHienMau(): HasOne
    {
        return $this->hasOne(NguoiHienMau::class, 'NguoiDungId', 'Id');
    }

    public function thongBaosGui(): HasMany
    {
        return $this->hasMany(ThongBao::class, 'NguoiGuiId', 'Id');
    }

    public function thongBaosNhan(): HasMany
    {
        return $this->hasMany(ThongBao::class, 'NguoiNhanId', 'Id');
    }

    public function taptinsTaiLen(): HasMany
    {
        return $this->hasMany(TapTin::class, 'NguoiTaiLenId', 'Id');
    }
}
