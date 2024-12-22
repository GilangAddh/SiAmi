<x-guest-layout>
    <x-authentication-card>
        <div class="flex justify-between items-center mb-6">
            <img src="{{ asset('images/logo-poltekkes.png') }}" class="w-[160px] sm:w-[215px] h-auto">
            <div class="flex space-x-2">
                <img src="{{ asset('images/berakhlak.png') }}" class="w-[88px] sm:w-[130px] h-auto">
                <img src="{{ asset('images/logo-blu.png') }}" class="w-[42px] sm:w-[55px] h-auto">
            </div>
        </div>

        <h1 class="font-semibold mb-1 text-sm sm:text-lg">Selamat Datang di SIAMI</h1>

        <div class="mb-4 text-xs sm:text-sm text-gray-600">
            {{ __('Masukkan email/username dan password untuk masuk.') }}
        </div>

        <x-validation-errors class="mb-4 text-sm" />

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-label for="login" value="{{ __('Email/Username') }}" />
                <x-input id="login" class="block mt-1 w-full" type="text" name="login" :value="old('login')"
                    required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />
            </div>

            {{-- <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-xs sm:text-sm text-gray-600">{{ __('Ingat Saya') }}</span>
                </label>
            </div> --}}

            <div class="g-recaptcha mt-5" data-sitekey="{{ config('captcha.sitekey') }}"></div>

            <div class="flex items-center justify-center mt-0 md:mt-3">
                <x-button class="w-full" type="submit">
                    {{ __('Masuk') }}
                </x-button>
            </div>

            <div class="flex items-center justify-center mt-3">
                @if (Route::has('password.request'))
                    <a class="text-xs sm:text-sm text-[#60C0D0] hover:underline rounded-md font-medium"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>

            <div class="flex justify-end mt-5">
                <a href="{{ asset('pdf/panduan.pdf') }}" target="_blank">
                    <x-button type="button" class="btn-sm">
                        {{ __('Unduh Panduan') }}
                    </x-button>
                </a>
            </div>
        </form>

        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    </x-authentication-card>
</x-guest-layout>
