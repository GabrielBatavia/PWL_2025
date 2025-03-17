<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory;

    protected $table      = 'm_barang';
    protected $primaryKey = 'barang_id';
    public $timestamps    = true; // created_at & updated_at

    protected $fillable = [
        'kategori_id',
        'barang_kode',
        'barang_nama',
        'harga_beli',
        'harga_jual'
    ];

    public function kategori()
    {
        return $this->belongsTo(\App\Models\KategoriModel::class, 'kategori_id', 'kategori_id');
    }
}
