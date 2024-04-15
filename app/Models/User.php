<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Users extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';

    protected $primaryKey = 'id';
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone_number',
        'gender',
        'birth_date',
    ];

    protected function Roles()
    {
       return $this->belongsTo(Roles::class, 'role_id');
    }
}
