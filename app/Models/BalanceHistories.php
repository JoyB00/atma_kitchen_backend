<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceHistories extends Model
{
    use HasFactory;

    protected $table = 'balance_histories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'customer_id', 
        'nominal_balance', 
        'bank_name', 
        'account_number', 
        'date', 
        'status',
        'detail_information'
    ];

    public function Customers(){
        return $this->belongsTo(Customers::class, 'customer_id');
    }
}
