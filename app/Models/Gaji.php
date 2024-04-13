<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    use HasFactory;

    protected $table = 'gajis';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_pegawai', 'tanggal_gajian', 'gaji_harian', 'bonus', 'total_gaji'
    ];

    public function Pegawai(){
        return $this->belongsTo(Pegawai::class, 'id_pegawai');
    }
}
