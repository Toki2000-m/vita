<?php

namespace App\Services;

use App\Models\OrdenCompra;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrdenCompraService
{
    public function listarTodas()
    {
        return OrdenCompra::all();
    }

    public function obtenerPorId($id)
    {
        return OrdenCompra::findOrFail($id);
    }

    public function crear(array $data)
    {
        return DB::transaction(function () use ($data) {
            $orden = OrdenCompra::create($data);
            // Aquí podrías agregar lógica extra: notificaciones, kardex, etc.
            return $orden;
        });
    }

    public function actualizar($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $orden = OrdenCompra::findOrFail($id);
            $orden->update($data);
            return $orden;
        });
    }

    public function eliminar($id)
    {
        return DB::transaction(function () use ($id) {
            $orden = OrdenCompra::findOrFail($id);
            $orden->delete();
        });
    }

    public function buscarPorNumero($numero)
    {
        return OrdenCompra::where('n_orden_compra', (string) $numero)->first();
    }


    public function clonar($id)
    {
        $original = OrdenCompra::findOrFail($id);
        $copia = $original->replicate();
        $copia->numero = $original->numero . '-C';
        $copia->save();

        return $copia;
    }

    public function autorizar($id)
    {
        $orden = OrdenCompra::findOrFail($id);
        $orden->estado = 'autorizada';
        $orden->save();

        return $orden;
    }
}
