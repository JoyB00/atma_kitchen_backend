<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class Roles extends Model
{
    use HasFactory;

   protected $table = 'roles';

   protected $primaryKey = 'id';
   protected $fillable = [
    'role_name'
   ];

   
=======
class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_role'
    ];
>>>>>>> a14fd3df5dce9a529a1509fb9cbd453f6ac0f1b7
}
