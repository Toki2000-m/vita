<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RegistroMovimientoController;
use App\Http\Controllers\TransaccionesController;
use App\Models\Perfumes;
use App\Http\Controllers\OrdenCompraController;
use App\Http\Controllers\ExistenciaController;
use App\Http\Controllers\MovimientoInventarioController;
use App\Http\Controllers\AnalisisController;
use App\Http\Controllers\Config\AlmacenController;
use App\Http\Controllers\Config\ProveedorController;
use App\Http\Controllers\Config\PerfumesController;
use App\Http\Controllers\Config\UsuariosController;





// Rutas para vistas
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/', [AuthController::class, 'showLogin']);


Route::get('/transacciones', [TransaccionesController::class, 'index'])->name('transacciones.index');

Route::post('/transacciones/entradas', [RegistroMovimientoController::class, 'registrarEntradaPorCompra'])->name('transacciones.entradas.store');

Route::post('/transacciones/salidas', [RegistroMovimientoController::class, 'registrarSalidaPorVenta'])->name('transacciones.salidas.store');

Route::post('/transacciones/traspasos', [RegistroMovimientoController::class, 'registrarTraspaso'])->name('transacciones.traspasos.store');

// Vistas módulo compras
Route::prefix('compras')->group(function () {
    Route::get('/', [OrdenCompraController::class, 'vistaPrincipal'])->name('compras.index');
    Route::get('/crear', [OrdenCompraController::class, 'vistaCrear'])->name('compras.crear');
    Route::get('/editar/{id}', [OrdenCompraController::class, 'vistaEditar'])->name('compras.editar');
});
Route::get('/existencias', [ExistenciaController::class, 'vistaPrincipal'])->name('existencias.index');


Route::prefix('inventarios')->group(function () {
    Route::get('/', function () {
        return view('inventarios.index');
    })->name('inventarios.index');

    Route::get('/kardex', function () {
        return view('inventarios.kardex');
    })->name('inventarios.kardex');
});
Route::get('/movimientos_inventario/kardex/pdf', [MovimientoInventarioController::class, 'exportarPdf']);
Route::get('/inventarios/entradas', [MovimientoInventarioController::class, 'entradas'])->name('inventarios.entradas');
Route::get('/inventarios/salidas', [MovimientoInventarioController::class, 'salidas'])->name('inventarios.salidas');
Route::get('/inventarios/traspasos', [MovimientoInventarioController::class, 'traspasos'])->name('inventarios.traspasos');

Route::get('/analisis/inventario', [AnalisisController::class, 'inventario'])->name('analisis.inventario');
Route::get('/api/ventas-por-almacen', [AnalisisController::class, 'getVentasPorAlmacen'])->name('api.ventas.almacen');
Route::get('/api/productos-mas-vendidos', [AnalisisController::class, 'getProductosMasVendidos'])->name('api.productos.masvendidos');


Route::get('/password/reset', function () {
    return 'Aquí va la página de recuperación de contraseña';
})->name('password.request');

//rutas para el login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::prefix('config')->name('config.')->group(function () {
    // CRUD Almacenes - definido manualmente
    Route::get('/almacenes', [AlmacenController::class, 'index'])->name('almacenes.index');
    Route::get('/almacenes/create', [AlmacenController::class, 'create'])->name('almacenes.create');
    Route::post('/almacenes', [AlmacenController::class, 'store'])->name('almacenes.store');
    Route::get('/almacenes/{id}/edit', [AlmacenController::class, 'edit'])->name('almacenes.edit');
    Route::put('/almacenes/{id}', [AlmacenController::class, 'update'])->name('almacenes.update');
    Route::delete('/almacenes/{id}', [AlmacenController::class, 'destroy'])->name('almacenes.destroy');

    // CRUD Proveedores - usando resource para todas las rutas
    Route::resource('proveedores', ProveedorController::class);

    Route::resource('usuarios', UsuariosController::class);

    // CRUD Perfumes - usando resource para todas las rutas
    Route::resource('perfumes', PerfumesController::class);

    
});

// Ruta para página principal configuración
Route::get('/config', function () {
    return view('config.index');
})->name('config.index');


// Ruta /ping para verificar vida en producción
Route::get('/ping', function () {
    return response()->json(['status' => 'alive'], 200);
});