@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">

    <h1 class="text-3xl font-semibold mb-6 text-purple-700">📊 Reportes de Ventas</h1>

    {{-- Formulario de filtro --}}
    <form id="formReporte" class="flex flex-wrap gap-4 items-end mb-8" onsubmit="event.preventDefault(); consultarReporte();">
        <div class="flex flex-col">
            <label for="fecha_inicio" class="text-gray-700 font-medium mb-1">Fecha Inicio</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required
                class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                value="{{ old('fecha_inicio') }}">
        </div>

        <div class="flex flex-col">
            <label for="fecha_fin" class="text-gray-700 font-medium mb-1">Fecha Fin</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required
                class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-500"
                value="{{ old('fecha_fin') }}">
        </div>

        <button type="submit"
            class="bg-purple-600 text-white px-6 py-2 rounded-md hover:bg-purple-700 transition-shadow shadow-md">
            Consultar
        </button>
    </form>

    {{-- Resultados Ventas por Almacén --}}
    <section class="mb-12">
        <h2 class="text-2xl font-semibold text-purple-700 mb-4">🏪 Ventas por Almacén</h2>

        <div id="ventasPorAlmacen" class="space-y-6">
            <p class="text-gray-500">Aquí aparecerán las ventas por almacén tras consultar.</p>
        </div>
    </section>

    {{-- Resultados Productos Más Vendidos --}}
    <section>
        <h2 class="text-2xl font-semibold text-purple-700 mb-4">🧴 Productos Más Vendidos</h2>

        <table class="min-w-full border border-gray-300 rounded-md overflow-hidden">
            <thead class="bg-purple-100 text-purple-800 font-semibold">
                <tr>
                    <th class="py-3 px-4 text-left border-r border-purple-200">Perfume</th>
                    <th class="py-3 px-4 text-right">Total Vendido</th>
                </tr>
            </thead>
            <tbody id="productosMasVendidos" class="divide-y divide-gray-200">
                <tr><td colspan="2" class="py-3 px-4 text-center text-gray-500">Aquí aparecerán los productos más vendidos tras consultar.</td></tr>
            </tbody>
        </table>
    </section>
</div>

<script>
    async function consultarReporte() {
        const inicio = document.getElementById('fecha_inicio').value;
        const fin = document.getElementById('fecha_fin').value;

        if (!inicio || !fin) {
            alert('Por favor ingresa ambas fechas.');
            return;
        }

        // Limpiar resultados previos
        const contVentas = document.getElementById('ventasPorAlmacen');
        const contProductos = document.getElementById('productosMasVendidos');
        contVentas.innerHTML = '<p class="text-gray-500">Cargando...</p>';
        contProductos.innerHTML = '<tr><td colspan="2" class="py-3 px-4 text-center text-gray-500">Cargando...</td></tr>';

        try {
            // Ventas por almacén
            const resVentas = await fetch(`/api/ventas-por-almacen?fecha_inicio=${inicio}&fecha_fin=${fin}`);
            if (!resVentas.ok) throw new Error('Error en ventas por almacén');
            const dataVentas = await resVentas.json();

            if (Object.keys(dataVentas).length === 0) {
                contVentas.innerHTML = '<p class="text-gray-500">No se encontraron ventas en el rango seleccionado.</p>';
            } else {
                let htmlVentas = '';
                for (const almacen in dataVentas) {
                    htmlVentas += `
                        <div class="border border-purple-300 rounded-md p-4 bg-purple-50 shadow-sm">
                            <h3 class="font-semibold text-purple-700 mb-2">${almacen} - Total vendido: ${dataVentas[almacen].total}</h3>
                            <ul class="list-disc list-inside space-y-1 max-h-48 overflow-y-auto text-gray-700">
                    `;
                    for (const perfume in dataVentas[almacen].perfumes) {
                        htmlVentas += `<li>${perfume}: ${dataVentas[almacen].perfumes[perfume]}</li>`;
                    }
                    htmlVentas += '</ul></div>';
                }
                contVentas.innerHTML = htmlVentas;
            }

            // Productos más vendidos
            const resProd = await fetch(`/api/productos-mas-vendidos?fecha_inicio=${inicio}&fecha_fin=${fin}`);
            if (!resProd.ok) throw new Error('Error en productos más vendidos');
            const dataProd = await resProd.json();

            if (Object.keys(dataProd).length === 0) {
                contProductos.innerHTML = '<tr><td colspan="2" class="py-3 px-4 text-center text-gray-500">No se encontraron productos vendidos.</td></tr>';
            } else {
                let htmlProd = '';
                for (const perfume in dataProd) {
                    htmlProd += `
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="py-3 px-4 border-b border-gray-200">${perfume}</td>
                            <td class="py-3 px-4 border-b border-gray-200 text-right">${dataProd[perfume]}</td>
                        </tr>
                    `;
                }
                contProductos.innerHTML = htmlProd;
            }
        } catch (error) {
            contVentas.innerHTML = '<p class="text-red-600 font-semibold">Error al consultar los datos.</p>';
            contProductos.innerHTML = '<tr><td colspan="2" class="py-3 px-4 text-center text-red-600 font-semibold">Error al consultar los datos.</td></tr>';
            console.error(error);
        }
    }
</script>
@endsection
