<!-- Incluye estas librerías en tu layout principal o aquí -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.css" />

<div class="max-w-xl mx-auto">
    <form id="form-entrada" class="space-y-4 bg-[#F9F9F9] p-6 rounded-xl shadow-md">
        <h3 class="text-xl font-semibold text-[#2C2C2C] mb-4">Registrar Entrada</h3>

        <!-- Orden de Compra -->
<div>
    <label class="block text-sm font-medium text-gray-700">Orden de Compra (opcional)</label>
    <select name="orden_compra_id" id="orden_compra_id" class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#B497BD]">
        <option value="">-- Sin orden de compra --</option>
        @foreach ($ordenesPendientes as $orden)
            <option value="{{ $orden->_id }}">{{ $orden->n_orden_compra }} - {{ $orden->cantidad }} pz</option>
        @endforeach
    </select>
</div>


        <!-- Proveedor -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Proveedor</label>
            <select name="proveedor_id" required class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#B497BD]">
                <option value="">-- Selecciona un proveedor --</option>
                @foreach ($proveedores as $proveedor)
                    <option value="{{ $proveedor->_id }}">{{ $proveedor->nombre_proveedor ?? 'Proveedor sin nombre' }}</option>
                @endforeach
            </select>
        </div>

        <!-- Perfume con autocompletado -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Perfume</label>
            <input id="input-perfume" class="w-full p-2 rounded border border-gray-300" autocomplete="off" />
            <input type="text" name="perfume_id" id="perfume-id" readonly class="w-full mt-2 p-2 rounded border border-gray-300 bg-gray-100 text-gray-700" placeholder="ID del perfume aparecerá aquí">
        </div>

        <!-- Almacén destino -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Código del Almacén destino</label>
            <select name="almacen_destino_codigo" required class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#B497BD]">
                <option value="">-- Selecciona un almacén --</option>
                <option value="ALM001">ALM001</option>
                <option value="ALM002">ALM002</option>
                <option value="ALM003">ALM003</option>
            </select>
        </div>

        <!-- Cantidad -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Cantidad</label>
            <input type="number" name="cantidad" min="1" class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#B497BD]" required>
        </div>

        <!-- Motivo -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Motivo (opcional)</label>
            <input type="text" name="motivo" class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#B497BD]">
        </div>

        <!-- Referencia -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Referencia (opcional)</label>
            <input type="text" name="referencia" class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#B497BD]">
        </div>

        <!-- Botón enviar -->
        <div class="text-right">
            <button type="submit" class="bg-[#B497BD] text-white px-4 py-2 rounded hover:bg-[#9c80a7] transition">Guardar Entrada</button>
        </div>

        <!-- Mensaje -->
        <div id="entrada-msg" class="text-sm mt-2"></div>
    </form>
</div>

<!-- Script para autocompletado y envío -->
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const input = document.getElementById('input-perfume');
    const hiddenInput = document.getElementById('perfume-id');
    const ordenSelect = document.getElementById('orden_compra_id');

    let perfumeList = [];

    try {
        const res = await fetch('/api/perfumes');
        const perfumes = await res.json();

        perfumeList = perfumes.map(p => ({
            label: `${p.name_per} - ${p._id}`,
            value: p._id
        }));

        new Awesomplete(input, {
            list: perfumeList.map(p => p.label),
            minChars: 1,
            autoFirst: true
        });

        input.addEventListener('awesomplete-selectcomplete', function (e) {
            const seleccionado = perfumeList.find(p => p.label === e.text.value);
            if (seleccionado) {
                hiddenInput.value = seleccionado.value;
            }
        });
    } catch (err) {
        console.error('❌ Error cargando perfumes:', err);
    }

    ordenSelect.addEventListener('change', async function () {
        const ordenId = this.value;
        if (!ordenId) return;

        try {
            const res = await fetch(`/api/ordenes-compra/${ordenId}`);
            const orden = await res.json();

            const nombrePerfume = perfumeList.find(p => p.value === orden.id_perfume)?.label || orden.id_perfume;
            input.value = nombrePerfume;
            hiddenInput.value = orden.id_perfume;

            document.querySelector('select[name="proveedor_id"]').value = orden.proveedor;
            document.querySelector('select[name="almacen_destino_codigo"]').value = orden.almacen;
            document.querySelector('input[name="cantidad"]').value = orden.cantidad;
            document.querySelector('input[name="referencia"]').value = orden.n_orden_compra;
        } catch (err) {
            console.error('❌ Error al cargar orden de compra:', err);
        }
    });

    document.getElementById('form-entrada').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const data = {
            perfume_id: form.perfume_id.value,
            almacen_destino_codigo: form.almacen_destino_codigo.value,
            cantidad: parseInt(form.cantidad.value),
            motivo: form.motivo.value || null,
            referencia: form.referencia.value || null,
            proveedor_id: form.proveedor_id.value,
            orden_compra_id: form.orden_compra_id.value || null
        };

        const msg = document.getElementById('entrada-msg');
        msg.innerText = "Procesando...";

        try {
            const res = await fetch('/transacciones/entradas', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });

            const result = await res.json();

            if (res.ok) {
                msg.innerText = result.message || '✅ Entrada registrada correctamente.';
                msg.className = 'text-green-600';
                form.reset();
                hiddenInput.value = '';
                input.value = '';
            } else {
                msg.innerText = result.error || '❌ Error al registrar entrada.';
                msg.className = 'text-red-600';
            }
        } catch (err) {
            msg.innerText = '❌ Error de red o servidor.';
            msg.className = 'text-red-600';
        }
    });
});
</script>
