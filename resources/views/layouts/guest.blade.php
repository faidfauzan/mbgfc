<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen w-full flex relative bg-slate-950 overflow-hidden">

        <!-- Area Gambar Kiri (Mempertahankan Proporsi Asli) -->
        <div class="hidden sm:block flex-1 relative">
            <img src="{{ asset('images/vizua  (1010 of 1056) (1).jpg') }}" alt="MBG FC Background"
                class="w-full h-full object-cover object-center" />
            <!-- Overlay Gelap Tipis -->
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <!-- Panel Kanan: Form Register (Lebih Lebar & Bisa Scroll) -->
        <div
            class="relative z-10 w-full max-w-lg min-h-screen max-h-screen overflow-y-auto bg-gray-500/25 dark:bg-gray-700/30 backdrop-blur-md p-8 flex flex-col justify-center border-l border-white/20 shadow-2xl">

            <!-- Logo / Header MBG FC (lebih ringkas) -->
            <div class="mb-4 text-center">
                <a href="/" class="inline-block">
                    <img src="{{ asset('images/mbg-logo-clean.png') }}" alt="MBG FC Logo"
                        class="h-20 mx-auto mb-2 drop-shadow-md">
                </a>
                <h2 class="text-xl font-extrabold text-slate-300 tracking-wider drop-shadow">Selamat datang 👋</h2>
                <p class="text-md font-medium text-slate-500 tracking-wider drop-shadow">Silahkan masuk menggunakan akun
                    anda</p>
            </div>

            <!-- Slot Isi Form Register -->
            {{ $slot }}

        </div>
    </div>
</body>

</html>