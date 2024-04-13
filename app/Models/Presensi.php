<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensis';
    
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_pegawai', 'tanggal_absensi', 'is_bolos'
    ];

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
