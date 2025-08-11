<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Usuario;
use App\Models\Perfumes;

class Traspasos extends Model
{
    protected $collection = 'Traspasos';
    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'numero_traspaso',
        'id_perfume',
        'cantidad',
        'estatus_validacion',
        'fecha_salida',
        'usuario_registro',
        'almacen_salida',
        'almacen_destino',
        'fecha_validacion',
        'observaciones_auditor',
        'validado_por',
    ];

    public function resolveReferencias()
    {
        $usuarios = Usuario::all()->keyBy(fn($u) => (string) $u->_id);
        $perfumes = Perfumes::all()->keyBy(fn($p) => (string) $p->_id);

        $this->responsable = $usuarios[(string) $this->usuario_registro] ?? null;
        $this->validador = $usuarios[(string) $this->validado_por] ?? null;
        $this->perfume = $perfumes[(string) $this->id_perfume] ?? null;

        return $this;
    }
}
