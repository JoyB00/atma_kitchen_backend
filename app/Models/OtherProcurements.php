<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherProcurements extends Model
{
    use HasFactory;
    protected $table = 'other_procurements';

    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id',
        'item_name',
        'price',
        'quantity', 'procurement_date',
        'total_price'
    ];

    public function Employees()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}
