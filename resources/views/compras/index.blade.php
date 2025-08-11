@extends('layouts.app')

@section('content')
<!-- Font Awesome para íconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-[#B497BD] flex items-center gap-2">
            📝 <span>Órdenes de Compra</span>
        </h2>
        <a href="{{ route('compras.crear') }}" class="bg-[#B497BD] hover:bg-[#9e84aa] text-white px-4 py-2 rounded-lg shadow">
            ➕ Nueva Orden
        </a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto border border-gray-200">
        <table class="min-w-full divide-y divide-gray-300 text-sm text-[#2C2C2C]">
            <thead class="bg-[#EDE9F3] text-[#4B3F72] border-b border-gray-300">
                <tr>
                    <th class="px-4 py-3 font-semibold text-left">#</th>
                    <th class="px-4 py-3 font-semibold text-left">Orden</th>
                    <th class="px-4 py-3 font-semibold text-left">Fecha</th>
                    <th class="px-4 py-3 font-semibold text-left">Estado</th>
                    <th class="px-4 py-3 font-semibold text-right">Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-body" class="divide-y divide-gray-200 bg-white">
                <!-- Se llena con JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal detalles -->
<div id="modalDetalles" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6 relative">
    <button onclick="cerrarModal()" class="absolute top-2 right-2 text-[#B75D69] hover:text-[#9e3e4b] text-xl font-bold">&times;</button>
    <h3 class="text-xl font-bold mb-4 text-[#B497BD]" id="modalTitulo">Detalles de la Orden</h3>
    <div id="modalContenido" class="text-[#2C2C2C] space-y-2">
      <!-- Aquí se cargan detalles -->
    </div>
  </div>
</div>

<!-- Modal cancelar -->
<div id="modalCancelar" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
  <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
    <button onclick="cerrarModalCancelar()" class="absolute top-2 right-2 text-[#B75D69] hover:text-[#9e3e4b] text-xl font-bold">&times;</button>
    <h3 class="text-xl font-bold mb-4 text-[#B497BD]">Cancelar Orden</h3>
    <input type="hidden" id="ordenCancelarId">
    <textarea id="motivoCancelacion" rows="4" class="w-full border p-2 rounded" placeholder="Motivo de cancelación..."></textarea>
    <div class="mt-4 flex justify-end gap-2">
      <button onclick="cerrarModalCancelar()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cerrar</button>
      <button onclick="confirmarCancelacion()" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Cancelar orden</button>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
    const tablaBody = document.getElementById('tabla-body');

    async function cargarOrdenes() {
        try {
            const res = await fetch('/api/ordenes-compra');
            if (!res.ok) throw new Error('Error al cargar las órdenes');
            const data = await res.json();

            tablaBody.innerHTML = '';

            data.forEach((orden, i) => {
                let estadoBadge = '';
                switch (orden.estado) {
                    case 'Aceptada':
                        estadoBadge = `<span class="inline-flex items-center gap-1 px-2 py-1 bg-[#9DBF9E] text-white text-xs font-medium rounded-full"><i class="fas fa-check-circle"></i> Aceptada</span>`;
                        break;
                    case 'Cancelada':
                        estadoBadge = `<span class="inline-flex items-center gap-1 px-2 py-1 bg-[#B75D69] text-white text-xs font-medium rounded-full"><i class="fas fa-times-circle"></i> Cancelada</span>`;
                        break;
                    default:
                        estadoBadge = `<span class="inline-flex items-center gap-1 px-2 py-1 bg-[#D4AF37] text-[#2C2C2C] text-xs font-medium rounded-full"><i class="fas fa-clock"></i> ${orden.estado}</span>`;
                }

                let acciones = `<div class="flex justify-end gap-2 flex-wrap">`;

                acciones += `<a href="/compras/editar/${orden.id}" class="px-2 py-1 bg-[#B497BD] text-white rounded hover:bg-[#9e84aa] text-xs">Editar</a>`;
                acciones += `<button onclick="eliminar('${orden.id}')" class="px-2 py-1 bg-[#B75D69] text-white rounded hover:bg-[#9e3e4b] text-xs">Eliminar</button>`;

                if (orden.estado === 'Pendiente') {
                    acciones += `<button onclick="abrirModalCancelar('${orden.id}')" class="px-2 py-1 bg-[#D97706] text-white rounded hover:bg-[#b35f00] text-xs">Cancelar</button>`;
                }

                acciones += `</div>`;

                tablaBody.innerHTML += `
                    <tr class="hover:bg-[#F9F9F9] transition-colors duration-200">
                        <td class="px-4 py-3">${i + 1}</td>
                        <td class="px-4 py-3 font-medium text-[#B497BD] cursor-pointer hover:underline" onclick="mostrarDetalles('${orden.id}')">${orden.n_orden_compra}</td>
                        <td class="px-4 py-3">${new Date(orden.fecha).toLocaleDateString()}</td>
                        <td class="px-4 py-3">${estadoBadge}</td>
                        <td class="px-4 py-3 text-right">${acciones}</td>
                    </tr>`;
            });
        } catch (error) {
            alert(error.message);
        }
    }

    function eliminar(id) {
        if (!confirm('¿Eliminar esta orden?')) return;
        fetch(`/api/ordenes-compra/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(() => cargarOrdenes());
    }

    function mostrarDetalles(id) {
        fetch(`/api/ordenes-compra/${id}`)
            .then(res => {
                if (!res.ok) throw new Error('Orden no encontrada');
                return res.json();
            })
            .then(orden => {
                const contenido = `
                    <p><strong>Número de Orden:</strong> ${orden.n_orden_compra}</p>
                    <p><strong>Perfume:</strong> ${orden.id_perfume}</p>
                    <p><strong>Proveedor:</strong> ${orden.proveedor}</p>
                    <p><strong>Fecha:</strong> ${new Date(orden.fecha).toLocaleDateString()}</p>
                    <p><strong>Estado:</strong> ${orden.estado}</p>
                    <p><strong>Cantidad:</strong> ${orden.cantidad}</p>
                    <p><strong>Precio Unitario:</strong> $${orden.precio_unitario}</p>
                    <p><strong>Precio Total:</strong> $${orden.precio_total}</p>
                    <p><strong>Almacén:</strong> ${orden.almacen || 'N/A'}</p>
                    <p><strong>Observaciones:</strong> ${orden.observaciones || 'N/A'}</p>
                `;
                document.getElementById('modalContenido').innerHTML = contenido;
                document.getElementById('modalDetalles').classList.remove('hidden');
                document.getElementById('modalDetalles').classList.add('flex');
            })
            .catch(err => {
                alert(err.message);
            });
    }

    function cerrarModal() {
        document.getElementById('modalDetalles').classList.add('hidden');
        document.getElementById('modalDetalles').classList.remove('flex');
    }

    function abrirModalCancelar(id) {
        document.getElementById('ordenCancelarId').value = id;
        document.getElementById('motivoCancelacion').value
                document.getElementById('motivoCancelacion').value = '';
        document.getElementById('modalCancelar').classList.remove('hidden');
        document.getElementById('modalCancelar').classList.add('flex');
    }

    function cerrarModalCancelar() {
        document.getElementById('modalCancelar').classList.add('hidden');
        document.getElementById('modalCancelar').classList.remove('flex');
    }

    function confirmarCancelacion() {
        const id = document.getElementById('ordenCancelarId').value;
        const motivo = document.getElementById('motivoCancelacion').value.trim();

        if (!motivo) {
            alert('Por favor, escribe un motivo de cancelación.');
            return;
        }

        fetch(`/api/ordenes-compra/cancelar/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ motivo })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.mensaje || data.error || 'Operación realizada');
            cerrarModalCancelar();
            cargarOrdenes();
        })
        .catch(err => {
            alert('Error al cancelar la orden');
            console.error(err);
        });
    }

    setInterval(cargarOrdenes, 10000); // Refresca cada 10 segundos
    cargarOrdenes(); // Carga inicial
</script>
@endsection
