<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LimitProduk extends Model
{
    use HasFactory;
    protected $table = 'limit_produks';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_produk', 'jumlah_limit', 'tanggal_produksi'
    ];

    public function Produk(){
        return $this->belongsTo(Produk::class, 'id_produk');
    }
}

