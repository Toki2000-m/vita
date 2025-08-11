<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Almacen; // asegúrate de tener el modelo configurado para mongo

class AlmacenController extends Controller
{
    public function index()
    {
        $almacenes = Almacen::all();
        return view('config.almacenes.index', compact('almacenes'));
    }

    public function create()
    {
        return view('config.almacenes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'telefono' => 'required|string|max:20',
            'codigo' => 'required|string|max:20|unique:almacenes,codigo',
        ]);

        Almacen::create($request->all());
        return redirect()->route('config.almacenes.index')->with('success', 'Almacén creado.');
    }

    public function edit($id)
    {
        $almacen = Almacen::findOrFail($id);
        return view('config.almacenes.edit', compact('almacen'));
    }

    public function update(Request $request, $id)
    {
        $almacen = Almacen::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:500',
            'telefono' => 'required|string|max:20',
            'codigo' => 'required|string|max:20|unique:almacenes,codigo,'.$id,
        ]);

        $almacen->update($request->all());
        return redirect()->route('config.almacenes.index')->with('success', 'Almacén actualizado.');
    }

    public function destroy($id)
    {
        $almacen = Almacen::findOrFail($id);
        $almacen->delete();
        return redirect()->route('config.almacenes.index')->with('success', 'Almacén eliminado.');
    }
}
