@props(['disabled' => false])

{{-- <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm',
]) !!}> --}}
<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5',
]) !!}>
