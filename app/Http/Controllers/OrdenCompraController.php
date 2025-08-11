<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdenCompra;

class OrdenCompraController extends Controller
{
    // Listar todas las órdenes
    public function index()
    {
        return response()->json(OrdenCompra::all());
    }

    // Mostrar una orden por ID
    public function show($id)
    {
        $orden = OrdenCompra::find($id);
        return $orden
            ? response()->json($orden)
            : response()->json(['error' => 'No encontrada'], 404);
    }

    // Buscar por número de orden
    public function buscarPorNumero(Request $request)
    {
        $orden = OrdenCompra::where('n_orden_compra', $request->n_orden_compra)->first();
        return $orden
            ? response()->json($orden)
            : response()->json(['error' => 'No encontrada'], 404);
    }

    // Crear nueva orden
    public function store(Request $request)
    {
        if ($request->n_orden_compra === 'undefined') {
    return response()->json(['error' => 'Número de orden inválido'], 422);
}

        $validatedData = $request->validate([
            'n_orden_compra' => 'required|string|unique:ordenes_compra,n_orden_compra',
            'id_perfume' => 'required|string',
            'proveedor' => 'required|string',
            'cantidad' => 'required|integer|min:1',
            'fecha' => 'required|date',
            'usuario_solicitante' => 'required|string',
            'precio_unitario' => 'required|numeric|min:0',
            'almacen' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $validatedData['precio_total'] = $validatedData['precio_unitario'] * $validatedData['cantidad'];
        $validatedData['estado'] = 'Pendiente';

        $orden = OrdenCompra::create($validatedData);

        return response()->json($orden, 201);
    }


    // Actualizar orden por ID
    public function update($id, Request $request)
    {
        $orden = OrdenCompra::find($id);
        if (!$orden) {
            return response()->json(['error' => 'No encontrada'], 404);
        }

        $orden->update($request->all());
        return response()->json($orden);
    }

    // Eliminar orden por ID
    public function destroy($id)
    {
        $orden = OrdenCompra::find($id);
        if (!$orden) {
            return response()->json(['error' => 'No encontrada'], 404);
        }

        $orden->delete();
        return response()->json(['mensaje' => 'Orden eliminada correctamente']);
    }

    // Cancelar orden con motivo
    public function cancelar(Request $request, $id)
    {
        $orden = OrdenCompra::find($id);

        if (!$orden) {
            return response()->json(['error' => 'Orden no encontrada'], 404);
        }

        if (strtolower($orden->estado) !== 'pendiente') {
            return response()->json(['error' => 'Solo se pueden cancelar órdenes pendientes'], 422);
        }

        $motivo = $request->input('motivo');
        if (!$motivo) {
            return response()->json(['error' => 'Motivo de cancelación requerido'], 422);
        }

        $orden->estado = 'Cancelada';
        $orden->observaciones = 'Cancelada por jefe de almacén: ' . $motivo;
        $orden->save();

        return response()->json(['mensaje' => '🚫 Orden cancelada correctamente']);
    }

    // Vistas
    public function vistaPrincipal()
    {
        return view('compras.index');
    }

    public function vistaCrear()
    {
        return view('compras.crear');
    }

    public function vistaEditar($id)
    {
        return view('compras.editar', ['id' => $id]);
    }
    public function obtenerUltimoNumeroOrden()
{
    $ultimaOrden = OrdenCompra::whereNotNull('n_orden_compra')
        ->where('n_orden_compra', '!=', 'undefined')
        ->orderBy('n_orden_compra', 'desc')
        ->first();

    if (!$ultimaOrden) {
        return response()->json(['nuevoNumeroOrden' => 'ORD-001']);
    }

    $ultimoNro = intval(substr($ultimaOrden->n_orden_compra, 4));
    $nuevoNro = $ultimoNro + 1;

    $nuevoNumeroOrden = 'ORD-' . str_pad($nuevoNro, 3, '0', STR_PAD_LEFT);

    return response()->json(['nuevoNumeroOrden' => $nuevoNumeroOrden]);
}

}
