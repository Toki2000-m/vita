@extends('layouts.app')

@section('title', 'Crear Orden de Compra')

@section('content')
<div class="min-h-screen bg-[#F4F4F4] py-10 px-6">
    <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-[#2C2C2C] mb-6 flex items-center gap-2">
            🛒 <span class="text-[#B497BD]">Crear Orden de Compra</span>
        </h1>

        <form id="ordenForm" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-[#2C2C2C]">Número de Orden:</label>
                <input type="text" id="n_orden_compra" name="n_orden_compra" readonly
                    class="mt-1 block w-full rounded-md border border-gray-300 bg-gray-100 text-[#2C2C2C] px-3 py-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-[#2C2C2C]">Perfume:</label>
                <select id="id_perfume" name="id_perfume" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-[#2C2C2C]">
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#2C2C2C]">Proveedor:</label>
                <select id="proveedor" name="proveedor" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-[#2C2C2C]">
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#2C2C2C]">Cantidad:</label>
                    <input type="number" name="cantidad" min="1" required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-[#2C2C2C]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#2C2C2C]">Fecha:</label>
                    <input type="date" name="fecha" required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-[#2C2C2C]">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#2C2C2C]">Usuario Solicitante:</label>
                <input type="text" name="usuario_solicitante" required
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-[#2C2C2C]">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[#2C2C2C]">Precio Unitario:</label>
                    <input type="number" name="precio_unitario" step="0.01" min="0" required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-[#2C2C2C]">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#2C2C2C]">Almacén:</label>
                    <select name="almacen" required
                        class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-[#2C2C2C]">
                        <option value="">Selecciona un almacén</option>
                        <option value="ALM001">ALM001</option>
                        <option value="ALM002">ALM002</option>
                        <option value="ALM003">ALM003</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-[#2C2C2C]">Observaciones:</label>
                <textarea name="observaciones"
                    class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-[#2C2C2C]"></textarea>
            </div>

            <button type="submit"
                class="w-full bg-[#B497BD] hover:bg-[#9e7aa9] text-white font-semibold py-2 px-4 rounded transition">
                Crear Orden
            </button>
        </form>
    </div>
</div>

<!-- Toast flotante -->
<div id="toast" class="fixed bottom-6 right-6 bg-[#9DBF9E] text-white px-6 py-4 rounded-lg shadow-lg hidden z-50">
    <strong class="block text-lg">✅ Orden creada correctamente</strong>
    <span id="toastInfo" class="text-sm block mt-1"></span>
    <div class="mt-3 flex gap-2">
        <button onclick="crearOtra()" class="bg-white text-[#2C2C2C] px-3 py-1 rounded hover:bg-gray-100 text-sm">Crear otra</button>
        <!-- <button onclick="window.location.href='/ordenes-compra'" class="bg-[#B497BD] text-white px-3 py-1 rounded text-sm">Ver listado</button> -->
        <button onclick="cerrarToast()" class="text-white hover:text-gray-200 text-sm">✖</button>
    </div>
</div>

<script>
    // Cargar número de orden
    fetch('/api/ordenes-compra/ultimo-numero')
        .then(res => res.json())
        .then(data => {
            document.getElementById('n_orden_compra').value = data.nuevoNumeroOrden;
        });

    // Cargar perfumes
    fetch('/api/perfumes')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('id_perfume');
            data.forEach(p => {
                const option = document.createElement('option');
                option.value = p._id;
                option.textContent = p.name_per;
                select.appendChild(option);
            });
        });

    // Cargar proveedores
    fetch('/api/proveedores')
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById('proveedor');
            data.forEach(p => {
                const option = document.createElement('option');
                option.value = p._id;
                option.textContent = p.nombre_proveedor;
                select.appendChild(option);
            });
        });

    // Enviar formulario
    document.getElementById('ordenForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        fetch('/api/ordenes-compra', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            mostrarToast(res.n_orden_compra, res.estado);
            limpiarFormulario();
        })
        .catch(err => {
            document.getElementById('toastInfo').innerHTML = '❌ Error al crear la orden';
            document.getElementById('toast').classList.remove('hidden');
        });
    });

    function mostrarToast(numero, estado) {
        const toast = document.getElementById('toast');
        const info = document.getElementById('toastInfo');
        info.innerHTML = `Número: <strong>${numero}</strong><br>Estado: <strong>${estado}</strong>`;
        toast.classList.remove('hidden');
        setTimeout(() => {
            toast.classList.add('hidden');
        }, 7000);
    }

    function cerrarToast() {
        document.getElementById('toast').classList.add('hidden');
    }

    function limpiarFormulario() {
        const form = document.getElementById('ordenForm');
        form.reset();
        // Regenerar número de orden
        fetch('/api/ordenes-compra/ultimo-numero')
            .then(res => res.json())
            .then(data => {
                document.getElementById('n_orden_compra').value = data.nuevoNumeroOrden;
            });
    }

    function crearOtra() {
        cerrarToast();
        limpiarFormulario();
    }
</script>
@endsection
