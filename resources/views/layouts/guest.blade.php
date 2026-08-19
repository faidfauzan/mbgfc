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
            <img src="{{ asset('images/bgmbgfc.jpg') }}" 
                 alt="MBG FC Background" 
                 class="w-full h-full object-cover object-center" />
            <!-- Overlay Gelap Tipis -->
            <div class="absolute inset-0 bg-black/20"></div>
        </div>

        <!-- Panel Kanan: Form Login/Register (Abu-Abu Blur Glassmorphism) -->
        <div class="relative z-10 w-full max-w-md min-h-screen bg-gray-500/25 dark:bg-gray-700/30 backdrop-blur-md p-8 flex flex-col justify-center border-l border-white/20 shadow-2xl">
            
            <!-- Logo / Header MBG FC -->
            <div class="mb-6 text-center">
                <a href="/" class="inline-block">
                    <img src="{{ asset('images/mbglogo3.png') }}" alt="MBG FC Logo" class="h-16 mx-auto mb-2 drop-shadow-md">
                </a>
                <h2 class="text-2xl font-extrabold text-white tracking-wider drop-shadow">MBG FC</h2>
            </div>

            <!-- Slot Isi Form Login / Register -->
            {{ $slot }}
            
        </div>
    </div></body>

</html>