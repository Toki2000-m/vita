@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-12 p-8 bg-[#F4F4F4] rounded-lg shadow-lg">
    <h1 class="text-4xl font-semibold mb-8 text-[#2C2C2C]">Configuración</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <a href="{{ route('config.almacenes.index') }}" class="block p-6 bg-[#B497BD] text-white rounded-lg shadow hover:bg-[#9C81AA] transition">
            <h2 class="text-2xl font-semibold mb-2">Almacenes</h2>
            <p>Gestiona almacenes, su información y códigos.</p>
        </a>

        <a href="{{ route('config.proveedores.index') }}" class="block p-6 bg-[#B497BD] text-white rounded-lg shadow hover:bg-[#9C81AA] transition">
            <h2 class="text-2xl font-semibold mb-2">Proveedores</h2>
            <p>Administra proveedores, contactos y datos fiscales.</p>
        </a>

        <a href="{{ route('config.perfumes.index') }}" class="block p-6 bg-[#B497BD] text-white rounded-lg shadow hover:bg-[#9C81AA] transition">
            <h2 class="text-2xl font-semibold mb-2">Perfumes</h2>
            <p>Catálogo de productos, stock y precios.</p>
        </a>
    
    </div>
</div>
@endsection
