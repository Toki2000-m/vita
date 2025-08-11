<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimientos_inventario';
    protected $connection = 'mongodb';
    public $timestamps = false;

    protected $fillable = [
        'tipo',
        'subtipo',
        'perfume_id',
        'almacen_origen_id',
        'almacen_destino_id',
        'cantidad',
        'motivo',
        'referencia',
        'usuario_id',
        'timestamp',
        'traspaso_id',
        'factura_id',
        'proveedor_id'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    /**
     * Relación con Perfumes
     */
    public function perfume()
    {
        return $this->belongsTo(Perfumes::class, 'perfume_id', '_id');
    }

    /**
     * Relación con Almacén origen
     */
    public function almacenOrigen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_origen_id', '_id');
    }

    /**
     * Relación con Almacén destino
     */
    public function almacenDestino()
    {
        return $this->belongsTo(Almacen::class, 'almacen_destino_id', '_id');
    }

    /**
     * Relación con Usuario
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id', '_id');
    }

    /**
     * Relación con Proveedor (si existe modelo)
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id', '_id');
    }

    /**
     * Accesor para nombre del perfume
     */
    public function getNombrePerfumeAttribute()
    {
        return $this->perfume->name_per ?? 'Sin nombre';
    }

    /**
     * Accesor para código de almacén origen
     */
    public function getCodigoAlmacenOrigenAttribute()
    {
        return $this->almacenOrigen->codigo ?? 'Desconocido';
    }

    /**
     * Accesor para código de almacén destino
     */
    public function getCodigoAlmacenDestinoAttribute()
    {
        return $this->almacenDestino->codigo ?? 'Desconocido';
    }
}
