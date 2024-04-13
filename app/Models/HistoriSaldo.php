<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriSaldo extends Model
{
    use HasFactory;

    protected $table = 'histori_saldos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_customer', 'nominal_saldo', 'nama_bank', 'nomor_rekening', 'tanggal', 'keterangan'
    ];

    public function Customer(){
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}
