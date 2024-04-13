<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;
    protected $table = 'reseps';
    
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_bahan_baku', 'id_produk', 'kuantitas'
    ];

    public function BahanBaku() {
        return $this->belongsTo(BahanBaku::class,'id_bahan_baku');
    }

    public function Produk(){
        return $this->belongsTo(Produk::class,'id_produk');
    }
}
