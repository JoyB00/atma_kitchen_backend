<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HampersDetails extends Model
{
    use HasFactory;

    protected $table = 'hampers_details';

    protected $primaryKey = 'id';
    protected $fillable = [
<<<<<<< HEAD
        'hampers_id',
        'product_id',
        'ingredient_id'
    ];

    public function Hampers()
    {
        return $this->belongsTo(Hampers::class, 'hampers_id');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Ingredients()
    {
=======
        'hampers_id', 
        'product_id', 
        'ingredient_id'
    ];

    public function Hampers(){
        return $this->belongsTo(Hampers::class, 'hampers_id');
    }

    public function Product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Ingredients(){
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
        return $this->belongsTo(Ingredients::class, 'ingredient_id');
    }
}
