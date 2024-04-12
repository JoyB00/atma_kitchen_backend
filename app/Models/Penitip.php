<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penitip extends Model
{
    use HasFactory;
    protected $table = 'penitips';

    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_penitip', 'no_telp',
    ];
}
