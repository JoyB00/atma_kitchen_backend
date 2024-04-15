<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formula extends Model
{
    use HasFactory;

    protected $table = 'formulas';

    protected $primaryKey = 'id';
    protected $fillable = [
        'hampers_name',
        'hampers_price',
        'quantity'
    ];
}
