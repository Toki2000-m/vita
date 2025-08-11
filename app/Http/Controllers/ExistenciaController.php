<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Perfumes;

class ExistenciaController extends Controller
{
    /**
     * Vista principal del módulo de existencias.
     */
    public function vistaPrincipal()
    {
        return view('existencias.index');
    }

    /**
     * Endpoint para consultar existencias desde la colección perfumes.
     */
    public function index(Request $request)
    {
        $query = Perfumes::query();

        // Filtrar por nombre de perfume
        if ($request->filled('nombre')) {
            $query->where('name_per', 'like', '%' . $request->nombre . '%');
        }

        // Filtrar por almacén específico
        if ($request->filled('almacen_codigo')) {
            $query->where("stock_por_almacen.{$request->almacen_codigo}", '>', 0);
        }

        // Solo perfumes activos
        $query->where('estado', 'Activo');

        // Ordenar por nombre
        $query->orderBy('name_per');

        $perfumes = $query->get();

        return response()->json($perfumes);
    }
}
