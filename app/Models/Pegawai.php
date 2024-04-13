<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use HasFactory;
    protected $table = 'pegawais';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_user', 'tanggal_mulai_kerja'
    ];

    public function User(){
        return $this->belongsTo(User::class, 'id_user');
    }
}

