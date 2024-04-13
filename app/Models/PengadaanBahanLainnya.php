<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengadaanBahanLainnya extends Model
{
    use HasFactory;
    protected $table = 'pengadaan_bahan_lainnyas';
    
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_pegawai', 'nama_bahan', 'harga_bahan', 'kauntitas', 'tanggal_pengadaan', 'total_harga'
    ];

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
