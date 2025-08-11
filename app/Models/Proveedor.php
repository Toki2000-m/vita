<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Proveedor extends Model
{
    protected $connection = 'mongodb';
    protected $table = 'proveedores';

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'nombre_proveedor',
        'rfc',
        'contacto',
        'telefono',
        'email',
        'direccion',
        'estado',
        'fecha_registro',
    ];

    // Si quieres que Laravel maneje timestamps automáticamente (opcional)
    public $timestamps = false; // Cambia a true si tienes created_at y updated_at en Mongo
}
