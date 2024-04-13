<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $primaryKey = 'id';
    protected $fillable = ['id_user', 'poin', 'nominal_saldo'];

    public function User(){
        return $this->belongsTo(User::class, 'id_user');
    }
}
