<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Almacen;
use App\Models\Perfume;
use App\Models\MovimientoInventario;
use App\Models\Perfumes;

class MigrarInventario extends Command
{
    protected $signature = 'migrar:inventario';
    protected $description = 'Migrar campos estandarizados en almacenes, perfumes y movimientos';

    public function handle()
    {
        $this->info("🔄 Iniciando migración...");

        $this->migrarAlmacenes();
        $this->migrarPerfumes();
        $this->migrarMovimientos();

        $this->info("✅ Migración completada exitosamente.");
    }

    private function migrarAlmacenes()
    {
        $almacenes = Almacen::all();
        $index = 1;

        foreach ($almacenes as $almacen) {
            if (!$almacen->codigo) {
                $almacen->codigo = 'ALM' . str_pad($index, 3, '0', STR_PAD_LEFT);
                $almacen->save();
                $index++;
            }
        }

        $this->info("🧱 Códigos generados para almacenes.");
    }

    private function migrarPerfumes()
    {
        $perfumes = Perfumes::all();
        $index = 1;

        foreach ($perfumes as $perfume) {
            if (!$perfume->sku) {
                $perfume->sku = 'PERF' . str_pad($index, 3, '0', STR_PAD_LEFT);
                $perfume->save();
                $index++;
            }
        }

        $this->info("💐 SKUs asignados a perfumes.");
    }

    private function migrarMovimientos()
    {
        $movimientos = MovimientoInventario::all();
        $actualizados = 0;

        foreach ($movimientos as $movimiento) {
            $actualizado = false;

            if ($movimiento->almacen_origen_id && !$movimiento->almacen_origen_codigo) {
                $almacenOrigen = Almacen::find($movimiento->almacen_origen_id);
                $movimiento->almacen_origen_codigo = $almacenOrigen?->codigo;
                $actualizado = true;
            }

            if ($movimiento->almacen_destino_id && !$movimiento->almacen_destino_codigo) {
                $almacenDestino = Almacen::find($movimiento->almacen_destino_id);
                $movimiento->almacen_destino_codigo = $almacenDestino?->codigo;
                $actualizado = true;
            }

            if ($actualizado) {
                $movimiento->save();
                $actualizados++;
            }
        }

        $this->info("📦 Movimientos actualizados: {$actualizados}");
    }
}
