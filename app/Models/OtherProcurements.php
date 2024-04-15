<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherProcurement extends Model
{
    use HasFactory;
    protected $table = 'other_procurements';
    
    protected $primaryKey = 'id';
    protected $fillable = [
        'employees_id', 
        'ingredient_name', 
        'price', 
        'quantity', 'procurement_date', 
        'total_price'
    ];

    public function Employees(){
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
