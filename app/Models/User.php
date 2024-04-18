<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Users extends Authenticatable
{
<<<<<<< HEAD
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
=======
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $timestamps = false;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_role', 'fullName', 'email', 'password', 'phoneNumber', 'gender', 'dateOfBirth', 'active', 'verify_key'
    ];


    public function Role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
>>>>>>> a14fd3df5dce9a529a1509fb9cbd453f6ac0f1b7
    {
       return $this->belongsTo(Roles::class, 'role_id');
    }
}
