<!-- Awesomplete CSS y JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.js"></script>

<div class="max-w-xl mx-auto">
    <form id="form-traspaso" class="space-y-4 bg-[#EFF8FF] p-6 rounded-xl shadow-md">
        <h3 class="text-xl font-semibold text-[#1D4E89] mb-4">Registrar Traspaso</h3>

        <!-- Perfume con autocompletado -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Perfume</label>
            <input 
                id="input-perfume-traspaso" 
                class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#1D4E89]" 
                autocomplete="off" 
                placeholder="Escribe para buscar perfume..."
                required
            />
            <input 
                type="text" 
                name="perfume_id" 
                id="perfume-id-traspaso" 
                readonly 
                class="w-full mt-2 p-2 rounded border border-gray-300 bg-gray-100 text-gray-700" 
                placeholder="ID del perfume aparecerá aquí"
                required
            >
        </div>

        <!-- Almacén origen -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Código del Almacén Origen</label>
            <select name="almacen_origen_codigo" required class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#1D4E89]">
                <option value="">-- Selecciona un almacén --</option>
                <option value="ALM001">ALM001</option>
                <option value="ALM002">ALM002</option>
                <option value="ALM003">ALM003</option>
            </select>
        </div>

        <!-- Almacén destino -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Código del Almacén Destino</label>
            <select name="almacen_destino_codigo" required class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#1D4E89]">
                <option value="">-- Selecciona un almacén --</option>
                <option value="ALM001">ALM001</option>
                <option value="ALM002">ALM002</option>
                <option value="ALM003">ALM003</option>
            </select>
        </div>

        <!-- Cantidad -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Cantidad</label>
            <input type="number" name="cantidad" min="1" class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#1D4E89]" required>
        </div>

        <!-- Motivo -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Motivo</label>
            <input type="text" name="motivo" class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#1D4E89]" required>
        </div>

        <!-- Referencia -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Referencia (opcional)</label>
            <input type="text" name="referencia" class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#1D4E89]">
        </div>

        <!-- Botón enviar -->
        <div class="text-right">
            <button type="submit" class="bg-[#1D4E89] text-white px-4 py-2 rounded hover:bg-[#163a66] transition">Guardar Traspaso</button>
        </div>

        <!-- Mensaje -->
        <div id="traspaso-msg" class="text-sm mt-2"></div>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', async () => {
    const input = document.getElementById('input-perfume-traspaso');
    const hiddenInput = document.getElementById('perfume-id-traspaso');

    try {
        const res = await fetch('/api/perfumes');
        const perfumes = await res.json();

        console.log('Perfumes cargados para traspasos:', perfumes);

        const perfumeList = perfumes.map(p => ({
            label: `${p.name_per} - ${p._id}`,
            value: p._id
        }));

        const awesomplete = new Awesomplete(input, {
            minChars: 1,
            autoFirst: true
        });

        awesomplete.list = perfumeList.map(p => p.label);

        input.addEventListener('awesomplete-selectcomplete', function (e) {
            const seleccionado = perfumeList.find(p => p.label === e.text.value);
            hiddenInput.value = seleccionado ? seleccionado.value : '';
        });
    } catch (err) {
        console.error('❌ Error cargando perfumes para traspasos:', err);
    }
});

document.getElementById('form-traspaso').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const origen = form.almacen_origen_codigo.value;
    const destino = form.almacen_destino_codigo.value;
    const msg = document.getElementById('traspaso-msg');
    msg.innerText = "";

    if (origen === destino) {
        msg.innerText = "❌ El almacén origen y destino no pueden ser iguales.";
        msg.className = 'text-red-600';
        return;
    }

    if (!form.perfume_id.value) {
        msg.innerText = "❌ Debes seleccionar un perfume válido.";
        msg.className = 'text-red-600';
        return;
    }

    const data = {
    perfume_id: form.perfume_id.value,
    almacen_origen_codigo: origen,
    almacen_destino_codigo: destino,
    cantidad: parseInt(form.cantidad.value),
    motivo: form.motivo.value,
    referencia: form.referencia.value || null
};


    msg.innerText = "Procesando...";

    try {
        const res = await fetch('/transacciones/traspasos', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(data)
});


        const text = await res.text();
        let result;

        try {
            result = JSON.parse(text);
        } catch {
            msg.innerText = '❌ Respuesta no es JSON válido.';
            msg.className = 'text-red-600';
            return;
        }

        if (res.ok) {
            msg.innerText = result.mensaje || '✅ Traspaso registrado correctamente.';
            msg.className = 'text-green-600';
            form.reset();
            document.getElementById('perfume-id-traspaso').value = '';
        } else {
            msg.innerText = result.error || '❌ Error al registrar traspaso.';
            msg.className = 'text-red-600';
        }
    } catch (err) {
        console.error('❌ Error en la solicitud:', err);
        msg.innerText = '❌ Error de red o servidor.';
        msg.className = 'text-red-600';
    }
});
</script>
