@extends('layouts.app')

@section('styles')
<style>
  /* Colores personalizados */
  .bg-lavanda-suave { background-color: #B497BD; }
  .bg-gris-perla { background-color: #9917d5; }
  .text-gris-oscuro { color: #2C2C2C; }
  .bg-oro-palido { background-color: #D4AF37; }
  .text-oro-palido { color: #D4AF37; }
  .bg-verde-salvia { background-color: #9DBF9E; }
  .text-verde-salvia { color: #9DBF9E; }
  .bg-rojo-vino { background-color: #B75D69; }
  .text-rojo-vino { color: #B75D69; }
  /* Opcional para botones secundarios */
  .btn-secundario {
    background-color: #D4AF37;
    color: #2C2C2C;
  }
  .btn-secundario:hover {
    background-color: #b6922a;
  }
</style>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 bg-gris-perla rounded-lg shadow">

    <h2 class="text-3xl font-semibold text-gris-oscuro mb-6 bg-lavanda-suave p-4 rounded-md text-center">
        Panel de Control
    </h2>

    @if(session('name_user'))
    <div class="bg-verde-salvia text-white rounded-md p-4 mb-6 flex items-center gap-3 font-semibold">
        <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM7 10l5-5-1.5-1.5L7 7 5.5 5.5 4 7l3 3z"/>
        </svg>
        <span>¡Hola, <strong>{{ session('name_user') }}</strong>!</span>
    </div>
    @endif

    {{-- Filtros --}}
    <form id="filtrosForm" method="GET" action="{{ route('dashboard') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-8">
        <div>
            <label for="filtro-periodo" class="block text-sm font-medium text-gris-oscuro mb-1">Periodo</label>
            <select id="filtro-periodo" name="periodo" class="block w-full rounded-md border border-lavanda-suave shadow-sm focus:border-lavanda-suave focus:ring focus:ring-lavanda-suave focus:ring-opacity-50">
                <option value="mensual" {{ (isset($periodo) && $periodo == 'mensual') ? 'selected' : '' }}>Mensual</option>
                <option value="semanal" {{ (isset($periodo) && $periodo == 'semanal') ? 'selected' : '' }}>Semanal</option>
                <option value="personalizado" {{ (isset($periodo) && $periodo == 'personalizado') ? 'selected' : '' }}>Personalizado</option>
            </select>
        </div>
        <div>
            <label for="filtro-desde" class="block text-sm font-medium text-gris-oscuro mb-1">Desde</label>
            <input type="date" id="filtro-desde" name="desde" value="{{ $desde ?? '' }}" class="block w-full rounded-md border border-lavanda-suave shadow-sm focus:border-lavanda-suave focus:ring focus:ring-lavanda-suave focus:ring-opacity-50" {{ (isset($periodo) && $periodo != 'personalizado') ? 'disabled' : '' }} />
        </div>
        <div>
            <label for="filtro-hasta" class="block text-sm font-medium text-gris-oscuro mb-1">Hasta</label>
            <input type="date" id="filtro-hasta" name="hasta" value="{{ $hasta ?? '' }}" class="block w-full rounded-md border border-lavanda-suave shadow-sm focus:border-lavanda-suave focus:ring focus:ring-lavanda-suave focus:ring-opacity-50" {{ (isset($periodo) && $periodo != 'personalizado') ? 'disabled' : '' }} />
        </div>
        <div class="flex items-end">
         <button 
    type="button" 
    class="btn" 
    style="background-color: #17a2b8; color: white; border-radius: 8px; padding: 6px 20px; border: none;">
    Aplicar filtros
</button>
        </div>
    </form>

    {{-- Gráficas --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-gris-perla rounded-lg shadow p-6 flex flex-col">
            <h3 class="text-lg font-semibold text-gris-oscuro mb-4 text-center">Productos más vendidos</h3>
            <div class="flex-grow">
                <canvas id="chartProductos" class="w-full h-72"></canvas>
            </div>
        </div>

        <div class="bg-gris-perla rounded-lg shadow p-6 flex flex-col">
            <h3 class="text-lg font-semibold text-gris-oscuro mb-4 text-center">Ventas por sucursal</h3>
            <div class="flex-grow">
                <canvas id="chartSucursales" class="w-full h-72"></canvas>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const filtroPeriodo = document.getElementById('filtro-periodo');
    const filtroDesde = document.getElementById('filtro-desde');
    const filtroHasta = document.getElementById('filtro-hasta');

    function toggleFechas() {
        if(filtroPeriodo.value === 'personalizado') {
            filtroDesde.disabled = false;
            filtroHasta.disabled = false;
        } else {
            filtroDesde.disabled = true;
            filtroHasta.disabled = true;
            filtroDesde.value = '';
            filtroHasta.value = '';
        }
    }
    filtroPeriodo.addEventListener('change', toggleFechas);
    toggleFechas();

    const ctxProductos = document.getElementById('chartProductos').getContext('2d');
    const ctxSucursales = document.getElementById('chartSucursales').getContext('2d');

    const productosLabels = @json($productosLabels);
    const productosData = @json($productosData);
    const sucursalesLabels = @json($sucursalesLabels);
    const sucursalesData = @json($sucursalesData);

    const colorsProductos = ['#B497BD', '#D4AF37', '#9DBF9E', '#B75D69', '#2C2C2C', '#F4F4F4'];
    const colorsSucursales = ['#B497BD', '#D4AF37', '#9DBF9E', '#B75D69'];

    const chartProductos = new Chart(ctxProductos, {
        type: 'bar',
        data: {
            labels: productosLabels,
            datasets: [{
                label: 'Ventas',
                data: productosData,
                backgroundColor: productosLabels.map((_, i) => colorsProductos[i % colorsProductos.length])
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true },
                x: { ticks: { maxRotation: 45, minRotation: 45, color: '#2C2C2C' } }
            },
            maintainAspectRatio: false,
            responsive: true,
        }
    });

    const chartSucursales = new Chart(ctxSucursales, {
        type: 'doughnut',
        data: {
            labels: sucursalesLabels,
            datasets: [{
                label: 'Ventas',
                data: sucursalesData,
                backgroundColor: sucursalesLabels.map((_, i) => colorsSucursales[i % colorsSucursales.length])
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { color: '#2C2C2C' } }
            }
        }
    });
});
</script>
@endsection
