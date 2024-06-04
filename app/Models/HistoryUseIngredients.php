<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryUseIngredients extends Model
{
    use HasFactory;

    protected $table = 'history_use_ingredients';

    protected $primaryKey = 'id';
    protected $fillable = [
        'ingredient_id',
        'quantity',
        'date'
    ];


    public function Ingredients()
    {
        return $this->belongsTo(Ingredients::class, 'ingredient_id');
    }
}
