<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Almacen;
use Carbon\Carbon;

class NormalizarAlmacenes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:normalizar-almacenes';

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
    $almacenes = Almacen::all();

    foreach ($almacenes as $almacen) {
        $almacen->nombre = $almacen->nombre ?? $almacen->nombre_almacen;
        unset($almacen->nombre_almacen);

        $almacen->codigo = strtoupper($almacen->codigo ?? 'ALM' . substr($almacen->_id, -3));
        $almacen->ubicacion = $almacen->ubicacion ?? '';
        $almacen->estado = $almacen->estado ?? 'Activo';
        $almacen->descripcion = $almacen->descripcion ?? '';
        $almacen->createdAt = $almacen->createdAt ?? Carbon::now();
        $almacen->updatedAt = Carbon::now();

        $almacen->save();
    }

    $this->info('Almacenes normalizados correctamente');
}
}
