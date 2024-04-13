<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';

   protected $primaryKey = 'id';
   protected $fillable = [
    'id_pegawawi', 'id_customer', 'id_delivery', 'tanggal_pesan', 'tanggal_lunas', 'tanggal_ambil', 'metode_pembayaran', 'status', 'bukti_pembayaran', 'poin_terpakai', 'poin_didapat', 'total_harga', 'tip'
   ];

   public function Pegawai(){
    return $this->belongsTo(Pegawai::class, 'id_pegawai');
   }

   public function Customer(){
    return $this->belongsTo(Customer::class, 'id_customer');
   }

   public function Delivery(){
    return $this->belongsTo(Delivery::class, 'id_delivery');
   }
   
}
