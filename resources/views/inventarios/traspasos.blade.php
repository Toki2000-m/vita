@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-semibold text-[#2C2C2C] mb-6">🔄 Traspasos entre almacenes</h2>

<section class="bg-[#F4F4F4] border border-[#B497BD] rounded-xl p-6 shadow-sm">
  {{-- Filtros --}}
  <form method="GET" class="mb-6 flex flex-wrap gap-4 items-end">
    <div>
      <label class="block text-sm text-[#2C2C2C] mb-1">Fecha desde</label>
      <input type="date" name="desde" value="{{ request('desde') }}"
             class="px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]">
    </div>
    <div>
      <label class="block text-sm text-[#2C2C2C] mb-1">Fecha hasta</label>
      <input type="date" name="hasta" value="{{ request('hasta') }}"
             class="px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]">
    </div>
    <button type="submit"
            class="px-6 py-2 bg-[#9DBF9E] text-white rounded-lg hover:bg-[#88a98a] transition">
      🔍 Filtrar
    </button>
  </form>

  {{-- Tabla --}}
  <div class="overflow-x-auto">
    <table class="min-w-full bg-white rounded-lg shadow-sm">
      <thead class="bg-[#B497BD] text-white">
        <tr>
          <th class="px-4 py-3 text-left">Traspaso</th>
          <th class="px-4 py-3 text-left">ID Perfume</th>
          <th class="px-4 py-3 text-left">Nombre Perfume</th>
          <th class="px-4 py-3 text-left">Cantidad</th>
          <th class="px-4 py-3 text-left">Almacén origen</th>
          <th class="px-4 py-3 text-left">Almacén destino</th>
          <th class="px-4 py-3 text-left">Estado</th>
          <th class="px-4 py-3 text-left">Fecha</th>
        </tr>
      </thead>
      <tbody>
        @forelse($traspasos as $t)
        <tr class="border-t hover:bg-[#F4F4F4]">
          <td class="px-4 py-2 font-medium text-[#2C2C2C]">{{ $t->numero_traspaso }}</td>
          <td class="px-4 py-2 text-sm text-[#6B7280]">{{ $t->id_perfume }}</td>
          <td class="px-4 py-2 font-medium text-[#2C2C2C]">{{ $t->perfume->name_per ?? '—' }}</td>
          <td class="px-4 py-2">{{ $t->cantidad }}</td>
          <td class="px-4 py-2">{{ $t->almacen_salida }}</td>
          <td class="px-4 py-2">{{ $t->almacen_destino }}</td>
          <td class="px-4 py-2">{{ $t->estatus_validacion }}</td>
          <td class="px-4 py-2">{{ \Carbon\Carbon::parse($t->fecha_salida)->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="px-4 py-6 text-center text-[#2C2C2C]">No se encontraron traspasos con los filtros seleccionados.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
