@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-semibold text-[#2C2C2C] mb-6">📤 Salidas del inventario</h2>

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
          <th class="px-4 py-3 text-left">Perfume</th>
          <th class="px-4 py-3 text-left">Almacén</th>
          <th class="px-4 py-3 text-left">Cantidad</th>
          <th class="px-4 py-3 text-left">Motivo</th>
          <th class="px-4 py-3 text-left">Fecha</th>
        </tr>
      </thead>
      <tbody>
        @forelse($salidas as $salida)
        <tr class="border-t hover:bg-[#F4F4F4]">
          <td class="px-4 py-2 text-[#2C2C2C] font-medium">
            {{ $salida->nombre_perfume ?? 'Perfume desconocido' }}
          </td>
          <td class="px-4 py-2">{{ $salida->almacen_salida ?? 'N/A' }}</td>
          <td class="px-4 py-2">{{ $salida->cantidad }}</td>
          <td class="px-4 py-2">{{ $salida->tipo ?? 'N/A' }}</td>
          <td class="px-4 py-2">{{ \Carbon\Carbon::parse($salida->fecha_salida)->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="px-4 py-6 text-center text-[#2C2C2C]">No se encontraron salidas con los filtros seleccionados.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
