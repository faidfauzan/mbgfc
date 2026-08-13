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
    <!-- UBAH BARIS 17: Hapus overflow-x-hidden -->
<body class="font-sans antialiased bg-gray-50 text-gray-800 min-h-screen">
    @include('layouts.navigation')

    <!-- Content Wrapper -->
    <div class="w-full lg:ps-[260px] min-h-screen flex flex-col">
        @isset($header)
            <header class="bg-white shadow-sm border-b border-gray-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- UBAH BARIS 32: Tambahkan overflow-x-auto pada main -->
        <main class="w-full flex-1 overflow-x-auto">
            {{ $slot }}
        </main>
    </div>
</body>
</html>