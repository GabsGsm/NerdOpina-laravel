<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-gray-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — NerdOpina Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full flex items-center justify-center">

    <div class="w-full max-w-sm px-6">

        <div class="text-center mb-8">
            <span class="text-2xl font-bold text-white font-sora">NerdOpina</span>
            <p class="text-gray-400 text-sm mt-1">Painel Administrativo</p>
        </div>

        <div class="bg-gray-900 rounded-xl p-8 shadow-2xl border border-gray-800">

            <h1 class="text-lg font-semibold text-white mb-6">Entrar</h1>

            <form method="POST" action="{{ route('admin.login.submit') }}" x-data="{ loading: false }" @submit="loading = true">
                @csrf

                {{-- E-mail --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm text-gray-400 mb-1">E-mail</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Senha --}}
                <div class="mb-6">
                    <label for="password" class="block text-sm text-gray-400 mb-1">Senha</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    :disabled="loading"
                    class="w-full bg-indigo-600 hover:bg-indigo-500 disabled:opacity-60 text-white font-medium rounded-lg py-2.5 text-sm transition-colors"
                >
                    <span x-show="!loading">Entrar</span>
                    <span x-show="loading" x-cloak>Entrando…</span>
                </button>
            </form>

        </div>
    </div>

</body>
</html>
