<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientoInventario;
use App\Models\Salidas;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AnalisisController extends Controller
{
    public function inventario()
    {
        return view('analisis.inventario');
    }

    public function getVentasPorAlmacen(Request $request)
    {
        $inicioRaw = $request->query('fecha_inicio');
        $finRaw = $request->query('fecha_fin');

        if (!$inicioRaw || !$finRaw) {
            return response()->json(['error' => 'Fechas requeridas'], 400);
        }

        try {
            $inicio = Carbon::parse($inicioRaw)->startOfDay();
            $fin = Carbon::parse($finRaw)->endOfDay();

            Log::info("getVentasPorAlmacen fechas: inicio=$inicio, fin=$fin");

            // Ventas desde movimientos_inventario
            $ventasWeb = MovimientoInventario::where('tipo', 'salida')
                ->where('subtipo', 'venta')
                ->whereBetween('timestamp', [$inicio, $fin])
                ->get()
                ->load(['perfume', 'almacenOrigen']);

            // Ventas desde salidas
            $ventasMovil = Salidas::where('tipo', 'Venta')
                ->whereBetween('fecha_salida', [$inicio, $fin])
                ->get();

            Log::info('Ventas Web encontradas: ' . $ventasWeb->count());
            Log::info('Ventas Móvil encontradas: ' . $ventasMovil->count());

            $ventas = [];

            foreach ($ventasWeb as $venta) {
                $nombre = $venta->nombrePerfume ?? 'Sin nombre';
                if ($nombre !== 'Sin nombre') {
                    $ventas[] = [
                        'almacen' => $venta->codigoAlmacenOrigen ?? 'Desconocido',
                        'perfume' => $nombre,
                        'cantidad' => $venta->cantidad
                    ];
                }
            }

            foreach ($ventasMovil as $venta) {
                $nombre = $venta->nombrePerfume ?? $venta->nombre_perfume ?? 'Sin nombre';
                if ($nombre !== 'Sin nombre') {
                    $ventas[] = [
                        'almacen' => $venta->almacen_salida ?? 'Desconocido',
                        'perfume' => $nombre,
                        'cantidad' => $venta->cantidad
                    ];
                }
            }

            $agrupado = collect($ventas)->groupBy('almacen')->map(function ($grupo) {
                return [
                    'total' => $grupo->sum('cantidad'),
                    'perfumes' => $grupo->groupBy('perfume')->map->sum('cantidad')
                ];
            });

            Log::info('Ventas agrupadas por almacen: ' . json_encode($agrupado));

            return response()->json($agrupado);
        } catch (\Throwable $e) {
            Log::error('Error en getVentasPorAlmacen: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function getProductosMasVendidos(Request $request)
    {
        $inicioRaw = $request->query('fecha_inicio');
        $finRaw = $request->query('fecha_fin');

        if (!$inicioRaw || !$finRaw) {
            return response()->json(['error' => 'Fechas requeridas'], 400);
        }

        try {
            $inicio = Carbon::parse($inicioRaw)->startOfDay();
            $fin = Carbon::parse($finRaw)->endOfDay();

            Log::info("getProductosMasVendidos fechas: inicio=$inicio, fin=$fin");

            $ventasWeb = MovimientoInventario::where('tipo', 'salida')
                ->where('subtipo', 'venta')
                ->whereBetween('timestamp', [$inicio, $fin])
                ->get()
                ->load('perfume');

            $ventasMovil = Salidas::where('tipo', 'Venta')
                ->whereBetween('fecha_salida', [$inicio, $fin])
                ->get();

            Log::info("Ventas Web encontradas: " . $ventasWeb->count());
            Log::info("Ventas Móvil encontradas: " . $ventasMovil->count());

            $ventas = [];

            foreach ($ventasWeb as $venta) {
                $nombre = $venta->nombrePerfume ?? 'Sin nombre';
                if ($nombre !== 'Sin nombre') {
                    $ventas[] = [
                        'perfume' => $nombre,
                        'cantidad' => $venta->cantidad
                    ];
                }
            }

            foreach ($ventasMovil as $venta) {
                $nombre = $venta->nombrePerfume ?? $venta->nombre_perfume ?? 'Sin nombre';
                if ($nombre !== 'Sin nombre') {
                    $ventas[] = [
                        'perfume' => $nombre,
                        'cantidad' => $venta->cantidad
                    ];
                }
            }

            $agrupado = collect($ventas)->groupBy('perfume')->map->sum('cantidad');

            Log::info("Ventas agrupadas por perfume: " . json_encode($agrupado));

            return response()->json($agrupado);
        } catch (\Throwable $e) {
            Log::error('Error en getProductosMasVendidos: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
