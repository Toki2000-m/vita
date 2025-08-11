<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Salida;

class DashboardController extends Controller
{
  public function index(Request $request)
{
    $nombre = session('name_user');

    $periodo = $request->input('periodo', 'mensual'); // Por defecto mensual
    $desde = $request->input('desde');
    $hasta = $request->input('hasta');

    // Determinar rango de fechas según filtro
    if ($periodo === 'semanal') {
        $inicio = Carbon::now()->startOfWeek();
        $fin = Carbon::now()->endOfWeek();
    } elseif ($periodo === 'personalizado' && $desde && $hasta) {
        // Validar fechas recibidas para evitar errores
        try {
            $inicio = Carbon::parse($desde)->startOfDay();
            $fin = Carbon::parse($hasta)->endOfDay();
        } catch (\Exception $e) {
            // Si las fechas no son válidas, usar rango mensual por defecto
            $inicio = Carbon::now()->startOfMonth();
            $fin = Carbon::now()->endOfMonth();
        }
    } else {
        // Por defecto mensual
        $inicio = Carbon::now()->startOfMonth();
        $fin = Carbon::now()->endOfMonth();
    }

    // Consultar ventas según rango y tipo "Venta"
    $ventas = Salida::where('tipo', 'Venta')
        ->whereBetween('fecha_salida', [$inicio, $fin])
        ->get();

    $productosVendidos = $ventas->groupBy('nombre_perfume')->map(function ($item) {
        return $item->sum('cantidad');
    })->sortDesc();

    $sucursalesVentas = $ventas->groupBy('almacen_salida')->map(function ($item) {
        return $item->sum('cantidad');
    })->sortDesc();

    $productosLabels = $productosVendidos->keys()->toArray();
    $productosData = $productosVendidos->values()->toArray();

    $sucursalesLabels = $sucursalesVentas->keys()->toArray();
    $sucursalesData = $sucursalesVentas->values()->toArray();

    return view('dashboard', compact('nombre', 
        'productosLabels', 'productosData', 
        'sucursalesLabels', 'sucursalesData'))
        ->with('periodo', $periodo)
        ->with('desde', $desde)
        ->with('hasta', $hasta);
}


}
