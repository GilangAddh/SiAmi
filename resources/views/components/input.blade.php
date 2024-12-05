@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-gray-300 focus:border-[#60C0D0] focus:ring-[#60C0D0] rounded-md shadow-sm text-xs sm:text-sm',
]) !!}>
