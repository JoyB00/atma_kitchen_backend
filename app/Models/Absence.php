<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    use HasFactory;

    protected $table = 'absences';

    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id',
        'attendance_date',
    ];

    public function Employees()
    {
        return $this->belongsTo(Employees::class, 'employees_id');
    }
}
