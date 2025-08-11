@extends('layouts.app')

@section('content')
<h2 class="text-3xl font-semibold text-[#2C2C2C] mb-6">👥 Crear Nuevo Usuario</h2>

<section class="bg-[#F4F4F4] border border-[#B497BD] rounded-xl p-6 shadow-sm max-w-lg mx-auto">

  <form action="{{ route('config.usuarios.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div>
      <label for="name_user" class="block mb-2 font-semibold text-[#2C2C2C]">Nombre</label>
      <input type="text" name="name_user" id="name_user" value="{{ old('name_user') }}"
        class="w-full px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]" required>
      @error('name_user')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="correo_user" class="block mb-2 font-semibold text-[#2C2C2C]">Correo</label>
      <input type="email" name="correo_user" id="correo_user" value="{{ old('correo_user') }}"
        class="w-full px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]" required>
      @error('correo_user')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="rol_user" class="block mb-2 font-semibold text-[#2C2C2C]">Rol</label>
      <select name="rol_user" id="rol_user" required
        class="w-full px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]">
        <option value="">Selecciona un rol</option>
        <option value="Admin" {{ old('rol_user') == 'Admin' ? 'selected' : '' }}>Admin</option>
        <option value="Auditor" {{ old('rol_user') == 'Auditor' ? 'selected' : '' }}>Auditor</option>
        <option value="Empleado" {{ old('rol_user') == 'Empleado' ? 'selected' : '' }}>Empleado</option>
      </select>
      @error('rol_user')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="estado_user" class="block mb-2 font-semibold text-[#2C2C2C]">Estado</label>
      <select name="estado_user" id="estado_user"
        class="w-full px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]" required>
        <option value="1" {{ old('estado_user', '1') == '1' ? 'selected' : '' }}>Activo</option>
        <option value="0" {{ old('estado_user') == '0' ? 'selected' : '' }}>Inactivo</option>
      </select>
      @error('estado_user')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="imagen_user" class="block mb-2 font-semibold text-[#2C2C2C]">Imagen (opcional)</label>
      <input type="file" name="imagen_user" id="imagen_user" accept="image/*"
        class="w-full px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]">
      @error('imagen_user')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="password_user" class="block mb-2 font-semibold text-[#2C2C2C]">Contraseña</label>
      <input type="password" name="password_user" id="password_user"
        class="w-full px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]" required>
      @error('password_user')
      <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="password_user_confirmation" class="block mb-2 font-semibold text-[#2C2C2C]">Confirmar Contraseña</label>
      <input type="password" name="password_user_confirmation" id="password_user_confirmation"
        class="w-full px-4 py-2 rounded-lg border border-[#B497BD] bg-white text-[#2C2C2C]" required>
    </div>

    <div class="flex justify-between items-center">
      <a href="{{ route('config.usuarios.index') }}"
        class="px-6 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition text-[#2C2C2C]">Cancelar</a>
      <button type="submit" class="px-6 py-2 bg-[#B497BD] text-white rounded-lg hover:bg-[#9B82B1] transition">Crear
        Usuario</button>
    </div>
  </form>
</section>
@endsection
