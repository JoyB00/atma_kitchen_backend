<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientsProcurementDetails extends Model
{
    use HasFactory;

    protected $table = 'ingredients_prcmnt_dtl';

    protected $primaryKey = 'id';
    protected $fillable = [
        'ingredient_procurement_id',
        'ingredient_id',
        'price',
        'quantity',
        'total_price'
    ];

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
