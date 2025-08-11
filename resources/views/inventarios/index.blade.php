@extends('layouts.app')

@section('content')
  <h2 class="text-3xl font-semibold text-[#2C2C2C] mb-6">Historial de movimientos</h2>

  <section class="bg-[#F4F4F4] border border-[#B497BD] rounded-xl p-6 text-center shadow-sm">
    <div class="flex flex-wrap justify-center gap-4 mb-6">
      <a href="{{ route('inventarios.kardex') }}"
         class="inline-block px-6 py-3 bg-[#B497BD] text-white rounded-lg hover:bg-[#a07faf] transition">
        📦 Consultar Kardex General
      </a>

      <a href="{{ route('inventarios.entradas') }}"
         class="inline-block px-6 py-3 bg-[#9DBF9E] text-white rounded-lg hover:bg-[#88a98a] transition">
        ➕ Consultar Entradas
      </a>

      <a href="{{ route('inventarios.salidas') }}"
         class="inline-block px-6 py-3 bg-[#B75D69] text-white rounded-lg hover:bg-[#a14c58] transition">
        ➖ Consultar Salidas
      </a>

      <a href="{{ route('inventarios.traspasos') }}"
         class="inline-block px-6 py-3 bg-[#D4AF37] text-white rounded-lg hover:bg-[#b8962f] transition">
        🔄 Consultar Traspasos
      </a>
    </div>

    <p class="text-[#2C2C2C] text-sm">
      Selecciona el tipo de movimiento que deseas consultar. Cada vista muestra los datos con filtros y validaciones específicas.
    </p>
  </section>
@endsection
