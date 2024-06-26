<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employees extends Model
{
    use HasFactory;
    protected $table = 'employees';

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'work_start_date'
    ];

    public function Users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Absence()
    {
        return $this->hasMany(Absence::class);
    }

    public function Salaries()
    {
        return $this->hasMany(Salaries::class, 'employee_id', 'id');
    }
}
