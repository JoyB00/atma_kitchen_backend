<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLimits extends Model
{
    use HasFactory;
    protected $table = 'product_limits';

    protected $primaryKey = 'id';
    protected $fillable = [
        'product_id', 'limit_amount', 'production_date'
    ];

    public function Product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
}

