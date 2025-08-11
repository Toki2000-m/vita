@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-[#F4F4F4] p-8 rounded-xl shadow-md">
    <h2 class="text-3xl font-bold text-[#2C2C2C] mb-6">✏️ Editar Orden de Compra</h2>

    <form id="form-editar" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-sm font-semibold text-[#2C2C2C] mb-1">Número de Orden</label>
            <input type="text" name="n_orden_compra" class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gray-100" readonly>
        </div>

        <div>
            <label class="block text-sm font-semibold text-[#2C2C2C] mb-1">Perfume</label>
            <select name="id_perfume" id="id_perfume" class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white focus:ring-[#B497BD] focus:border-[#B497BD]" required>
                <option value="">Selecciona un perfume</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-[#2C2C2C] mb-1">Cantidad</label>
            <input type="number" name="cantidad" class="w-full px-4 py-2 rounded-lg border border-gray-300" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-[#2C2C2C] mb-1">Proveedor</label>
            <select name="proveedor" id="proveedor" class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-white focus:ring-[#B497BD] focus:border-[#B497BD]" required>
                <option value="">Selecciona un proveedor</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-semibold text-[#2C2C2C] mb-1">Fecha</label>
            <input type="date" name="fecha" class="w-full px-4 py-2 rounded-lg border border-gray-300" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-[#2C2C2C] mb-1">Solicitante</label>
            <input type="text" name="usuario_solicitante" class="w-full px-4 py-2 rounded-lg border border-gray-300" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-[#2C2C2C] mb-1">Precio Unitario</label>
            <input type="number" name="precio_unitario" class="w-full px-4 py-2 rounded-lg border border-gray-300" required>
        </div>

        <div>
            <label class="block text-sm font-semibold text-[#2C2C2C] mb-1">Almacén</label>
            <input type="text" name="almacen" class="w-full px-4 py-2 rounded-lg border border-gray-300" required>
        </div>

        <div class="md:col-span-2">
            <label class="block text-sm font-semibold text-[#2C2C2C] mb-1">Observaciones</label>
            <textarea name="observaciones" rows="3" class="w-full px-4 py-2 rounded-lg border border-gray-300"></textarea>
        </div>

        <div class="md:col-span-2 flex justify-between items-center mt-4">
            <button type="submit" class="px-6 py-3 bg-[#B497BD] text-white rounded-lg hover:bg-[#a07faf] transition">
                💾 Actualizar
            </button>
            <a href="/compras" class="px-6 py-3 bg-[#D4AF37] text-white rounded-lg hover:bg-[#c49e2f] transition">
                ↩️ Cancelar
            </a>
        </div>
    </form>
</div>

<script>
    const id = "{{ $id }}";

    // Cargar datos de la orden
    fetch('/api/ordenes-compra/' + id)
        .then(res => res.json())
        .then(orden => {
            for (let campo in orden) {
                const input = document.querySelector(`[name=${campo}]`);
                if (input) input.value = orden[campo];
            }
        });

    // Cargar proveedores
    fetch('/api/proveedores')
        .then(res => res.json())
        .then(proveedores => {
            const select = document.getElementById('proveedor');
            proveedores.forEach(p => {
                const option = document.createElement('option');
                option.value = p._id;
                option.textContent = p.nombre_proveedor;
                select.appendChild(option);
            });
        });

    // Cargar perfumes
    fetch('/api/perfumes')
        .then(res => res.json())
        .then(perfumes => {
            const select = document.getElementById('id_perfume');
            perfumes.forEach(p => {
                const option = document.createElement('option');
                option.value = p._id;
                option.textContent = p.name_per;
                select.appendChild(option);
            });
        });

    // Enviar actualización
    document.getElementById('form-editar').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = e.target;
        const data = Object.fromEntries(new FormData(form).entries());

        data.precio_total = data.precio_unitario * data.cantidad;

        fetch('/api/ordenes-compra/' + id, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => {
            if (!res.ok) throw new Error('Error al actualizar');
            return res.json();
        })
        .then(() => {
            alert('✅ Orden actualizada correctamente');
            window.location.href = '/compras';
        })
        .catch(err => {
            alert('❌ Error al actualizar la orden');
            console.error(err);
        });
    });
</script>
@endsection
