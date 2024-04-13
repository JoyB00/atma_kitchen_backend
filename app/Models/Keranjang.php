<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $table = 'keranjangs';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_transaksi', 'id_produk', 'id_hampers', 'kuantitas', 'harga_satuan', 'total_harga'
    ];

    public function Transaksi(){
        return $this->belongsTo(Transaksi::class, 'id_transaksi');
    }

    public function Produk(){
        return $this->belongsTo(Produk::class, 'id_produk');
    }

    public function Hampers(){
        return $this->belongsTo(Hampers::class, 'id_hampers');
    }
}

