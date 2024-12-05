@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-xs sm:text-sm text-gray-700']) }}>
    {{ $value ?? $slot }} <span class="text-red-500">*</span>
</label>
