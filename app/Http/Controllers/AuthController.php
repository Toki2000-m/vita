<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'correo_user' => 'required|email',
        'password_user' => 'required|string'
    ]);

    $usuario = Usuario::where('correo_user', $request->correo_user)->first();

    // Usamos password_verify en lugar de Hash::check
    if (!$usuario || !password_verify($request->password_user, $usuario->password_user)) {
        return back()->withErrors(['login' => 'Credenciales inválidas']);
    }

    if (!$usuario->estado_user) {
        return back()->withErrors(['login' => 'Usuario inactivo']);
    }

    session([
        'usuario_id' => (string) $usuario->_id,
        'rol_user' => $usuario->rol_user,
        'name_user' => $usuario->name_user
    ]);

    return redirect()->route('dashboard');
}


    public function logout()
    {
        session()->flush();
        return redirect()->route('login');
    }
}
