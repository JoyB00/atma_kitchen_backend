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
        'is_bolos'
    ];

    public function Employees(){
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
