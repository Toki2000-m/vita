<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ExistenciaController;
use App\Http\Controllers\AjusteInventarioController;
use App\Http\Controllers\RegistroMovimientoController;
use App\Models\Perfumes;
use App\Models\Proveedor;
use App\Http\Controllers\Config\PerfumesController;
use App\Http\Controllers\UsuariosController;



// ... tus otras rutas ...

// Ruta para traer todos los perfumes (solo id y nombre)
Route::get('/perfumes', function () {
    return Perfumes::select('_id', 'name_per')->get()->map(function($perfume) {
        return [
            '_id' => (string) $perfume->_id,
            'name_per' => $perfume->name_per,
        ];
    });
});

// Ruta para traer todos los proveedores (solo id y nombre)
Route::get('/proveedores', function () {
    return Proveedor::select('_id', 'nombre_proveedor')->get()->map(function($proveedor) {
        return [
            '_id' => (string) $proveedor->_id,
            'nombre_proveedor' => $proveedor->nombre_proveedor,
        ];
    });
});

// Rutas para ordenes de compra (ya tienes estas)
Route::prefix('ordenes-compra')->group(function () {
    Route::get('/ultimo-numero', [OrdenCompraController::class, 'obtenerUltimoNumeroOrden']); // 👈 Mueve esta arriba

    
    Route::get('/', [OrdenCompraController::class, 'index']);
    Route::get('/{id}', [OrdenCompraController::class, 'show']);
    Route::post('/', [OrdenCompraController::class, 'store']);
    Route::put('/{id}', [OrdenCompraController::class, 'update']);
    Route::delete('/{id}', [OrdenCompraController::class, 'destroy']);
    Route::post('/buscar', [OrdenCompraController::class, 'buscarPorNumero']);
    Route::post('/cancelar/{id}', [OrdenCompraController::class, 'cancelar']);
});

Route::prefix('movimientos')->group(function () {
    Route::post('/salida/venta', [RegistroMovimientoController::class, 'registrarSalidaPorVenta']);
});
// En api.php
Route::get('/existencias', [ExistenciaController::class, 'index']);
Route::post('/movimientos_inventario/kardex', [MovimientoInventarioController::class, 'kardex']);
//Route::post('/perfumes', [PerfumesController::class, 'store']); para pruebas en postman
