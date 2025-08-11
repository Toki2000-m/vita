<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Perfumes extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'perfumes';

    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name_per',
        'stock_per',
        // otros campos si los necesitas
    ];

    protected $dates = ['updatedAt', 'fecha_expiracion'];

    /**
     * Relación con movimientos de inventario
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class, 'perfume_id', '_id');
    }

    /**
     * Relación con salidas (si decides guardar perfume_id en Salidas)
     */
    public function salidas()
    {
        return $this->hasMany(Salidas::class, 'perfume_id', '_id');
    }

    /**
     * Accesor para stock formateado
     */
    public function getStockFormateadoAttribute()
    {
        return number_format($this->stock_per ?? 0, 0, '.', ',') . ' unidades';
    }

    /**
     * Accesor para nombre capitalizado
     */
    public function getNombreCapitalizadoAttribute()
    {
        return ucwords(strtolower($this->name_per ?? 'Sin nombre'));
    }

    /**
     * ¿Está expirado?
     */
    public function getExpiradoAttribute()
    {
        return $this->fecha_expiracion && $this->fecha_expiracion->isPast();
    }
}
