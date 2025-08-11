<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class AjusteInventario extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'ajustes_inventario';

    protected $fillable = [
        'producto_id',
        'almacen_id',
        'cantidad_ajustada',
        'motivo',
        'tipo_ajuste',
        'autorizado_por',
        'timestamp',
        'folio_ajuste'
    ];
}
