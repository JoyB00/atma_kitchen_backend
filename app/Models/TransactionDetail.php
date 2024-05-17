<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $table = 'transaction_detail';

    protected $primaryKey = 'id';
    protected $fillable = [
        'transaction_id',
        'product_id',
        'hampers_id',
        'quantity',
        'price',
        'total_price'
    ];

    public function Transaction()
    {
        return $this->belongsTo(Transactions::class, 'transaction_id');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Hampers()
    {
        return $this->belongsTo(Hampers::class, 'hampers_id');
    }
}
