<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="w-full max-w-md bg-white rounded-xl shadow p-6">
        <h2 class="text-2xl font-bold mb-4 text-center">Crear Cuenta</h2>

        <form method="POST" action="{{ url('/register') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Nombre</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Correo</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Contraseña</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Confirmar Contraseña</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
            </div>

            <button class="w-full bg-green-500 text-white py-2 rounded hover:bg-green-600">Registrar</button>
        </form>

        <p class="mt-4 text-center text-gray-600">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Inicia sesión</a>
        </p>
    </div>
</body>
</html>
