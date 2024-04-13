<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id_role',
        'nama',
        'email',
        'password',
        'jenis_kelamin',
        'tanggal_lahir',
    ];

    protected function Role()
    {
       return $this->belongsTo(Role::class, 'id_role');
    }
}
