<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use App\Models\Almacen;
use App\Models\Existencia;
use Illuminate\Http\Request;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;
use App\Services\Inventario\ExistenciaService;
use App\Models\Perfumes;
use MongoDB\BSON\Int32;
use Illuminate\Support\Str;
use App\Models\Proveedor;
use App\Services\Inventario\PerfumesService;
use App\Models\OrdenCompra;





class RegistroMovimientoController extends Controller
{
    private function getUsuarioId()
    {
        return new ObjectId('64f29b73f9c95ecbd26bba13'); // Simulación para pruebas
    }

    private function obtenerAlmacenPorCodigo(string $codigo)
    {
        $almacen = Almacen::where('codigo', $codigo)->first();
        if (!$almacen) {
            throw new \Exception("❌ Código de almacén inválido: {$codigo}");
        }
        return new ObjectId($almacen->_id);
    }

    private function verificarStock(ObjectId $perfumeId, string $almacenCodigo, int $cantidad)
    {
        // Intentamos encontrar la existencia con el ObjectId original
        $existencia = Existencia::where([
            'producto_id' => $perfumeId,
            'almacen_id' => $almacenCodigo
        ])->first();

        // Si no encuentra, intenta con el producto_id como string (por si está así en la base)
        if (!$existencia) {
            $existencia = Existencia::where([
                'producto_id' => (string) $perfumeId,
                'almacen_id' => $almacenCodigo
            ])->first();
        }

        // Si aún no encuentra, lanza error
        if (!$existencia) {
            throw new \Exception("❌ No se encontró existencia del producto en el almacén {$almacenCodigo}.");
        }

        // Validación de stock
        $stock = $existencia->cantidad ?? 0;

        if ($stock < $cantidad) {
            throw new \Exception("🚨 Stock insuficiente: disponibles {$stock} pz, se requieren {$cantidad} pz");
        }
    }

    private function construirDocumentoMovimiento(array $data, string $tipo, string $subtipo, ?ObjectId $origenId, ?ObjectId $destinoId)
{
    return [
        'tipo' => $tipo,
        'subtipo' => $subtipo,
        'perfume_id' => new ObjectId($data['perfume_id']),
        'almacen_origen_id' => $origenId ?? null,
        'almacen_destino_id' => $destinoId ?? null,
        'cantidad' => $tipo === 'salida' && $subtipo === 'ajuste'
            ? -1 * abs($data['cantidad'])
            : $data['cantidad'],
        'motivo' => $data['motivo'],
        'referencia' => $data['referencia'] ?? null,
        'usuario_id' => $this->getUsuarioId(),
        'timestamp' => new UTCDateTime(now()),
        'traspaso_id' => null,
        'factura_id' => null,
        'proveedor_id' => isset($data['proveedor_id']) ? new ObjectId($data['proveedor_id']) : null,

        // ✅ Aquí se agrega la trazabilidad con la orden de compra
        'orden_compra_id' => isset($data['orden_compra_id']) ? new ObjectId($data['orden_compra_id']) : null,
    ];
}



    public function registrarEntradaPorCompra(Request $request)
{
    try {
        $data = $request->validate([
            'perfume_id' => 'required|string',
            'almacen_destino_codigo' => 'required|string',
            'cantidad' => 'required|numeric|min:1',
            'motivo' => 'nullable|string',
            'referencia' => 'nullable|string',
            'proveedor_id' => 'required|string',
            'orden_compra_id' => 'nullable|string'
        ]);

        // Validar proveedor
        $proveedor = Proveedor::find($data['proveedor_id']);
        if (!$proveedor) {
            return response()->json(['error' => '❌ El proveedor no existe.'], 422);
        }

        // Si viene orden_compra_id, validar que exista y esté pendiente
        if (!empty($data['orden_compra_id'])) {
            $orden = OrdenCompra::find($data['orden_compra_id']);
            if (!$orden || strtolower($orden->estado) !== 'pendiente') {
                return response()->json(['error' => '🚫 La orden de compra no existe o ya fue aceptada.'], 422);
            }

            // Sobrescribir datos desde la orden
            $data['perfume_id'] = $orden->id_perfume;
            $data['cantidad'] = (int) $orden->cantidad;
            $data['proveedor_id'] = $orden->proveedor;
            $data['referencia'] = $orden->n_orden_compra;
        }

        $destinoCodigo = $data['almacen_destino_codigo'];
        $destinoId = $this->obtenerAlmacenPorCodigo($destinoCodigo);

        $documento = $this->construirDocumentoMovimiento(
            $data,
            'entrada',
            'compra',
            null,
            $destinoId
        );

        // Agregar orden_compra_id si existe
        if (!empty($data['orden_compra_id'])) {
            $documento['orden_compra_id'] = new ObjectId($data['orden_compra_id']);
        }

        MovimientoInventario::create($documento);

        // Ajustar existencias
        ExistenciaService::ajustarStock($data['perfume_id'], $destinoCodigo, $data['cantidad'], 'entrada');
        $perfume = PerfumesService::ajustarStock($data['perfume_id'], $destinoCodigo, $data['cantidad'], 'entrada');

        // Actualizar estado de la orden
        if (!empty($data['orden_compra_id'])) {
            $orden->estado = 'Aceptada';
            $orden->save();
        }

        return response()->json([
            'message' => '✅ Entrada registrada correctamente.',
            'perfume' => [
                'stock_por_almacen' => $perfume->stock_por_almacen,
                'stock_actual' => $perfume->stock_actual
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 409);
    }
}

    public function registrarSalidaPorVenta(Request $request)
{
    $data = $request->validate([
        'perfume_id' => 'required|string',
        'almacen_origen_codigo' => 'required|string',
        'cantidad' => 'required|numeric|min:1',
        'motivo' => 'required|string',
        'referencia' => 'nullable|string'
    ]);

    try {
        $perfumeId = new ObjectId($data['perfume_id']);
        $origenCodigo = $data['almacen_origen_codigo'];
        $cantidad = $data['cantidad'];

        // Validar existencia suficiente antes de registrar salida
        $this->verificarStock($perfumeId, $origenCodigo, $cantidad);

        // Obtener el ObjectId del almacén origen
        $origenId = $this->obtenerAlmacenPorCodigo($origenCodigo);

        // Construir documento de movimiento
        $documento = $this->construirDocumentoMovimiento(
            $data,
            'salida',
            'venta',
            $origenId,
            null
        );

        // Registrar movimiento en la colección
        MovimientoInventario::create($documento);

        // Ajustar stock en Existencias
        ExistenciaService::ajustarStock(
            $data['perfume_id'],
            $origenCodigo,
            $cantidad,
            'salida'
        );

        // Ajustar stock en Perfumes (incluye stock_por_almacen y stock_actual)
        $perfume = Perfumes::find($perfumeId);

        if ($perfume) {
            $stockPorAlmacen = $perfume->stock_por_almacen ?? [];

            // Restar en el almacén correspondiente
            $stockPorAlmacen[$origenCodigo] = max(0, ($stockPorAlmacen[$origenCodigo] ?? 0) - $cantidad);

            // Recalcular stock_actual como suma de todos los almacenes
            $nuevoStockActual = array_sum($stockPorAlmacen);

            // Recalcular stock_per si aplica (puedes ajustar esta lógica según tu modelo)
            $nuevoStockPer = max(0, ($perfume->stock_per ?? 0) - $cantidad);

            Perfumes::where('_id', $perfumeId)->update([
                'stock_por_almacen' => $stockPorAlmacen,
                'stock_actual' => $nuevoStockActual,
                'stock_per' => $nuevoStockPer
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'mensaje' => '✅ Salida registrada por venta'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'mensaje' => '❌ Error al registrar salida',
            'detalle' => $e->getMessage()
        ], 409);
    }
}


    public function registrarTraspaso(Request $request)
{
    $data = $request->validate([
        'perfume_id' => 'required|string',
        'almacen_origen_codigo' => 'required|string',
        'almacen_destino_codigo' => 'required|string|different:almacen_origen_codigo',
        'cantidad' => 'required|numeric|min:1',
        'motivo' => 'required|string',
        'referencia' => 'nullable|string'
    ]);

    try {
        $perfumeId = new ObjectId($data['perfume_id']);
        $origenCodigo = $data['almacen_origen_codigo'];
        $destinoCodigo = $data['almacen_destino_codigo'];
        $cantidad = $data['cantidad'];

        // Verificar stock suficiente en almacén origen
        $this->verificarStock($perfumeId, $origenCodigo, $cantidad);

        // Obtener ObjectId de almacenes
        $origenId = $this->obtenerAlmacenPorCodigo($origenCodigo);
        $destinoId = $this->obtenerAlmacenPorCodigo($destinoCodigo);

        // Registrar movimiento en la colección
        $documento = $this->construirDocumentoMovimiento(
            $data,
            'traspaso',
            'manual',
            $origenId,
            $destinoId
        );

        MovimientoInventario::create($documento);

        // Ajustar existencias
        ExistenciaService::ajustarStock($data['perfume_id'], $origenCodigo, $cantidad, 'salida');
        ExistenciaService::ajustarStock($data['perfume_id'], $destinoCodigo, $cantidad, 'entrada');

        // Ajustar stock por almacén en Perfumes
        PerfumesService::ajustarStock($data['perfume_id'], $origenCodigo, $cantidad, 'salida');
        PerfumesService::ajustarStock($data['perfume_id'], $destinoCodigo, $cantidad, 'entrada');

        return response()->json([
            'status' => 'ok',
            'mensaje' => '✅ Traspaso registrado correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'mensaje' => '❌ Error al registrar traspaso',
            'detalle' => $e->getMessage()
        ], 409);
    }
}

public function crearEntrada()
{
    $ordenesPendientes = OrdenCompra::where('estado', 'Pendiente')->get();
    return view('movimientos.entrada', compact('ordenesPendientes'));
}

}