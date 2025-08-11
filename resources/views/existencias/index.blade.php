@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h2 class="text-3xl font-bold text-[#2C2C2C] mb-8">📦 Existencias por Almacén</h2>

    <!-- Filtros -->
    <div class="bg-white border border-[#B497BD] rounded-xl p-6 mb-8 shadow-sm">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="filtro-nombre" class="block text-sm font-medium text-[#2C2C2C] mb-1">Buscar perfume</label>
                <input 
                    type="text" 
                    id="filtro-nombre" 
                    placeholder="Nombre del perfume..." 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#B497BD]"
                />
            </div>

            <div>
                <label for="filtro-almacen" class="block text-sm font-medium text-[#2C2C2C] mb-1">Filtrar por almacén</label>
                <select 
                    id="filtro-almacen" 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white focus:outline-none focus:ring-2 focus:ring-[#B497BD]"
                >
                    <option value="">Todos los almacenes</option>
                    <option value="ALM001">ALM001</option>
                    <option value="ALM002">ALM002</option>
                    <option value="ALM003">ALM003</option>
                </select>
            </div>

            <div class="flex items-end">
                <button 
                    id="btn-filtrar" 
                    class="w-full px-4 py-2 bg-[#B497BD] text-white rounded-lg hover:bg-[#a07faf] transition"
                >
                    🔍 Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-x-auto bg-white rounded-xl shadow-md border border-[#B497BD]">
        <table class="min-w-full table-auto">
            <thead class="bg-[#F4F4F4] text-[#2C2C2C]">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">Nombre</th>
                    <th class="px-4 py-3 text-left font-semibold">Marca</th>
                    <th class="px-4 py-3 text-left font-semibold">Categoría</th>
                    <th class="px-4 py-3 text-left font-semibold">Precio</th>
                    <th class="px-4 py-3 text-left font-semibold">Stock Actual</th>
                    <th class="px-4 py-3 text-left font-semibold">ALM001</th>
                    <th class="px-4 py-3 text-left font-semibold">ALM002</th>
                    <th class="px-4 py-3 text-left font-semibold">ALM003</th>
                    <th class="px-4 py-3 text-left font-semibold">Expira</th>
                </tr>
            </thead>
            <tbody id="tabla-existencias" class="text-[#2C2C2C]">
                <!-- Datos dinámicos -->
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('btn-filtrar').addEventListener('click', async () => {
    const nombre = document.getElementById('filtro-nombre').value.trim();
    const almacen = document.getElementById('filtro-almacen').value;
    const tabla = document.getElementById('tabla-existencias');
    tabla.innerHTML = `
        <tr>
            <td colspan="9" class="text-center py-6 text-[#2C2C2C]">🔄 Cargando existencias...</td>
        </tr>
    `;

    let url = '/api/existencias?';
    if (nombre) url += `nombre=${encodeURIComponent(nombre)}&`;
    if (almacen) url += `almacen_codigo=${encodeURIComponent(almacen)}&`;

    try {
        const res = await fetch(url);
        const perfumes = await res.json();

        if (!perfumes.length) {
            tabla.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-6 text-[#B75D69] font-semibold">
                        ❌ No se encontraron resultados.
                    </td>
                </tr>
            `;
            return;
        }

        tabla.innerHTML = perfumes.map(p => `
            <tr class="border-t hover:bg-[#F4F4F4] transition">
                <td class="px-4 py-3 font-semibold">${p.name_per}</td>
                <td class="px-4 py-3">${p.marca}</td>
                <td class="px-4 py-3">${p.categoria_per}</td>
                <td class="px-4 py-3">$${p.precio_venta_per}</td>
                <td class="px-4 py-3 font-bold text-[#1D4E89]">${p.stock_actual}</td>
                <td class="px-4 py-3">${p.stock_por_almacen?.ALM001 ?? 0}</td>
                <td class="px-4 py-3">${p.stock_por_almacen?.ALM002 ?? 0}</td>
                <td class="px-4 py-3">${p.stock_por_almacen?.ALM003 ?? 0}</td>
                <td class="px-4 py-3">${p.fecha_expiracion?.substring(0, 10) ?? '—'}</td>
            </tr>
        `).join('');
    } catch (err) {
        console.error('❌ Error al cargar existencias:', err);
        tabla.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-6 text-[#B75D69] font-semibold">
                    ❌ Error al cargar datos.
                </td>
            </tr>
        `;
    }
});
</script>
@endsection
