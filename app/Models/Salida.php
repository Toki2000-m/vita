<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Salida extends Model
{
    protected $connection = 'mongodb'; // si tienes varias conexiones
    protected $collection = 'salidas';

    protected $fillable = [
        'nombre_perfume',
        'almacen_salida',
        'cantidad',
        'tipo',
        'fecha_salida',
        'usuario_registro',
    ];

    protected $dates = [
        'fecha_salida',
    ];
}
