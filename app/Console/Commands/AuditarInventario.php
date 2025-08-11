<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Almacen;
use App\Models\Perfume;
use App\Models\MovimientoInventario;
use App\Models\Perfumes;

class AuditarInventario extends Command
{
    protected $signature = 'auditar:inventario';
    protected $description = 'Audita inconsistencias en almacenes, perfumes y movimientos';

    public function handle()
    {
        $this->info("🔍 Iniciando auditoría de inventario...\n");

        $this->verificarDuplicadosAlmacenes();
        $this->verificarDuplicadosPerfumes();
        $this->verificarPerfumesSinMovimientos();
        $this->verificarMovimientosConAlmacenesInvalidos();

        $this->info("\n✅ Auditoría completada.");
    }

    private function verificarDuplicadosAlmacenes()
{
    $this->info("📦 Verificando almacenes duplicados por nombre y dirección:");

    $duplicados = Almacen::raw(function($collection) {
        return $collection->aggregate([
            [
                '$group' => [
                    '_id' => ['nombre' => '$nombre', 'direccion' => '$direccion'],
                    'conteo' => ['$sum' => 1]
                ]
            ],
            [
                '$match' => ['conteo' => ['$gt' => 1]]
            ]
        ]);
    });

    $hayDuplicados = false;

    foreach ($duplicados as $duplicado) {
        $nombre = $duplicado['_id']['nombre'] ?? '???';
        $direccion = $duplicado['_id']['direccion'] ?? '???';
        $this->line("🔁 Duplicado: {$nombre} — {$direccion}");
        $hayDuplicados = true;
    }

    if (!$hayDuplicados) {
        $this->line("✅ Sin duplicados de almacén.");
    }
}

    private function verificarDuplicadosPerfumes()
{
    $this->info("\n💐 Verificando perfumes con SKU duplicado:");

    $duplicados = Perfumes::raw(function($collection) {
        return $collection->aggregate([
            [
                '$group' => [
                    '_id' => '$sku',
                    'conteo' => ['$sum' => 1]
                ]
            ],
            [
                '$match' => ['conteo' => ['$gt' => 1]]
            ]
        ]);
    });

    $hayDuplicados = false;

    foreach ($duplicados as $sku) {
        $valor = $sku['_id'] ?? 'Sin SKU';
        $this->line("🔁 SKU duplicado: {$valor}");
        $hayDuplicados = true;
    }

    if (!$hayDuplicados) {
        $this->line("✅ Sin SKUs duplicados.");
    }
}


    private function verificarPerfumesSinMovimientos()
    {
        $this->info("\n📭 Verificando perfumes sin movimientos registrados:");

        $sinMovs = Perfumes::doesntHave('movimientos')->get();

        if ($sinMovs->count()) {
            foreach ($sinMovs as $perf) {
                $this->line("🚫 Sin movimientos: {$perf->name_per} (SKU: {$perf->sku})");
            }
        } else {
            $this->line("✅ Todos los perfumes tienen movimientos.");
        }
    }

    private function verificarMovimientosConAlmacenesInvalidos()
    {
        $this->info("\n🚨 Verificando movimientos con almacenes inválidos:");

        $almacenesValidos = Almacen::pluck('codigo')->toArray();

        $movimientosInvalidos = MovimientoInventario::whereNotIn('almacen_origen_codigo', $almacenesValidos)
            ->orWhereNotIn('almacen_destino_codigo', $almacenesValidos)
            ->get();

        if ($movimientosInvalidos->count()) {
            foreach ($movimientosInvalidos as $mov) {
                $this->line("⚠️ Movimiento con almacén inexistente — ID: {$mov->_id}");
            }
        } else {
            $this->line("✅ Todos los movimientos usan almacenes válidos.");
        }
    }
}
