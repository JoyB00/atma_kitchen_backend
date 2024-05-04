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
<<<<<<< HEAD
        'consignor_id',
        'category_id',
        'product_name',
        'quantity',
        'product_price',
        'product_picture',
        'description'
    ];

    // public function scopeFilter($query)
    // {
    //     
    // }

=======
        'consignor_id', 
        'category_id', 
        'product_name', 
        'quantity', 
        'product_price', 
        'product_pict', 
        'description'
    ];

>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
    public function Categories()
    {
        return  $this->belongsTo(Categories::class, 'category_id');
    }

    public function Consignors()
    {
        return $this->belongsTo(Consignors::class, 'consignor_id');
    }
}
