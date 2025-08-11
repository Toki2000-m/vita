<?php

namespace App\Http\Controllers;

use App\Models\Usuario;



class UsuarioController extends Controller
{
    public function verUsuarios()
    {
        try {
            $usuarios = Usuario::all(); // obtiene todos los documentos

            return response()->json([
                'status' => 'ok',
                'total' => $usuarios->count(),
                'datos' => $usuarios
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'mensaje' => $e->getMessage()
            ], 500);
        }
    }
}
