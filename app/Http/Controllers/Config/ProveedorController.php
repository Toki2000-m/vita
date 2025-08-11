<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveedor;

class ProveedorController extends Controller
{
    public function index()
    {
        $proveedores = Proveedor::all();
        return view('config.proveedores.index', compact('proveedores'));
    }

    public function create()
    {
        return view('config.proveedores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_proveedor' => 'required|string|max:255',
            'rfc' => 'required|string|max:20|unique:proveedores,rfc',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        $data = $request->only([
            'nombre_proveedor',
            'rfc',
            'contacto',
            'telefono',
            'email',
            'direccion',
            'estado',
        ]);
        $data['fecha_registro'] = now();

        Proveedor::create($data);
        return redirect()->route('config.proveedores.index')->with('success', 'Proveedor creado.');
    }


    public function edit($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        return view('config.proveedores.edit', compact('proveedor'));
    }

    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $request->validate([
            'nombre_proveedor' => 'required|string|max:255',
            'rfc' => 'required|string|max:20|unique:proveedores,rfc,' . $id,
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'estado' => 'required|string|in:Activo,Inactivo',
        ]);

        $data = $request->only([
            'nombre_proveedor',
            'rfc',
            'contacto',
            'telefono',
            'email',
            'direccion',
            'estado',
        ]);

        $proveedor->update($data);
        return redirect()->route('config.proveedores.index')->with('success', 'Proveedor actualizado.');
    }

    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->delete();
        return redirect()->route('config.proveedores.index')->with('success', 'Proveedor eliminado.');
    }
}
