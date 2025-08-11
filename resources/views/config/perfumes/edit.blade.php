@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-[#F4F4F4] p-8 rounded-lg shadow-lg mt-10">
    <h1 class="text-3xl font-semibold mb-6 text-[#2C2C2C]">Editar Perfume</h1>

    @if ($errors->any())
    <div class="mb-6 p-4 bg-[#B75D69] text-white rounded-md shadow">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('config.perfumes.update', $perfume->_id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label for="name_per" class="block mb-2 font-medium text-[#2C2C2C]">Nombre</label>
            <input type="text" name="name_per" id="name_per" value="{{ old('name_per', $perfume->name_per) }}" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C]" />
        </div>

        <div>
            <label for="descripcion_per" class="block mb-2 font-medium text-[#2C2C2C]">Descripción</label>
            <textarea name="descripcion_per" id="descripcion_per" rows="3"
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C]">{{ old('descripcion_per', $perfume->descripcion_per) }}</textarea>
        </div>

        <div>
            <label for="categoria_per" class="block mb-2 font-medium text-[#2C2C2C]">Categoría</label>
            <input type="text" name="categoria_per" id="categoria_per" value="{{ old('categoria_per', $perfume->categoria_per) }}" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C]" />
        </div>

        <div>
            <label for="precio_venta_per" class="block mb-2 font-medium text-[#2C2C2C]">Precio</label>
            <input type="number" name="precio_venta_per" id="precio_venta_per" value="{{ old('precio_venta_per', $perfume->precio_venta_per) }}" min="0" step="0.01" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C]" />
        </div>

        <div>
            <label for="stock_per" class="block mb-2 font-medium text-[#2C2C2C]">Stock</label>
            <input type="number" name="stock_per" id="stock_per" value="{{ old('stock_per', $perfume->stock_per) }}" min="0" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C]" />
        </div>

        <div>
            <label for="fecha_expiracion" class="block mb-2 font-medium text-[#2C2C2C]">Fecha de Expiración</label>
            <input type="date" name="fecha_expiracion" id="fecha_expiracion" value="{{ old('fecha_expiracion', $perfume->fecha_expiracion ? $perfume->fecha_expiracion->format('Y-m-d') : '') }}" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD] bg-white text-[#2C2C2C]" />
        </div>

        <div>
            <label for="estado" class="block mb-2 font-medium text-[#2C2C2C]">Estado</label>
            <select name="estado" id="estado" required
                class="w-full px-4 py-2 rounded-md border border-[#B497BD] bg-white text-[#2C2C2C] focus:outline-none focus:ring-2 focus:ring-[#B497BD] focus:border-[#B497BD]">
                <option value="Activo" {{ old('estado', $perfume->estado) == 'Activo' ? 'selected' : '' }}>Activo</option>
                <option value="Inactivo" {{ old('estado', $perfume->estado) == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div class="flex items-center gap-4">
            <button type="submit"
                class="bg-[#B497BD] hover:bg-[#9C81AA] text-white font-semibold px-6 py-2 rounded-md transition-colors duration-300 shadow-md">
                Actualizar
            </button>

            <a href="{{ route('config.perfumes.index') }}"
                class="px-6 py-2 border border-[#D4AF37] text-[#D4AF37] rounded-md hover:bg-[#D4AF37] hover:text-white transition-colors duration-300 font-semibold">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection
