@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-[#F4F4F4] p-8 rounded-lg shadow-lg mt-10">
    <h1 class="text-3xl font-semibold mb-6 text-[#2C2C2C]">Editar Almacén</h1>

    @if ($errors->any())
    <div class="mb-6 p-4 bg-[#B75D69] text-white rounded-md shadow">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('config.almacenes.update', $almacen->_id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="nombre" class="block mb-2 font-medium text-[#2C2C2C]">Nombre</label>
            <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $almacen->nombre) }}" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C] placeholder-[#9CA3AF]" />
        </div>

        <div>
            <label for="direccion" class="block mb-2 font-medium text-[#2C2C2C]">Dirección</label>
            <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $almacen->direccion) }}" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C] placeholder-[#9CA3AF]" />
        </div>

        <div>
            <label for="telefono" class="block mb-2 font-medium text-[#2C2C2C]">Teléfono</label>
            <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $almacen->telefono) }}" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C] placeholder-[#9CA3AF]" />
        </div>

        <div>
            <label for="codigo" class="block mb-2 font-medium text-[#2C2C2C]">Código</label>
            <input type="text" name="codigo" id="codigo" value="{{ old('codigo', $almacen->codigo) }}" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C] placeholder-[#9CA3AF]" />
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="bg-[#B497BD] hover:bg-[#9C81AA] text-white font-semibold px-6 py-2 rounded-md transition-colors duration-300 shadow-md">
                Actualizar
            </button>

            <a href="{{ route('config.almacenes.index') }}"
                class="px-6 py-2 border border-[#D4AF37] text-[#D4AF37] rounded-md hover:bg-[#D4AF37] hover:text-white transition-colors duration-300 font-semibold">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
