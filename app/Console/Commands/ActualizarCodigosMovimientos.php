<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Movimiento;
use App\Models\Almacen;
use App\Models\MovimientoInventario;

class ActualizarCodigosMovimientos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:actualizar-codigos-movimientos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
   public function handle()
{
    $movimientos = MovimientoInventario::where(function ($q) {
        $q->whereNull('almacen_origen_codigo')
          ->orWhereNull('almacen_destino_codigo');
    })->get();

    foreach ($movimientos as $movimiento) {
        if ($movimiento->tipo === 'transferencia') {
            $origen = Almacen::find($movimiento->almacen_origen_id);
            $destino = Almacen::find($movimiento->almacen_destino_id);

            $movimiento->almacen_origen_codigo = $origen?->codigo ?? null;
            $movimiento->almacen_destino_codigo = $destino?->codigo ?? null;
        }

        $movimiento->save();
    }

    $this->info('Movimientos actualizados con códigos de almacén');
}}
