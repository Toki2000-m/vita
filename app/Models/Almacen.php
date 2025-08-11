<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Almacen extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'almacenes';

    protected $fillable = [
        'codigo',
        'nombre',
        'direccion',
        'telefono',
        'updated_at',
    ];
}
