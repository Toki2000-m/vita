<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // o Jenssegers\Mongodb\Eloquent\Model si usas ese paquete

class Usuarios extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'Usuarios';

    protected $primaryKey = '_id';

    protected $fillable = [
        'name_user',
        'correo_user',
        'imagen_user',
        'password_user',
        'rol_user',
        'estado_user',
        'fecha_creacion',
    ];

    // Ocultar password en JSON
    protected $hidden = ['password_user'];

    // No usar timestamps automáticos
    public $timestamps = false;

    // Forzar que el modelo use el nombre exacto de la colección
    public function getTable()
    {
        return $this->collection;
    }
}
