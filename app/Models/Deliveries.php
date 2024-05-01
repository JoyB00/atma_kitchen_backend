<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deliveries extends Model
{
    use HasFactory;

    protected $table = 'deliveries';

    protected $primaryKey = 'id';
    protected $fillable = [
        'delivery_method', 'distance', 'shipping_cost', 'recipient_address'
    ];
}
