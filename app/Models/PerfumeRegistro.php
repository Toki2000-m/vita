<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PerfumeRegistro extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'perfumes';

    public $timestamps = false; // Usas createdAt y updatedAt manualmente

    protected $fillable = [
        '_id', // necesario para asignar el ID manualmente
        'name_per',
        'descripcion_per',
        'categoria_per',
        'precio_venta_per',
        'stock_per',
        'stock_actual',
        'stock_minimo_per',
        'ubicacion_per',
        'fecha_expiracion',
        'estado',
        'imagen_url',
        'marca',
        'createdAt',
        'updatedAt',
        'stock_por_almacen',
    ];

    protected $casts = [
        'fecha_expiracion' => 'datetime',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setTable('perfumes'); // Forzar la colección a usar
    }
}
