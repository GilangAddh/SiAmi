<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="navbar bg-base-100 shadow-md sticky top-0">
        <div class="flex-1">
            <a class="btn btn-ghost text-xl">{{ config('app.name', 'Laravel') }}</a>
        </div>
        @if (Route::has('login'))
            <div class="flex-none">
                <ul class="menu menu-horizontal px-1">
                    @auth
                        <li><a href="{{ url('/dashboard') }}"
                                class="font-semibold text-gray-600 hover:text-gray-900">Dashboard</a>
                        </li>
                    @else
                        <li><a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900">Log
                                in</a></li>

                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}"
                                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900">Register</a>
                            </li>
                        @endif
                    @endauth
                </ul>
            </div>
        @endif
    </div>
</body>

</html>
