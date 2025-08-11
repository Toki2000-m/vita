<?php

namespace App\Http\Controllers;

use App\Models\AjusteInventario;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;

class AjusteInventarioController extends Controller
{
    public function registrarAjuste(Request $request)
    {
        try {
            $ajustes_inventario = AjusteInventario::create([
                'producto_id' => $request->producto_id,
                'almacen_id' => $request->almacen_id,
                'cantidad_ajustada' => $request->cantidad_ajustada,
                'motivo' => $request->motivo,
                'tipo_ajuste' => $request->tipo_ajuste, // 'entrada' o 'salida'
                'autorizado_por' => [
                    'usuario_id' => $request->usuario_id,
                    'nombre' => $request->usuario_nombre
                ],
                'timestamp' => now(),
                'folio_ajuste' => $request->folio_ajuste
            ]);

            MovimientoInventario::create([
                'producto_id' => $ajustes_inventario->producto_id,
                'almacen_id' => $ajustes_inventario->almacen_id,
                'cantidad' => abs($ajustes_inventario->cantidad_ajustada),
                'tipo' => $ajustes_inventario->tipo_ajuste,
                'referencia' => 'ajuste_inventario',
                'referencia_id' => $ajustes_inventario->_id,
                'folio_movimiento' => $ajustes_inventario->folio_ajuste,
                'timestamp' => $ajustes_inventario->timestamp
            ]);

            return response()->json([
                'status' => 'ok',
                'mensaje' => 'Ajuste y movimiento registrados.',
                'datos' => [
                    'ajuste_id' => $ajustes_inventario->_id,
                    'folio' => $ajustes_inventario->folio_ajuste
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }
}
