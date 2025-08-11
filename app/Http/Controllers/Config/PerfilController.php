<?php

namespace App\Http\Controllers\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Storage;

class PerfilController extends Controller
{
    public function show()
    {
        $id = session('id_user'); // o Auth::id() si usas autenticación Laravel
        $usuario = Usuarios::findOrFail($id);
        return view('perfil.show', compact('usuario'));
    }

    public function edit()
    {
        $id = session('id_user');
        $usuario = Usuarios::findOrFail($id);
        return view('perfil.edit', compact('usuario'));
    }

    public function update(Request $request)
    {
        $id = session('id_user');
        $usuario = Usuarios::findOrFail($id);

        $request->validate([
            'name_user' => 'required|string|max:255',
            'correo_user' => 'required|email|unique:Usuarios,correo_user,' . $id,
            'password_user' => 'nullable|string|min:6|confirmed',
            'imagen_user' => 'nullable|image|max:2048',
        ]);

        $usuario->name_user = $request->name_user;
        $usuario->correo_user = $request->correo_user;

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

        return redirect()->route('perfil.show')->with('success', 'Perfil actualizado correctamente.');
    }
}
