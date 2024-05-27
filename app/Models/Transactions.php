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
        'payment_amount',
        'used_point',
        'earned_point',
        'total_price',
        'tip'
    ];

    public function Employee()
    {
        return $this->belongsTo(Employees::class, 'employee_id');
    }

    public function Customer()
    {
        return $this->belongsTo(Customers::class, 'customer_id');
    }

    public function Delivery()
    {
        return $this->belongsTo(Deliveries::class, 'delivery_id');
    }

    public function TransactionDetails()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id', 'id');
    }
}
