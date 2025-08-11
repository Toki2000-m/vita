@extends('layout') {{-- o el nombre real de tu layout --}}

@section('title', 'Mi Perfil')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-4">Perfil de Usuario</h1>

    <div class="space-y-4">
        <div>
            <span class="block text-sm text-gray-500">Nombre:</span>
            <span class="font-medium text-gray-800">{{ $usuario['nombre'] }}</span>
        </div>

        <div>
            <span class="block text-sm text-gray-500">Rol:</span>
            <span class="font-medium text-gray-800">{{ $usuario['rol'] }}</span>
        </div>

        <div>
            <span class="block text-sm text-gray-500">Correo:</span>
            <span class="font-medium text-gray-800">{{ $usuario['email'] }}</span>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ url()->previous() }}" class="px-4 py-2 bg-[#B497BD] text-white rounded hover:bg-[#9d84a8] transition">
            Volver
        </a>
    </div>
</div>
@endsection
