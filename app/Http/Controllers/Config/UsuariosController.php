<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios; // tu modelo Usuarios
use Illuminate\Support\Facades\Storage;

class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $usuariosQuery = Usuarios::query();

        if ($search) {
            $usuariosQuery->where(function ($query) use ($search) {
                $query->where('name_user', 'LIKE', "%{$search}%")
                      ->orWhere('correo_user', 'LIKE', "%{$search}%");
            });
        }

        $usuarios = $usuariosQuery->orderBy('name_user')->paginate(10)->withQueryString();

        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('config.usuarios.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_user' => 'required|string|max:255',
            'correo_user' => 'required|email|unique:Usuarios,correo_user',
            'password_user' => 'required|string|min:6|confirmed',
            'rol_user' => 'required|string|in:Admin,Empleado,Auditor',
            'imagen_user' => 'nullable|image|max:2048',
            'estado_user' => 'required|boolean',
        ]);

        $data = $request->only('name_user', 'correo_user', 'rol_user', 'estado_user');

        if ($request->hasFile('imagen_user')) {
            $path = $request->file('imagen_user')->store('uploads/users', 'public');
            $data['imagen_user'] = '/' . $path;
        }

        $data['password_user'] = bcrypt($request->password_user);
        $data['fecha_creacion'] = now();

        Usuarios::create($data);

        return redirect()->route('config.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit($id)
    {
        $usuario = Usuarios::findOrFail($id);
        return view('config.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuarios::findOrFail($id);

        $request->validate([
            'name_user' => 'required|string|max:255',
            'correo_user' => 'required|email|unique:Usuarios,correo_user,'.$id,
            'password_user' => 'nullable|string|min:6|confirmed',
            'rol_user' => 'required|string|in:Admin,Empleado,Auditor',
            'imagen_user' => 'nullable|image|max:2048',
            'estado_user' => 'required|boolean',
        ]);

        $usuario->name_user = $request->name_user;
        $usuario->correo_user = $request->correo_user;
        $usuario->rol_user = $request->rol_user;
        $usuario->estado_user = $request->estado_user;

        if ($request->hasFile('imagen_user')) {
            if ($usuario->imagen_user) {
                Storage::disk('public')->delete(ltrim($usuario->imagen_user, '/'));
            }
            $path = $request->file('imagen_user')->store('uploads/users', 'public');
            $usuario->imagen_user = '/' . $path;
        }

        if ($request->filled('password_user')) {
            $usuario->password_user = bcrypt($request->password_user);
        }

        $usuario->save();

        return redirect()->route('config.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id)
    {
        $usuario = Usuarios::findOrFail($id);

        if ($usuario->imagen_user) {
            Storage::disk('public')->delete(ltrim($usuario->imagen_user, '/'));
        }

        $usuario->delete();

        return redirect()->route('config.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
