<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignors extends Model
{
    use HasFactory;

    protected $table = 'consignors';

    protected $primaryKey = 'id';
    protected $fillable = [
        'consignor_name',
        'phone_number',
        'active',
    ];

    public function Product()
    {
        return $this->hasMany(Product::class, 'consignor_id');
    }
}
