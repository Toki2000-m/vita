<?php

namespace App\Services\Inventario;

use App\Models\Perfumes;
use MongoDB\BSON\ObjectId;

class PerfumesService
{
    /**
     * Ajusta el stock de un perfume en un almacén específico.
     *
     * @param string $perfumeId
     * @param string $almacenCodigo
     * @param int $cantidad
     * @param string $tipo ('entrada' o 'salida')
     * @return Perfumes
     * @throws \Exception
     */
    public static function ajustarStock(string $perfumeId, string $almacenCodigo, int $cantidad, string $tipo = 'entrada'): Perfumes
    {
        $perfume = Perfumes::find($perfumeId);
        if (!$perfume) {
            throw new \Exception("❌ Perfume no encontrado");
        }

        $stockPorAlmacen = $perfume->stock_por_almacen ?? [];

        if ($tipo === 'entrada') {
            $stockPorAlmacen[$almacenCodigo] = ($stockPorAlmacen[$almacenCodigo] ?? 0) + $cantidad;
        } elseif ($tipo === 'salida') {
            $stockDisponible = $stockPorAlmacen[$almacenCodigo] ?? 0;
            if ($stockDisponible < $cantidad) {
                throw new \Exception("🚨 Stock insuficiente en $almacenCodigo: disponibles {$stockDisponible} pz");
            }
            $stockPorAlmacen[$almacenCodigo] = $stockDisponible - $cantidad;
        } else {
            throw new \Exception("❌ Tipo de ajuste inválido: $tipo");
        }

        $perfume->stock_por_almacen = $stockPorAlmacen;
        $perfume->stock_actual = array_sum($stockPorAlmacen);
        $perfume->save();

        return $perfume;
    }

    /**
     * Consulta el stock de un perfume en un almacén específico.
     *
     * @param string $perfumeId
     * @param string $almacenCodigo
     * @return int
     */
    public static function consultarStock(string $perfumeId, string $almacenCodigo): int
    {
        $perfume = Perfumes::find($perfumeId);
        if (!$perfume) {
            return 0;
        }

        $stockPorAlmacen = $perfume->stock_por_almacen ?? [];
        return $stockPorAlmacen[$almacenCodigo] ?? 0;
    }

    /**
     * Consulta el stock total de un perfume (suma de todos los almacenes).
     *
     * @param string $perfumeId
     * @return int
     */
    public static function consultarStockTotal(string $perfumeId): int
    {
        $perfume = Perfumes::find($perfumeId);
        if (!$perfume) {
            return 0;
        }

        return array_sum($perfume->stock_por_almacen ?? []);
    }
}
