<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Usuario extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'Usuarios';

    protected $fillable = [
        'name_user', 'correo_user', 'password_user', 'rol_user', 'estado_user', 'imagen_user'
    ];

    protected $hidden = ['password_user'];
}
