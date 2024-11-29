<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SiAmi')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased">
    <x-banner />

    <div class="min-h-screen bg-white">
        @livewire('navbar')

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')

    @if (session('error'))
        <div id="error-toast" class="toast">
            <div class="alert alert-error">
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @livewireScripts
    <script src="https://kit.fontawesome.com/3cfd8eaa87.js" crossorigin="anonymous"></script>;
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const errorToast = document.getElementById('error-toast');
            if (errorToast) {
                setTimeout(() => {
                    errorToast.style.display = 'none';
                }, 3000);
            }
        });

        Livewire.on('setTitle', (data) => {
            // Update the document title dynamically when the event is received
            document.title = data.title;
        });
    </script>
</body>

</html>
