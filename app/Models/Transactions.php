<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    protected $table = 'transactions';

    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id',
        'customer_id',
        'delivery_id',
        'order_date',
        'paidoff_date',
        'pickup_date',
        'payment_method',
        'status',
        'payment_evidence',
        'used_point',
        'earned_point',
        'total_price',
        'tip'
    ];

    public function Employees()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function Customers()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function Deliveries()
    {
        return $this->belongsTo(Deliveries::class, 'delivery_id');
    }
}
