@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto bg-[#F4F4F4] p-8 rounded-lg shadow-lg mt-10">
    <h1 class="text-3xl font-semibold mb-6 text-[#2C2C2C]">Nuevo Perfume</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-[#B75D69] text-white rounded-md shadow">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('config.perfumes.store') }}" method="POST" autocomplete="off">
        @csrf

        {{-- Nombre --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">Nombre</label>
        <input type="text" name="name_per" value="{{ old('name_per') }}" required
            class="w-full mb-4 border border-[#B497BD] rounded p-2 text-[#2C2C2C]">

        {{-- Descripción --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">Descripción</label>
        <textarea name="descripcion_per"
            class="w-full mb-4 border border-[#B497BD] rounded p-2 text-[#2C2C2C]" rows="3">{{ old('descripcion_per') }}</textarea>

        {{-- Categoría --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">Categoría</label>
        <select name="categoria_per" required class="w-full mb-4 border border-[#B497BD] rounded p-2 text-[#2C2C2C]">
            <option value="" disabled hidden>Selecciona...</option>
            <option value="Dama" {{ old('categoria_per') == 'Dama' ? 'selected' : '' }}>Dama</option>
            <option value="Caballero" {{ old('categoria_per') == 'Caballero' ? 'selected' : '' }}>Caballero</option>
        </select>

        {{-- Precio --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">Precio</label>
        <input type="number" name="precio_venta_per" value="{{ old('precio_venta_per') }}" min="0" step="0.01" required
            class="w-full mb-4 border border-[#B497BD] rounded p-2 text-[#2C2C2C]">

        {{-- Ubicación --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">Ubicación <small class="text-gray-500">(Opcional)</small></label>
        <select name="ubicacion_per" class="w-full mb-1 border border-[#B497BD] rounded p-2 text-[#2C2C2C]">
            <option value="" disabled hidden>Selecciona...</option>
            <option value="ALM001" {{ old('ubicacion_per') == 'ALM001' ? 'selected' : '' }}>ALM001</option>
            <option value="ALM002" {{ old('ubicacion_per') == 'ALM002' ? 'selected' : '' }}>ALM002</option>
            <option value="ALM003" {{ old('ubicacion_per') == 'ALM003' ? 'selected' : '' }}>ALM003</option>
        </select>
        <small class="text-sm text-gray-500 mb-4 block">Si defines una ubicación, el stock será registrado en ese almacén.</small>

        {{-- Stock (oculto o visible según ubicación) --}}
        <div id="stock-container" style="display:none;">
            <label class="block mb-2 font-medium text-[#2C2C2C]">Stock</label>
            <input type="number" name="stock_per" value="{{ old('stock_per') }}" min="0" step="1"
                class="w-full mb-1 border border-[#B497BD] rounded p-2 text-[#2C2C2C]">
            <small class="text-sm text-gray-500 mb-4 block">El stock inicial solo se aplicará si defines una ubicación.</small>
            @error('stock_per')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Stock mínimo --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">Stock mínimo <small class="text-gray-500">(Opcional)</small></label>
        <input type="number" name="stock_minimo_per" value="{{ old('stock_minimo_per') }}" min="0" step="1"
            class="w-full mb-4 border border-[#B497BD] rounded p-2 text-[#2C2C2C]" placeholder="Opcional">

        {{-- Fecha de expiración --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">Fecha de expiración <small class="text-gray-500">(Opcional)</small></label>
        <input type="date" name="fecha_expiracion" value="{{ old('fecha_expiracion') }}"
            class="w-full mb-4 border border-[#B497BD] rounded p-2 text-[#2C2C2C]">

        {{-- Estado --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">Estado</label>
        <select name="estado" required class="w-full mb-1 border border-[#B497BD] rounded p-2 text-[#2C2C2C]">
            <option value="" disabled hidden>Selecciona...</option>
            <option value="Activo" {{ old('estado') == 'Activo' ? 'selected' : '' }}>Activo</option>
            <option value="Inactivo" {{ old('estado') == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
        </select>
        @error('estado')
            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror

        {{-- Imagen --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">URL de Imagen <small class="text-gray-500">(Opcional)</small></label>
        <input type="text" name="imagen_url" value="{{ old('imagen_url') }}"
            class="w-full mb-4 border border-[#B497BD] rounded p-2 text-[#2C2C2C]" placeholder="Opcional">

        {{-- Marca --}}
        <label class="block mb-2 font-medium text-[#2C2C2C]">Marca <small class="text-gray-500">(Opcional)</small></label>
        <input type="text" name="marca" value="{{ old('marca') }}"
            class="w-full mb-6 border border-[#B497BD] rounded p-2 text-[#2C2C2C]" placeholder="Opcional">

        {{-- Botones --}}
        <div class="flex items-center gap-4">
            <button type="submit"
                class="bg-[#B497BD] hover:bg-[#9C81AA] text-white font-semibold px-6 py-2 rounded-md transition-colors duration-300 shadow-md">
                Guardar
            </button>

            <a href="{{ route('config.perfumes.index') }}"
                class="px-6 py-2 border border-[#D4AF37] text-[#D4AF37] rounded-md hover:bg-[#D4AF37] hover:text-white transition-colors duration-300 font-semibold">
                Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ubicacionSelect = document.querySelector('select[name="ubicacion_per"]');
        const stockContainer = document.getElementById('stock-container');

        function toggleStockField() {
            if (ubicacionSelect.value) {
                stockContainer.style.display = 'block';
            } else {
                stockContainer.style.display = 'none';
                // Limpia valor para evitar envíos erróneos
                document.querySelector('input[name="stock_per"]').value = '';
            }
        }

        ubicacionSelect.addEventListener('change', toggleStockField);

        // Mostrar/ocultar según valor guardado (por si hay error y el usuario recarga la página)
        toggleStockField();
    });
</script>
@endsection
