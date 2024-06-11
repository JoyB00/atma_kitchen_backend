<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $primaryKey = 'id';
    protected $fillable = [
        'consignor_id',
        'category_id',
        'product_name',
        'ready_stock',
        'daily_stock',
        'product_status',
        'product_price',
        'product_picture',
        'active',
        'description'
    ];

    public function Categories()
    {
        return  $this->belongsTo(Categories::class, 'category_id');
    }

    public function TransactionDetail()
    {
        return $this->hasMany(TransactionDetail::class, 'product_id');
    }

    public function Consignor()
    {
        return $this->belongsTo(Consignors::class, 'consignor_id');
    }

    public function AllLimit()
    {
        return $this->hasMany(ProductLimits::class, 'product_id', 'id');
    }

    public function AllRecipes()
    {
        return $this->hasMany(Recipes::class, 'product_id', 'id');
    }
}
