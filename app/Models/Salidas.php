<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Salidas extends Model
{
    protected $collection = 'salidas';

    /**
     * Relación con el usuario responsable del registro
     */
    public function responsable()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro', '_id');
    }

    /**
     * Relación opcional con el almacén de salida
     * (si tienes un modelo Almacen y el campo es trazable)
     */
    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'almacen_salida', '_id');
    }

    /**
     * Relación opcional con Perfumes si decides guardar perfume_id en el futuro
     */
    public function perfume()
    {
        return $this->belongsTo(Perfumes::class, 'perfume_id', '_id');
    }

    /**
     * Accesor para nombre de perfume, prioriza relación si existe
     */
    public function getNombrePerfumeAttribute()
    {
        return $this->perfume->name_per ?? $this->attributes['nombre_perfume'] ?? 'Sin nombre';
    }
}
