<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'address';

    protected $primaryKey = 'id';
    protected $fillable = ['id_customer', 'subdistrict', 'city', 'postal_code', 'full_address'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer');
    }
}
