<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salaries extends Model
{
    use HasFactory;

    protected $table = 'salaries';

    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id', 
        'pay_date', 
        'daily_salary', 
        'bonus', 
        'total_salaries'
    ];

    public function Employees(){
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
