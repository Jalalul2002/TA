@props(['active'])

@php
$classes = ($active ?? false)
            ? 'flex items-center px-3 py-4 text-gray-900 rounded-lg bg-uinYellow text-white group'
            : 'flex items-center px-3 py-4 text-gray-900 rounded-lg hover:bg-uinYellow hover:font-medium hover:text-white group transition duration-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
