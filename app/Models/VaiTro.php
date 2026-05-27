<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VaiTro extends Model
{
    protected $table = 'VaiTro';
    protected $primaryKey = 'Id';

    protected $fillable = [
        'TenVaiTro',
    ];

    public function nguoiDungs(): HasMany
    {
        return $this->hasMany(NguoiDung::class, 'VaiTroId', 'Id');
    }
}
