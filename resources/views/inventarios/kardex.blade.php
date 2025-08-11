@extends('layouts.app')

@section('title', 'Kardex de Inventarios')

@section('content')
<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Histórico de movimientos</h2>

    <form id="filtrosForm" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="producto_id" class="block text-sm font-medium text-gray-700">ID Producto</label>
            <input type="text" id="producto_id" name="producto_id" placeholder="ObjectId producto" 
                   class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500">
        </div>
        <div>
            <label for="tipo" class="block text-sm font-medium text-gray-700">Tipo de movimiento</label>
            <select id="tipo" name="tipo" 
                    class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500">
                <option value="">-- Todos --</option>
                <option value="entrada">Entrada</option>
                <option value="salida">Salida</option>
                <option value="traspaso">Traspaso</option>
            </select>
        </div>
        <div>
            <label for="desde" class="block text-sm font-medium text-gray-700">Desde</label>
            <input type="date" id="desde" name="desde" 
                   class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500">
        </div>
        <div>
            <label for="hasta" class="block text-sm font-medium text-gray-700">Hasta</label>
            <input type="date" id="hasta" name="hasta" 
                   class="mt-1 block w-full rounded-md border border-gray-300 shadow-sm focus:ring-purple-500 focus:border-purple-500">
        </div>

        <div class="md:col-span-4 flex gap-4 mt-4 items-center">
            <button type="button" id="btnBuscar" 
                class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700 transition flex items-center gap-2">
                🔍 Buscar
            </button>

            <button type="button" id="btnExportarPdf" 
                class="px-4 py-2 bg-white text-red-600 border border-red-600 rounded hover:bg-red-50 transition flex items-center gap-2" 
                title="Exportar PDF">
                <img src="{{ asset('icons/pdf.svg') }}" alt="PDF" width="24" height="24" />
                PDF
            </button>

        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse border border-gray-300 text-left">
            <thead class="bg-purple-600 text-white">
                <tr>
                    <th class="border border-gray-300 px-4 py-2">Producto ID</th>
                    <th class="border border-gray-300 px-4 py-2">Tipo</th>
                    <th class="border border-gray-300 px-4 py-2">Cantidad</th>
                    <th class="border border-gray-300 px-4 py-2">Fecha y Hora</th>
                    <th class="border border-gray-300 px-4 py-2">Almacén Origen</th>
                    <th class="border border-gray-300 px-4 py-2">Almacén Destino</th>
                    <th class="border border-gray-300 px-4 py-2">Referencia</th>
                </tr>
            </thead>
            <tbody id="tablaKardexBody" class="bg-white"></tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('btnBuscar').addEventListener('click', function () {
    const producto_id = document.getElementById('producto_id').value.trim();
    const tipo = document.getElementById('tipo').value;
    const desde = document.getElementById('desde').value;
    const hasta = document.getElementById('hasta').value;

    const body = {};
    if(producto_id) body.perfume_id = producto_id;
    if(tipo) body.tipo = tipo;
    if(desde) body.desde = desde;
    if(hasta) body.hasta = hasta;

    fetch('/api/movimientos_inventario/kardex', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(body)
    })
    .then(res => res.json())
    .then(data => {
        if(data.status === 'ok') {
            console.log('Perfumes Map:', data.perfumes_map);
            const tbody = document.getElementById('tablaKardexBody');
            tbody.innerHTML = '';

            const perfumesMap = data.perfumes_map || {};
            const almacenesMap = data.almacenes_map || {};

            data.movimientos.forEach(mov => {
    const perfumeId = typeof mov.perfume_id === 'object' && mov.perfume_id.$oid
        ? mov.perfume_id.$oid
        : mov.perfume_id;

    const perfumeNombre = perfumesMap[perfumeId] || '—';

    console.log('ID:', perfumeId, 'Nombre:', perfumeNombre);

    const almacenOrigenId = mov.almacen_origen_id?.$oid || mov.almacen_origen_id || '';
    const almacenDestinoId = mov.almacen_destino_id?.$oid || mov.almacen_destino_id || '';
    const fecha = mov.timestamp ? new Date(mov.timestamp).toLocaleString() : '';

    const almacenOrigen = almacenesMap[almacenOrigenId] || {};
    const almacenDestino = almacenesMap[almacenDestinoId] || {};

    const origenTexto = almacenOrigenId
        ? `${almacenOrigenId}<br><small class="text-gray-500">${almacenOrigen.codigo || ''} - ${almacenOrigen.nombre || ''}</small>`
        : '—';

    const destinoTexto = almacenDestinoId
        ? `${almacenDestinoId}<br><small class="text-gray-500">${almacenDestino.codigo || ''} - ${almacenDestino.nombre || ''}</small>`
        : '—';

    tbody.innerHTML += `
        <tr>
            <td class="border border-gray-300 px-4 py-2" title="${perfumeId}">
                ${perfumeNombre}
            </td>
            <td class="border border-gray-300 px-4 py-2">${mov.tipo || ''}</td>
            <td class="border border-gray-300 px-4 py-2">${mov.cantidad || ''}</td>
            <td class="border border-gray-300 px-4 py-2">${fecha}</td>
            <td class="border border-gray-300 px-4 py-2">${origenTexto}</td>
            <td class="border border-gray-300 px-4 py-2">${destinoTexto}</td>
            <td class="border border-gray-300 px-4 py-2">${mov.referencia || ''}</td>
        </tr>
    `;
});

        } else {
            alert('Error al obtener datos');
        }
    })
    .catch(err => {
        alert('Error en la consulta');
        console.error(err);
    });
});

document.getElementById('btnExportarPdf').addEventListener('click', () => {
    const params = new URLSearchParams({
        perfume_id: document.getElementById('producto_id').value.trim(),
        tipo: document.getElementById('tipo').value,
        desde: document.getElementById('desde').value,
        hasta: document.getElementById('hasta').value
    });

    window.open('/movimientos_inventario/kardex/pdf?' + params.toString(), '_blank');
});


</script>

@endsection
