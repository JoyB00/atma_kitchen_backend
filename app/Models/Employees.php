<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    protected $table = 'employees';

    protected $primaryKey = 'id';
    protected $fillable = [
<<<<<<< HEAD
        'user_id',
        'work_start_date'
    ];

    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
=======
        'user_id', 
        'work_start_date'
    ];

    public function Users(){
        return $this->belongsTo(Users::class, 'user_id');
    }
}

>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
