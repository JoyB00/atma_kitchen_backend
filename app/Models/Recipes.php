<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipes extends Model
{
    use HasFactory;
    protected $table = 'recipes';
    
    protected $primaryKey = 'id';
    protected $fillable = [
        'ingredient_id', 
        'product_id', 
        'quantity'
    ];

    public function Ingredients() {
        return $this->belongsTo(Ingredients::class,'ingredient_id');
    }

    public function Product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
