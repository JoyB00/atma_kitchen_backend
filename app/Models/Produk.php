<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_penitip', 'id_kategori', 'nama_produk', 'kuantitas', 'harga_produk', 'foto_produk', 'deskripsi'
    ];

    public function Kategori()
    {
        return  $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function Penitip()
    {
        return $this->belongsTo(Penitip::class, 'id_penitip');
    }
}
