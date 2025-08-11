<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use App\Models\Perfumes;
use App\Models\Movimientos;
use App\Models\Almacen;
use App\Models\MovimientoInventario;

class ExistenciaController extends Controller
{
    // Consulta general de existencia por almacén y producto
    public function consultaGeneral()
    {
        $almacenes = Almacen::all();
        $resultado = [];

        foreach ($almacenes as $almacen) {
            $productos = Perfumes::all();

            foreach ($productos as $producto) {
                $movimientos = MovimientoInventario::where('producto_id', $producto->_id)
                    ->where(function ($q) use ($almacen) {
                        $q->where('almacen_origen_codigo', $almacen->codigo)
                          ->orWhere('almacen_destino_codigo', $almacen->codigo);
                    })->get();

                $existencia = 0;

                foreach ($movimientos as $mov) {
                    switch ($mov->tipo) {
                        case 'entrada':
                            if ($mov->almacen_destino_codigo === $almacen->codigo) $existencia += $mov->cantidad;
                            break;
                        case 'salida':
                        case 'ajuste':
                            if ($mov->almacen_origen_codigo === $almacen->codigo) $existencia -= abs($mov->cantidad);
                            break;
                        case 'transferencia':
                            if ($mov->almacen_origen_codigo === $almacen->codigo) $existencia -= $mov->cantidad;
                            if ($mov->almacen_destino_codigo === $almacen->codigo) $existencia += $mov->cantidad;
                            break;
                    }
                }

                if ($existencia > 0) {
                    $resultado[] = [
                        'almacen' => $almacen->nombre,
                        'producto' => $producto->name_per,
                        'sku' => $producto->sku,
                        'existencia' => $existencia,
                    ];
                }
            }
        }

        return response()->json($resultado);
    }

    // Consulta existencia por SKU específico
    public function consultaPorSku($sku)
    {
        $producto = Perfumes::where('sku', $sku)->firstOrFail();
        $almacenes = Almacen::all();
        $resultado = [];

        foreach ($almacenes as $almacen) {
            $movimientos = MovimientoInventario::where('producto_id', $producto->_id)
                ->where(function ($q) use ($almacen) {
                    $q->where('almacen_origen_codigo', $almacen->codigo)
                      ->orWhere('almacen_destino_codigo', $almacen->codigo);
                })->get();

            $existencia = 0;

            foreach ($movimientos as $mov) {
                switch ($mov->tipo) {
                    case 'entrada':
                        if ($mov->almacen_destino_codigo === $almacen->codigo) $existencia += $mov->cantidad;
                        break;
                    case 'salida':
                    case 'ajuste':
                        if ($mov->almacen_origen_codigo === $almacen->codigo) $existencia -= abs($mov->cantidad);
                        break;
                    case 'transferencia':
                        if ($mov->almacen_origen_codigo === $almacen->codigo) $existencia -= $mov->cantidad;
                        if ($mov->almacen_destino_codigo === $almacen->codigo) $existencia += $mov->cantidad;
                        break;
                }
            }

            if ($existencia > 0) {
                $resultado[] = [
                    'almacen' => $almacen->nombre,
                    'codigo' => $almacen->codigo,
                    'existencia' => $existencia,
                ];
            }
        }

        return response()->json([
            'sku' => $sku,
            'producto' => $producto->name_per,
            'existencias_por_almacen' => $resultado,
        ]);
    }
}
