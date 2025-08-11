<?php

namespace App\Http\Controllers;

use App\Models\Inventario;



class InventarioController extends Controller
{
    public function verInventarios()
    {
        try {
            $inventarios = Inventario::all(); // obtiene todos los documentos

            return response()->json([
                'status' => 'ok',
                'total' => $inventarios->count(),
                'datos' => $inventarios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }
}

