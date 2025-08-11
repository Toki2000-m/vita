@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-semibold text-[#2C2C2C] mb-6">👥 Gestión de Usuarios</h2>

<section class="bg-[#F4F4F4] border border-[#B497BD] rounded-xl p-6 shadow-sm">

  <div class="flex justify-between items-center mb-6">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
      <div>
        <label class="block text-sm text-[#2C2C2C] mb-1" for="search">Buscar</label>
        <input id="search" name="search" type="text" placeholder="Nombre o correo"
          value="{{ request('search') }}"
          class="px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]">
      </div>
      <button type="submit"
        class="px-6 py-2 bg-[#9DBF9E] text-white rounded-lg hover:bg-[#88a98a] transition">🔍 Filtrar</button>
    </form>

    <a href="{{ route('config.usuarios.create') }}"
      class="px-6 py-2 bg-[#B497BD] text-white rounded-lg hover:bg-[#9B82B1] transition">+ Nuevo Usuario</a>
  </div>

  <div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-lg shadow-sm">
      <thead class="bg-[#B497BD] text-white">
        <tr>
          <th class="px-4 py-3 text-left">Nombre</th>
          <th class="px-4 py-3 text-left">Correo</th>
          <th class="px-4 py-3 text-left">Rol</th>
          <th class="px-4 py-3 text-left">Estado</th>
          <th class="px-4 py-3 text-left">Fecha de creación</th>
          <th class="px-4 py-3 text-center">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($usuarios as $usuario)
        <tr class="border-t hover:bg-[#F4F4F4]">
          <td class="px-4 py-2 flex items-center gap-3">
            <!-- Ícono genérico de usuario -->
            <div class="w-10 h-10 rounded-full bg-[#B497BD] flex items-center justify-center text-white">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A8 8 0 0112 15a8 8 0 016.879 2.804M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <span class="text-[#2C2C2C] font-medium">{{ $usuario->name_user }}</span>
          </td>
          <td class="px-4 py-2 text-[#2C2C2C]">{{ $usuario->correo_user }}</td>
          <td class="px-4 py-2 text-[#2C2C2C]">{{ $usuario->rol_user }}</td>
          <td class="px-4 py-2">
            @if ($usuario->estado_user)
              <span class="px-2 py-1 text-xs bg-[#9DBF9E] text-white rounded-full">Activo</span>
            @else
              <span class="px-2 py-1 text-xs bg-[#E08B8B] text-white rounded-full">Inactivo</span>
            @endif
          </td>
          <td class="px-4 py-2 text-[#2C2C2C]">
            {{ \Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y') }}
          </td>
          <td class="px-4 py-2 text-center space-x-2">
            <a href="{{ route('config.usuarios.edit', $usuario->_id) }}"
              class="text-[#B497BD] hover:text-[#7C5B8A] font-semibold">Editar</a>
            <form action="{{ route('config.usuarios.destroy', $usuario->_id) }}" method="POST" class="inline-block"
              onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="text-[#E08B8B] hover:text-[#B25555] font-semibold">Eliminar</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="px-4 py-6 text-center text-[#2C2C2C]">No se encontraron usuarios.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

</section>
@endsection
