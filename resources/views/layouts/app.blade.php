<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" x-data="{ openModal: false, openSidebar: JSON.parse(localStorage.getItem('openSidebar')) ?? true }" x-init="$watch('openSidebar', value => localStorage.setItem('openSidebar', JSON.stringify(value)))" x-cloak>
    @include('layouts.navigation')

    @include('layouts.sidebar')

    <div :class="openSidebar ? 'sm:ml-64' : 'sm:ml-20'" class="min-h-screen flex flex-col bg-gray-100 transition-all duration-300">
        <div class="flex-grow">
            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow pt-20">
                    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        @include('layouts.footer')
    </div>

</body>

</html>
