<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
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
        'verification_code'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function Roles()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }
}
