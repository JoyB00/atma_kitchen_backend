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
        return $this->belongsTo(Ingredients::class, 'ingredient_id');
    }
}
