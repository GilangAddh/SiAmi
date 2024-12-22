<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Page Not Found</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body>
    <div class="h-screen flex flex-col justify-center items-center bg-[#8BDCEA]">
        <div
            class="relative z-10 w-full max-w-xs sm:max-w-lg px-8 py-6 sm:px-10 sm:py-8 bg-white shadow-lg overflow-hidden rounded-lg">

            <div class="flex justify-between items-center mb-6">
                <img src="{{ asset('images/logo-poltekkes.png') }}" class="w-[175px] sm:w-[215px] h-auto">
                <img src="{{ asset('images/logo-blu.png') }}" class="w-[45px] sm:w-[55px] h-auto">
            </div>

            <div class="flex flex-col text-center">
                <h1 class="font-semibold mb-1 text-lg sm:text-2xl">404 | Page Not Found</h1>
                <p class="text-sm mb-5">Halaman yang anda cari tidak ditemukan</p>
                <a wire:navigate.hover href="{{ url('/') }}">
                    <button type ='button'
                        class='w-full inline-flex items-center justify-center py-2 bg-[#60C0D0] border border-transparent rounded-md font-medium text-xs sm:text-sm text-white capitalize tracking-widest'>
                        Kembali
                    </button>
                </a>
            </div>
        </div>
    </div>

    @livewireScripts
</body>

</html>
