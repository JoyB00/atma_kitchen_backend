<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hampers extends Model
{
        use HasFactory;

        protected $table = 'hampers';

        protected $primaryKey = 'id';
        protected $fillable = [
                'hampers_name',
                'hampers_price',
                'quantity',
                'hampers_picture',
                'active',
        ];

        public function HampersDetail()
        {
                return $this->hasMany(HampersDetails::class, 'hampers_id', 'id');
        }
}
