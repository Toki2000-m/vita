<?php
namespace App\Http\Controllers;

use App\Models\Proveedor;

class ProveedorController extends Controller
{
    // Método para traer solo id y nombre para autocompletado
    public function listarProveedoresMinimos()
    {
        $proveedores = Proveedor::select('_id', 'nombre_proveedor')->get();

        // Convertir _id a string (por si usas MongoDB)
        $proveedores = $proveedores->map(function($p) {
            return [
                '_id' => (string) $p->_id,
                'nombre_proveedor' => $p->nombre_proveedor,
            ];
        });

        return response()->json($proveedores);
    }

    public function mostrarFormularioEntrada()
    {
        $proveedores = Proveedor::all();
        return view('transacciones.partials.entradas', compact('proveedores'));
    }
}

