<?php

namespace App\Services\Inventario;

use App\Models\Almacen;
use App\Models\Perfume;
use App\Models\MovimientoInventario;
use App\Models\Perfumes;

class AuditoriaService
{
    public function verificarAlmacenesDuplicados(): array {
        return Almacen::select('nombre', 'direccion')
            ->groupBy('nombre', 'direccion')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->toArray();
    }

    public function perfumesSinMovimientos(): array {
        return Perfumes::doesntHave('movimientos')->get()->toArray();
    }

    public function movimientosConAlmacenesInvalidos(): array {
        return MovimientoInventario::whereDoesntHave('almacenOrigen')
            ->orWhereDoesntHave('almacenDestino')
            ->get()
            ->toArray();
    }
}
