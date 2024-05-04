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
<<<<<<< HEAD
        'user_id',
        'point',
        'nominal_balance'
    ];

    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id');
=======
        'user_id', 
        'point', 
        'balance_nominal'
    ];

    public function Users(){
        return $this->belongsTo(Users::class, 'user_id');
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
    }
}
