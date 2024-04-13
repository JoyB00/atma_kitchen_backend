<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengadaanBahanBaku extends Model
{
    use HasFactory;
    protected $table = 'pengadaan_bahan_bakus';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_pegawai', 'tanggal_pengadaan_bahan_baku', 'total_harga'
    ];

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
