<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;  // Importa el modelo
use App\Models\OrdenCompra;  // Importa el modelo


class TransaccionesController extends Controller
{
    
public function index()
{
    $proveedores = Proveedor::all();
    $ordenesPendientes = OrdenCompra::where('estado', 'Pendiente')->get();

    return view('transacciones.index', compact('proveedores', 'ordenesPendientes'));

}
}
