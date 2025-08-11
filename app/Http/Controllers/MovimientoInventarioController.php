<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use App\Models\Perfumes;
use App\Models\Almacen;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\MovimientosInventarioExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Entradas;
use App\Models\Salidas;
use App\Models\Traspasos;
use App\Models\Usuario;



class MovimientoInventarioController extends Controller
{
    public function kardex(Request $request)
    {
        try {
            $query = MovimientoInventario::query();

            if ($request->filled('perfume_id')) {
                $query->where('perfume_id', new ObjectId($request->perfume_id));
            }

            if ($request->filled('tipo')) {
                $query->where('tipo', $request->tipo);
            }

            if ($request->filled('desde') && $request->filled('hasta')) {
                $desde = Carbon::parse($request->desde . ' 00:00:00')->utc()->timestamp * 1000;
                $hasta = Carbon::parse($request->hasta . ' 23:59:59')->utc()->timestamp * 1000;

                $query->whereNotNull('timestamp')
                    ->where('timestamp', '>=', new UTCDateTime($desde))
                    ->where('timestamp', '<=', new UTCDateTime($hasta));
            }


            $movimientos = $query->orderBy('timestamp', 'asc')->get();

            // Mapear nombres de perfumes
            $perfumes = [];
            foreach (Perfumes::all() as $p) {
                $perfumes[(string) $p->_id] = $p->getAttribute('name_per') ?? '—';
            }

            // Mapear almacenes
            $almacenes = Almacen::all()->mapWithKeys(function ($a) {
                return [(string) $a->_id => [
                    'codigo' => $a->codigo,
                    'nombre' => $a->nombre
                ]];
            });

            // Transformar movimientos para incluir perfume_id como $oid
            $movimientosTransformados = $movimientos->map(function ($mov) {
                return [
                    '_id' => (string) $mov->_id,
                    'tipo' => $mov->tipo,
                    'cantidad' => $mov->cantidad,
                    'timestamp' => $mov->timestamp,
                    'almacen_origen_id' => $mov->almacen_origen_id,
                    'almacen_destino_id' => $mov->almacen_destino_id,
                    'referencia' => $mov->referencia,
                    'perfume_id' => ['$oid' => (string) $mov->perfume_id], // 👈 clave para el frontend
                ];
            });

            return response()->json([
                'status' => 'ok',
                'producto_id' => $request->perfume_id,
                'total' => $movimientos->count(),
                'movimientos' => $movimientosTransformados,
                'perfumes_map' => $perfumes,
                'almacenes_map' => $almacenes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'mensaje' => 'Error en consulta Kardex.',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

    public function exportarPdf(Request $request)
    {
        $query = MovimientoInventario::query();

        if ($request->filled('perfume_id')) {
            $query->where('perfume_id', new ObjectId($request->perfume_id));
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('desde') && $request->filled('hasta')) {
            $desde = Carbon::parse($request->desde . ' 00:00:00')->utc()->timestamp * 1000;
            $hasta = Carbon::parse($request->hasta . ' 23:59:59')->utc()->timestamp * 1000;

            $query->whereNotNull('timestamp')
                ->where('timestamp', '>=', new UTCDateTime($desde))
                ->where('timestamp', '<=', new UTCDateTime($hasta));
        }

        $movimientos = $query->orderBy('timestamp', 'asc')->get();

        $perfumes = Perfumes::all()->pluck('name_per', '_id')->toArray();
        $almacenes = Almacen::all()->mapWithKeys(function ($a) {
            return [
                (string) $a->_id => $a->codigo . ' - ' . $a->nombre
            ];
        })->toArray();


        // Pasar todo a la vista
        $pdf = Pdf::loadView('pdf.kardex', [
            'movimientos' => $movimientos,
            'perfumes' => $perfumes,
            'almacenes' => $almacenes
        ])->setPaper('a4', 'landscape');

        return $pdf->download('kardex.pdf');
    }

    public function entradas(Request $request)
{
    $query = Entradas::query();

    if ($request->filled('desde')) {
        $query->whereDate('fecha_entrada', '>=', $request->desde);
    }

    if ($request->filled('hasta')) {
        $query->whereDate('fecha_entrada', '<=', $request->hasta);
    }

    $entradas = $query->orderBy('fecha_entrada', 'desc')->get();

    // Convertir ObjectId a string para usar como clave
    $perfumeIds = $entradas->pluck('id_perfume')->map(fn($id) => (string) $id);
    $perfumes = Perfumes::whereIn('_id', $perfumeIds)->get()->keyBy(fn($p) => (string) $p->_id);

    return view('inventarios.entradas', compact('entradas', 'perfumes'));
}

public function salidas(Request $request)
{
    $query = Salidas::query();

    if ($request->filled('desde')) {
        $query->whereDate('fecha_salida', '>=', $request->desde);
    }

    if ($request->filled('hasta')) {
        $query->whereDate('fecha_salida', '<=', $request->hasta);
    }

    $salidas = $query->orderBy('fecha_salida', 'desc')->get();

    // Cargar todos los usuarios en memoria
    $usuarios = Usuario::all()->keyBy(function ($usuario) {
        return (string) $usuario->_id;
    });

    // Resolver responsable manualmente
    $salidas->transform(function ($salida) use ($usuarios) {
    $key = (string) $salida->usuario_registro;
    $salida->responsable = $usuarios[$key] ?? null;
    return $salida;
});


    return view('inventarios.salidas', compact('salidas'));
}

public function traspasos(Request $request)
{
    $query = Traspasos::query();

    if ($request->filled('desde')) {
        $query->whereDate('fecha_salida', '>=', $request->desde);
    }

    if ($request->filled('hasta')) {
        $query->whereDate('fecha_salida', '<=', $request->hasta);
    }

    $traspasos = $query->orderBy('fecha_salida', 'desc')->get();

    $traspasos->transform(fn($t) => $t->resolveReferencias());

    return view('inventarios.traspasos', compact('traspasos'));
}

}
