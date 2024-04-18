<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use HasFactory;

    protected $table = 'addresses';

    protected $primaryKey = 'id';
    protected $fillable = [
        'customer_id',
        'subdistrict',
        'city',
        'postal_code',
        'complete_address'
        ];
    
    public function Customers(){
        return $this->belongsTo(Customers::class, 'customer_id');
    }

}
