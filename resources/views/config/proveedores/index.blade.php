@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto mt-10 px-6 py-8 bg-[#F4F4F4] rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-semibold text-[#2C2C2C]">Proveedores</h1>
        <a href="{{ route('config.proveedores.create') }}"
            class="bg-[#B497BD] hover:bg-[#9C81AA] text-white font-semibold px-5 py-2 rounded-md transition-colors duration-300 shadow-md">
            + Nuevo Proveedor
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 rounded-md bg-[#9DBF9E] text-[#2C2C2C] font-medium shadow">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-[#B497BD] shadow-md">
        <table class="min-w-full divide-y divide-[#B497BD]">
            <thead class="bg-[#B497BD] text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">RFC</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Contacto</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Teléfono</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Estado</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-[#B497BD]">
                @foreach($proveedores as $proveedor)
                <tr class="hover:bg-[#F4F4F4] transition-colors duration-200">
                    <td class="px-6 py-4 text-[#2C2C2C]">{{ $proveedor->nombre_proveedor }}</td>
                    <td class="px-6 py-4 text-[#2C2C2C] font-mono">{{ $proveedor->rfc }}</td>
                    <td class="px-6 py-4 text-[#2C2C2C]">{{ $proveedor->contacto }}</td>
                    <td class="px-6 py-4 text-[#2C2C2C]">{{ $proveedor->telefono }}</td>
                    <td class="px-6 py-4 text-[#2C2C2C]">{{ $proveedor->email }}</td>
                    <td class="px-6 py-4 text-[#2C2C2C]">{{ $proveedor->estado }}</td>
                    <td class="px-6 py-4 text-center space-x-2">
                        <a href="{{ route('config.proveedores.edit', $proveedor->_id) }}"
    class="inline-block px-2 py-1 rounded-md bg-[#D4AF37] hover:bg-[#b3952a] text-white font-semibold text-sm transition-colors duration-300 shadow">
    Editar
</a>

<form action="{{ route('config.proveedores.destroy', $proveedor->_id) }}" method="POST"
    class="inline-block" onsubmit="return confirm('¿Eliminar proveedor?');">
    @csrf
    @method('DELETE')
    <button type="submit"
        class="px-2 py-1 rounded-md bg-[#B75D69] hover:bg-[#8a3d48] text-white font-semibold text-sm transition-colors duration-300 shadow">
        Eliminar
    </button>
</form>

                    </td>
                </tr>
                @endforeach
                @if($proveedores->isEmpty())
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-[#B75D69] font-semibold">
                        No hay proveedores registrados.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
