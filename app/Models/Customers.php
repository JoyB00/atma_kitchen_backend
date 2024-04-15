<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id', 
        'point', 
        'balance_nominal'
    ];

    public function Users(){
        return $this->belongsTo(Users::class, 'user_id');
    }
}
