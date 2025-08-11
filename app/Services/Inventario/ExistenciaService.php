<?php

// app/Services/Inventario/ExistenciaService.php

namespace App\Services\Inventario;

use App\Models\Existencia;
use MongoDB\BSON\ObjectId;

class ExistenciaService
{
    public static function ajustarStock($productoId, $almacenCodigo, $cantidad, $tipo = 'entrada')
    {
        $existencia = Existencia::firstOrCreate(
            [
                'producto_id' => new ObjectId($productoId),
                'almacen_id' => $almacenCodigo
            ],
            [
                'cantidad' => 0,
                'stock_actual' => 0,
                'stock_minimo' => 0,
                'stock_maximo' => 1000,
                'sku' => null
            ]
        );


        if ($tipo === 'entrada') {
            $existencia->cantidad += $cantidad;
        } elseif ($tipo === 'salida') {
            if ($existencia->cantidad < $cantidad) {
                throw new \Exception("🚨 Stock insuficiente: disponibles {$existencia->cantidad} pz");
            }
            $existencia->cantidad -= $cantidad;
        }

        $existencia->stock_actual = $existencia->cantidad;
        $existencia->save();

        return $existencia;
    }
}
