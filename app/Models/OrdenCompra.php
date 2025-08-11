<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model; // Correcto para el driver oficial

class OrdenCompra extends Model
{
    protected $connection = 'mongodb'; // conexión MongoDB
    protected $table = 'ordenes_compra'; // nombre de la colección

    public $timestamps = true; // timestamps activados

    protected $fillable = [
        'n_orden_compra',
        'id_perfume',
        'proveedor',
        'cantidad',
        'fecha',
        'usuario_solicitante',
        'precio_unitario',
        'precio_total',
        'almacen',
        'observaciones',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
