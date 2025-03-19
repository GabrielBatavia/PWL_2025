<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;

    protected $table = 'm_supplier';
    protected $primaryKey = 'supplier_id'; 
    public $timestamps = true; // agar Laravel mengisi created_at & updated_at

    protected $fillable = [
        'supplier_nama',
        'supplier_alamat'
    ];
}
