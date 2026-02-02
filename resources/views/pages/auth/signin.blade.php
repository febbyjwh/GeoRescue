@extends('layouts.fullscreen-layout')

@section('content')
<div class="flex min-h-screen bg-white">

    {{-- LEFT SIDE : LOGIN FORM --}}
    <div class="flex w-full items-center justify-center lg:w-1/2">
        <div class="w-full max-w-md p-8 sm:p-12">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Selamat Datang</h1>
                <p class="text-gray-500 mt-2">
                    Silakan masukkan username dan password untuk mengakses sistem.
                </p>
            </div>

            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 border border-green-300 text-green-700 px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('info'))
                <div class="mb-4 rounded-lg bg-blue-100 border border-blue-300 text-blue-700 px-4 py-3">
                    {{ session('info') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 border border-red-300 text-red-700 px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif
        
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all placeholder:text-gray-400"
                        placeholder="Masukan username" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:border-yellow-500 focus:ring-2 focus:ring-yellow-500/20 focus:outline-none transition-all placeholder:text-gray-400"
                        placeholder="Masukan password" required>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-yellow-500 focus:ring-yellow-500">
                        Ingat saya
                    </label>
                </div>

                <button type="submit"
                    class="w-full rounded-xl bg-yellow-500 py-3 font-bold text-white shadow-lg shadow-yellow-500/30 hover:bg-yellow-600 active:scale-[0.98] transition-all">
                    Masuk
                </button>
            </form>
        </div>
    </div>

    {{-- RIGHT SIDE : MAP PANEL WITH OVERLAY --}}
    <div class="relative hidden w-full items-center justify-center lg:flex lg:w-1/2 overflow-hidden">
        
        <div class="absolute inset-0 bg-center bg-no-repeat bg-cover scale-105 transition-transform duration-[10s] hover:scale-100"
             style="background-image: url('{{ asset('image/petaKabbdg.png') }}')">
        </div>

        <div class="absolute inset-0 bg-gradient-to-br from-slate-900/80 via-slate-900/40 to-transparent"></div>

        <div class="relative z-10 w-full px-20 text-left">
            
           <h2 class="text-6xl font-extrabold tracking-tighter text-white mb-2"
                style="text-shadow: 0 4px 18px rgba(0,0,0,0.7);">
                Geo<span class="text-yellow-500"
                    style="text-shadow: 0 4px 18px rgba(0,0,0,0.7);">Rescue</span>
            </h2>

            <div class="h-1 w-20 bg-yellow-500 mb-6 rounded-full"></div>

            <p class="text-xl leading-relaxed text-gray-200 max-w-md font-light">
                Sistem Informasi Geografis <br> 
                <span class="font-semibold text-white">Mitigasi Bencana Alam</span> <br> 
                Kabupaten Bandung
            </p>
        </div>
    </div>
</div>
@endsection
