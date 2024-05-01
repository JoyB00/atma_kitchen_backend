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
        'quantity',
        'product_price',
        'product_pict',
        'description'
    ];

    public function Categories()
    {
        return  $this->belongsTo(Categories::class, 'category_id');
    }

    public function Consignors()
    {
        return $this->belongsTo(Consignors::class, 'consignor_id');
    }
}
