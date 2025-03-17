<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori';
    protected $primaryKey = 'kategori_id';
    public $timestamps = true; // agar Laravel menulis created_at & updated_at secara otomatis

    // Jika ingin mass-assignment (create/update) lebih mudah:
    protected $fillable = [
        'kategori_kode',
        'kategori_nama'
        // created_at & updated_at akan ditangani otomatis
    ];
}
