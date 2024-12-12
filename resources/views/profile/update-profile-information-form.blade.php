<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Informasi Profil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Pastikan informasi profil/akun anda valid & terkini.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4 flex items-center" x-data="{ photoName: null, photoPreview: null }">
            <!-- Avatar section for existing image -->
            <div class="avatar" x-show="!photoPreview">
                <div class="w-28 rounded-full border border-1">
                    <img
                        src="{{ Auth::user()->profile_photo_path ? Storage::url(Auth::user()->profile_photo_path) : asset('images/avatar.png') }}" />
                </div>
            </div>

            <!-- Avatar section for new image preview -->
            <div class="avatar" x-show="photoPreview">
                <div class="w-28 rounded-full border border-1">
                    <img x-bind:src="photoPreview" />
                </div>
            </div>

            <!-- File input for uploading a new photo -->
            <input type="file" class="file-input file-input-ghost file-input-sm w-full max-w-xs" id="photo"
                wire:model.live="photo" accept="image/*" x-ref="photo"
                x-on:change="
                    photoName = $refs.photo.files[0].name;
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        photoPreview = e.target.result;
                    };
                    reader.readAsDataURL($refs.photo.files[0]);
                " />

            <!-- Error message for file input -->
            <x-input-error for="photo" class="mt-2" />
        </div>

        <!-- profile_name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="profile_name" value="{{ __('Nama Profil') }}" />
            <x-input id="profile_name" type="text" class="mt-1 block w-full" wire:model="state.profile_name" required
                autocomplete="profile_name" />
            <x-input-error for="profile_name" class="mt-2" />
        </div>

        <!-- NIP -->
        @if (Auth::user()->role == 'auditor')
            <div class="col-span-6 sm:col-span-4">
                <x-label for="no_identity" value="{{ __('NIP') }}" />
                <x-input id="no_identity" type="text" class="mt-1 block w-full" wire:model="state.no_identity"
                    required autocomplete="no_identity" />
                <x-input-error for="no_identity" class="mt-2" />
            </div>
        @endif

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required
                autocomplete="email" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) &&
                    !$this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Username Akun') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required
                autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Tersimpan.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Simpan') }}
        </x-button>
    </x-slot>
</x-form-section>
