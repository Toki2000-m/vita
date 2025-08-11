@extends('layouts.auth')

@section('title', 'Acceso - VITA')

@section('content')
<style>
  body {
    background-color: #F4F4F4;
  }
  .main-container {
    min-height: 100vh;
    display: flex;
    flex-wrap: wrap;
  }
  .left-panel {
    background-color: white;
    padding: 3rem;
    display: flex;
    flex-direction: column;
    justify-content: center;
    width: 100%;
    max-width: 480px;
    border: 1px solid #B497BD;
    border-radius: 1rem;
    box-shadow: 0 4px 12px rgba(180, 151, 189, 0.3);
    margin: 1rem auto;
  }
  .logo-container {
      display: flex;
  justify-content: center;
    text-align: center;
    margin-bottom: 2rem;
  }
  .logo-container img {
    max-height: 120px;
    max-width: 100%;
  height: auto;
  }
  .login-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2C2C2C;
    text-align: center;
    margin-bottom: 2.5rem;
    letter-spacing: 0.05em;
  }
  form label {
    color: #2C2C2C;
    font-weight: 600;
  }
  form input[type="email"],
  form input[type="password"] {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1.5px solid #B497BD;
    border-radius: 0.75rem;
    font-size: 1rem;
    color: #2C2C2C;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
  }
  form input[type="email"]:focus,
  form input[type="password"]:focus {
    outline: none;
    border-color: #9DBF9E;
    box-shadow: 0 0 8px rgba(157, 191, 158, 0.6);
  }
  .error-message {
    color: #E08B8B;
    font-size: 0.85rem;
    margin-top: 0.25rem;
  }
  .error-box {
    background-color: #E08B8B;
    color: white;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    font-weight: 600;
    text-align: center;
  }
  button[type="submit"] {
    width: 100%;
    background-color: #B497BD;
    color: white;
    font-weight: 700;
    padding: 0.9rem 0;
    border: none;
    border-radius: 0.75rem;
    font-size: 1.1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  button[type="submit"]:hover {
    background-color: #9B82B1;
  }
  .remember-me {
    display: flex;
    align-items: center;
    margin-top: 1rem;
    font-weight: 600;
    color: #2C2C2C;
  }
  .remember-me input {
    margin-right: 0.5rem;
  }
  .forgot-link {
    margin-top: 1rem;
    display: block;
    text-align: center;
    color: #7C5B8A;
    font-weight: 600;
    text-decoration: none;
  }
  .forgot-link:hover {
    text-decoration: underline;
  }
  .right-panel {
    flex-grow: 1;
    background-color: #9DBF9E;
    border-radius: 1rem;
    margin: 1rem;
    background-image: url('{{ asset("img/perfume-banner.jpg") }}');
    background-size: cover;
    background-position: center;
    min-height: 540px;
    box-shadow: 0 4px 12px rgba(157, 191, 158, 0.5);
  }

  @media (max-width: 768px) {
    .main-container {
      flex-direction: column;
      align-items: center;
    }
    .right-panel {
      display: none;
    }
    .left-panel {
      max-width: 100%;
      border-radius: 1rem;
      margin: 2rem 1rem;
    }
  }
</style>

<div class="main-container">
  <!-- Izquierda: Formulario + Logo + Título -->
  <div class="left-panel">
    <div class="logo-container">
      <img src="{{ asset('logo.jpg') }}" alt="Logo VITA" />
    </div>
    <h1 class="login-title">VITA</h1>

    @if($errors->any())
      <div class="error-box">
        <ul class="mb-0 list-none">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
      @csrf

      <label for="correo_user">Correo electrónico</label>
      <input
        id="correo_user"
        name="correo_user"
        type="email"
        required
        autofocus
        value="{{ old('correo_user') }}"
        placeholder="ejemplo@empresa.com"
        class="@error('correo_user') border-[#E08B8B] @enderror"
      />
      @error('correo_user')
        <p class="error-message">{{ $message }}</p>
      @enderror

      <label for="password_user" class="mt-6">Contraseña</label>
      <input
        id="password_user"
        name="password_user"
        type="password"
        required
        placeholder="••••••••"
        class="@error('password_user') border-[#E08B8B] @enderror"
      />
      @error('password_user')
        <p class="error-message">{{ $message }}</p>
      @enderror

      <div class="remember-me">
        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} />
        <label for="remember">Recordarme</label>
      </div>

      <button type="submit">Ingresar</button>
    </form>

    <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidaste tu contraseña?</a>
  </div>

  <!-- Derecha: Banner con imagen -->
  <div class="right-panel"></div>
</div>
@endsection
