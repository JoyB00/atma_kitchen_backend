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
    use HasFactory, Notifiable, HasApiTokens;
=======
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
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
    protected $table = 'users';

    protected $primaryKey = 'id';
    protected $fillable = [
        'role_id',
        'fullName',
        'email',
        'password',
        'phoneNumber',
        'gender',
        'dateOfBirth',
        'verify_key',
        'active',
        'email_verified_at',
    ];


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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
<<<<<<< HEAD
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    protected function Roles()
    {
        return $this->belongsTo(Roles::class, 'role_id');
=======
    protected function casts(): array
>>>>>>> a14fd3df5dce9a529a1509fb9cbd453f6ac0f1b7
    {
       return $this->belongsTo(Roles::class, 'role_id');
>>>>>>> 1910d06b13b6f454c174fe8fe15088f5c6b6f4ec
    }
}
