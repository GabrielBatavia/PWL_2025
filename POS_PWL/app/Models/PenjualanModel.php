<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    protected $table = 't_penjualan';
    protected $primaryKey = 'penjualan_id';

    protected $fillable = [
        'user_id',
        'pembeli',
        'penjualan_kode',
        'penjualan_tanggal',
    ];

    /**
     * Relasi ke detail penjualan (t_penjualan_detail)
     */
    public function details()
    {
        return $this->hasMany(
            \App\Models\PenjualanDetailModel::class,
            'penjualan_id',   // FK in t_penjualan_detail
            'penjualan_id'    // PK in t_penjualan
        );
    }

    /**
     * (Opsional) relasi ke user, kalau butuh
     */
    public function user()
    {
        return $this->belongsTo(
            \App\Models\UserModel::class,
            'user_id',
            'user_id'
        );
    }
}
