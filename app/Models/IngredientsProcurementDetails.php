<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientsProcurementDetails extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $table = 'ingredients_prcmnt_dtl';
=======
    protected $table = 'ingredients_procurement_details';
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec

    protected $primaryKey = 'id';
    protected $fillable = [
        'ingredient_procurement_id',
<<<<<<< HEAD
        'ingredient_id',
=======
        'ingredient_id', 
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
        'price',
        'quantity',
        'total_price'
    ];

<<<<<<< HEAD
    public function PengadaanBahanBaku()
    {
        return $this->belongsTo(IngredientProcurements::class, 'ingredient_procurement_id');
    }

    public function Ingredients()
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id');
    }

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
=======
    public function PengadaanBahanBaku(){
        return $this->belongsTo(IngredientProcurements::class, 'ingredient_procurement_id');
    }

    public function Ingredients(){
        return $this->belongsTo(Ingredients::class, 'ingredient_id');
    }

    public function Product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

}

>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
