@extends('layouts.app')

@section('title', 'Transacciones')

@section('content')
<div class="bg-white rounded-2xl shadow-md p-6">
    <h2 class="text-2xl font-bold text-[#2C2C2C] mb-6">Transacciones</h2>

    <!-- Tabs -->
    <div class="flex gap-4 mb-6 border-b border-gray-300">
        <button onclick="setTab('entradas')" class="tab-btn text-sm py-2 px-4 border-b-2 border-transparent hover:border-[#B497BD]">Entradas</button>
        <button onclick="setTab('salidas')" class="tab-btn text-sm py-2 px-4 border-b-2 border-transparent hover:border-[#B497BD]">Salidas</button>
        <button onclick="setTab('traspasos')" class="tab-btn text-sm py-2 px-4 border-b-2 border-transparent hover:border-[#B497BD]">Traspasos</button>
    </div>

    <!-- Contenido dinámico -->
    <div id="tab-entradas" class="tab-content hidden">
        @include('transacciones.partials.entradas', ['proveedores' => $proveedores])
    </div>
    <div id="tab-salidas" class="tab-content hidden">
        @include('transacciones.partials.salidas')
    </div>
    <div id="tab-traspasos" class="tab-content hidden">
        @include('transacciones.partials.traspasos')
    </div>
</div>

<script>
    function setTab(tab) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('tab-' + tab).classList.remove('hidden');

        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('border-[#B497BD]', 'text-[#B497BD]', 'font-semibold'));
        event.target.classList.add('border-[#B497BD]', 'text-[#B497BD]', 'font-semibold');
    }

    // Mostrar Entradas por defecto
    document.addEventListener('DOMContentLoaded', () => setTab('entradas'));
</script>
@endsection
