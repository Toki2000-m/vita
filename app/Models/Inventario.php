<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Inventario extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'inventarios'; // ← Este es el cambio clave
}
