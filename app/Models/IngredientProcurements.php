<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientProcurements extends Model
{
    use HasFactory;
    protected $table = 'ingredient_procurements';

    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id',
        'procurement_date',
        'total_price'
    ];

    public function Employees()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
