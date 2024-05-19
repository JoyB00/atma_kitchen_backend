<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carts extends Model
{
    use HasFactory;

    protected $table = "carts";
    protected $primaryKey = "id";
    protected $fillable = [
        'user_id',
        'product_id',
        'hampers_id',
        'status_item',
        'order_date',
        'quantity',
        'limit_item',
        'total_price'
    ];

    public function Products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Hampers()
    {
        return $this->belongsTo(Hampers::class, 'hampers_id');
    }
    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
