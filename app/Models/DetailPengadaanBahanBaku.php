<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengadaanBahanBaku extends Model
{
    use HasFactory;

    protected $table = 'detail_pengadaan_bahan_bakus';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_pengadaan_bahan_baku', 'id_bahan_baku', 'id_produk'
    ];

    public function PengadaanBahanBaku(){
        return $this->belongsTo(PengadaanBahanBaku::class, 'id_pengadaan_bahan_baku');
    }

    public function BahanBaku(){
        return $this->belongsTo(BahanBaku::class, 'id_bahan_baku');
    }

    public function Produk(){
        return $this->belongsTo(Produk::class, 'id_produk');
    }

}

