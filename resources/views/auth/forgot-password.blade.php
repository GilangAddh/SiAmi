<x-guest-layout>
    <x-authentication-card>
        <div class="flex justify-between items-center mb-6">
            <img src="{{ asset('images/logo-poltekkes.png') }}" class="w-[160px] sm:w-[215px] h-auto">
            <div class="flex space-x-2">
                <img src="{{ asset('images/berakhlak.png') }}" class="w-[88px] sm:w-[130px] h-auto">
                <img src="{{ asset('images/logo-blu.png') }}" class="w-[42px] sm:w-[55px] h-auto">
            </div>
        </div>

        <h1 class="font-semibold mb-1 text-sm sm:text-lg">Reset Password</h1>

        <div class="mb-4 text-xs sm:text-sm text-gray-600">
            {{ __('Masukkan email akun anda untuk mendapatkan link reset password.') }}
        </div>

        <x-validation-errors class="mb-4 text-sm" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-5">
                <x-button>
                    {{ __('Kirim Link Reset Password') }}
                </x-button>
            </div>

            <div class="block mt-2">
                <a class="text-xs sm:text-sm text-[#60C0D0] hover:underline rounded-md font-medium"
                    href="{{ route('login') }}">
                    {{ __('Kembali ke halaman masuk') }}
                </a>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
