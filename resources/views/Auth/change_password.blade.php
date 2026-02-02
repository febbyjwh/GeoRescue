<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Change Password</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center px-4">

    <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-lg p-8">

        <!-- Branding -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">GeoRescue</h1>
            <p class="text-sm text-gray-500 mt-1">Ubah password Anda</p>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('info'))
            <div class="mb-5 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700">
                {{ session('info') }}
            </div>
        @endif

        @if (session()->has('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('password.change.update') }}" novalidate>
            @csrf

            <x-form.form-elements.default-inputs
                label="Password Baru"
                name="password"
                id="password"
                type="password"
                placeholder="Minimum 6 karakter"
                required
            />

            <x-form.form-elements.default-inputs
                label="Konfirmasi Password"
                name="password_confirmation"
                id="password_confirmation"
                type="password"
                placeholder="Ulangi password baru"
                required
                class="mt-4"
            />

            <!-- Submit -->
            <div class="mt-6">
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center rounded-lg
                           bg-amber-300 px-5 py-2.5 text-sm font-medium
                           text-black hover:bg-amber-500 transition"
                >
                    Simpan Password
                </button>
            </div>
        </form>

        <!-- Logout -->
        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs text-gray-400 hover:text-gray-600 transition">
                    Logout
                </button>
            </form>
        </div>

    </div>

    @vite('resources/js/app.js')
</body>
</html>