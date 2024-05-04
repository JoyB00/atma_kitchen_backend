<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendances extends Model
{
    use HasFactory;

    protected $table = 'attendances';
    
    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id', 
        'attendance_date', 
<<<<<<< HEAD
        'is_absence'
=======
        'is_bolos'
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
    ];

    public function Employees(){
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
