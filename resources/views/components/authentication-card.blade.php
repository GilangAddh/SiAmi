<div class="h-screen flex flex-col justify-center items-center bg-[#8BDCEA]">
    {{-- <div class="absolute inset-0 bg-white w-1/2 right-0"></div> --}}
    {{-- <div class="relative z-10">
        {{ $logo }}
    </div> --}}

    <div class="relative z-10 w-full max-w-lg xl:max-w-xl p-6 bg-white shadow-lg overflow-hidden rounded-lg">
        {{ $slot }}
    </div>
</div>
