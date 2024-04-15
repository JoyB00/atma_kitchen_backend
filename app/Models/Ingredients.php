<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredients extends Model
{
    use HasFactory;

    protected $table = 'ingredients';

    protected $primaryKey = 'id';
    protected $fillable = [
        'ingredient_name', 
        'quantity', 
        'unit'
    ];
}
