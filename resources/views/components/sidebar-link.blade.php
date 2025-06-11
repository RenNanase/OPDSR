@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-4 py-2 text-black bg-opd-secondary rounded-md transition duration-150 ease-in-out'
            : 'flex items-center px-4 py-2 text-gray-600 hover:bg-opd-secondary hover:text-black rounded-md transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
