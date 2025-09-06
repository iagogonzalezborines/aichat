<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white rounded-xl shadow p-6">
        <h2 class="text-2xl font-bold mb-4 text-center">Iniciar Sesión</h2>

        @if($errors->any())
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Correo</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Contraseña</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <button class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Entrar</button>
        </form>

        <p class="mt-4 text-center text-gray-600">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Registrarse</a>
        </p>
    </div>
</body>
</html>
