<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Existencia extends Model
{
    protected $collection = 'existencias';

    protected $fillable = [
        'producto_id',
        'almacen_id',
        'cantidad',
        'stock_actual',
        'stock_minimo',
        'stock_maximo',
        'sku',
        'updated_at'
    ];

    // ...
}
