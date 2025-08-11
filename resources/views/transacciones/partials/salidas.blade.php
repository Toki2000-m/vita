<!-- Incluye estas librerías (solo una vez en tu layout principal) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.5/awesomplete.min.js"></script>

<div class="max-w-xl mx-auto">
    <form id="form-salida" class="space-y-4 bg-[#FFF3F3] p-6 rounded-xl shadow-md">
        <h3 class="text-xl font-semibold text-[#8B1E3F] mb-4">Registrar Salida</h3>

        <!-- Perfume con autocompletado -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Perfume</label>
            <input 
                id="input-perfume-salida" 
                class="w-full p-2 rounded border border-gray-300" 
                autocomplete="off" 
                placeholder="Escribe para buscar perfume..."
            />
            <input 
                type="text" 
                name="perfume_id" 
                id="perfume-id-salida" 
                readonly
                class="w-full mt-2 p-2 rounded border border-gray-300 bg-gray-100 text-gray-700" 
                placeholder="ID del perfume aparecerá aquí"
            >
        </div>

        <!-- Almacén origen -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Código del Almacén origen</label>
            <select 
                name="almacen_origen_codigo" 
                required 
                class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#8B1E3F]"
            >
                <option value="">-- Selecciona un almacén --</option>
                <option value="ALM001">ALM001</option>
                <option value="ALM002">ALM002</option>
                <option value="ALM003">ALM003</option>
            </select>
        </div>

        <!-- Cantidad -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Cantidad</label>
            <input 
                type="number" 
                name="cantidad" 
                min="1" 
                class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#8B1E3F]" 
                required
            >
        </div>

        <!-- Motivo -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Motivo</label>
            <input 
                type="text" 
                name="motivo" 
                class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#8B1E3F]" 
                required
            >
        </div>

        <!-- Referencia -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Referencia (opcional)</label>
            <input 
                type="text" 
                name="referencia" 
                class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:border-[#8B1E3F]"
            >
        </div>

        <!-- Botón enviar -->
        <div class="text-right">
            <button 
                type="submit" 
                class="bg-[#8B1E3F] text-white px-4 py-2 rounded hover:bg-[#741c38] transition"
            >
                Guardar Salida
            </button>
        </div>

        <!-- Mensaje -->
        <div id="salida-msg" class="text-sm mt-2"></div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const input = document.getElementById('input-perfume-salida');
    const hiddenInput = document.getElementById('perfume-id-salida');

    try {
        const res = await fetch('/api/perfumes');
        const perfumes = await res.json();

        console.log('Perfumes cargados:', perfumes);

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
        console.error('❌ Error cargando perfumes:', err);
    }
});

document.getElementById('form-salida').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const data = {
        perfume_id: form.perfume_id.value,
        almacen_origen_codigo: form.almacen_origen_codigo.value,
        cantidad: parseInt(form.cantidad.value),
        motivo: form.motivo.value,
        referencia: form.referencia.value || null
    };

    const msg = document.getElementById('salida-msg');
    msg.innerText = "Procesando...";

    // 🚨 Validación manual
    if (!data.perfume_id) {
        msg.innerText = '❌ Debes seleccionar un perfume del autocompletado.';
        msg.className = 'text-red-600';
        return;
    }

    try {
        const res = await fetch('/api/movimientos/salida/venta', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        const result = await res.json();
        console.log('🟢 Respuesta:', result);

        if (res.ok) {
            msg.innerText = result.message || '✅ Salida registrada correctamente.';
            msg.className = 'text-green-600';
            form.reset();
            document.getElementById('perfume-id-salida').value = '';
            document.getElementById('input-perfume-salida').value = '';
        } else {
            msg.innerText = result.error || '❌ Error al registrar salida.';
            msg.className = 'text-red-600';
        }
    } catch (err) {
        console.error('❌ Error:', err);
        msg.innerText = '❌ Error de red o servidor.';
        msg.className = 'text-red-600';
    }
});
</script>
