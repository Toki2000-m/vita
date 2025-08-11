<?php
namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use App\Models\Perfumes;

class Entradas extends Model
{
    protected $collection = 'entradas';

    public function perfume()
    {
        return $this->belongsTo(Perfumes::class, 'id_perfume', '_id');
    }
}
