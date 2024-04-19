<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'point',
        'nominal_balance'
    ];

    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
