<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hampers extends Model
{
    use HasFactory;
<<<<<<< HEAD

=======
    
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
    protected $table = 'hampers';

    protected $primaryKey = 'id';
    protected $fillable = [
<<<<<<< HEAD
        'hampers_name',
        'hampers_price',
        'quantity',
        'hampers_picture'
=======
        'hampers_name', 
        'hampers_price', 
        'quantity'
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
    ];
}
