<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="max-h-screen font-sans text-gray-900 antialiased bg-sky-50">
    <div class="max-w-6xl mx-auto md:py-8 md:px-6 xl:px-0">
        <div
            class="header bg-uinBlue p-3 md:p-5 md:rounded-3xl text-white flex justify-between items-center animate__animated animate__fadeInUp animate__slow">
            <div class="flex items-center gap-x-8">
                <a href="/">
                    <i class="fi fi-sr-angle-circle-left text-3xl hover:text-uinOrange leading-none transition duration-300"></i>
                </a>
                <a href="/">
                    <div class="flex items-center gap-x-2">
                        <img src="Logo-UIN-Putih.png" alt="Logo UIN" class="h-10">
                        <div>
                            <h1 class="font-semibold text-lg leading-none">Laboratorium</h1>
                            <h2 class="text-[11px]">Fakultas Sains dan Teknologi</h2>
                        </div>
                    </div>
                </a>
            </div>
            <div class="pr-2 flex items-center gap-2">
                <div>
                    <h2 class="cursor-default"> - - | - - </h2>
                </div>
                <svg class="size-6 fill-white" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                    viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="512"
                    height="512">
                    <g>
                        <path
                            d="M85.333,0h64c47.128,0,85.333,38.205,85.333,85.333v64c0,47.128-38.205,85.333-85.333,85.333h-64   C38.205,234.667,0,196.462,0,149.333v-64C0,38.205,38.205,0,85.333,0z" />
                        <path
                            d="M362.667,0h64C473.795,0,512,38.205,512,85.333v64c0,47.128-38.205,85.333-85.333,85.333h-64   c-47.128,0-85.333-38.205-85.333-85.333v-64C277.333,38.205,315.538,0,362.667,0z" />
                        <path
                            d="M85.333,277.333h64c47.128,0,85.333,38.205,85.333,85.333v64c0,47.128-38.205,85.333-85.333,85.333h-64   C38.205,512,0,473.795,0,426.667v-64C0,315.538,38.205,277.333,85.333,277.333z" />
                        <path
                            d="M362.667,277.333h64c47.128,0,85.333,38.205,85.333,85.333v64C512,473.795,473.795,512,426.667,512h-64   c-47.128,0-85.333-38.205-85.333-85.333v-64C277.333,315.538,315.538,277.333,362.667,277.333z" />
                    </g>
                </svg>
            </div>
        </div>
        <div
            class="w-full mt-28 mx-auto sm:max-w-md px-8 py-12 bg-white shadow-md overflow-hidden sm:rounded-xl animate__animated animate__zoomIn">
            <div class="flex items-center justify-center gap-x-4 pb-6">
                <x-application-logo class="h-10 fill-current" />
                <div>
                    <h2 class="font-semibold text-base leading-none text-uinOrange">Sistem Manajemen Aset</h2>
                    <h1 class="text-lg leading-none text-uinBlue">Laboratorium Saintek</h1>
                </div>
            </div>
            <div>
                {{ $slot }}
            </div>
        </div>
    </div>
    <footer class="max-w-6xl pt-36 mx-auto text-center animate__animated animate__zoomIn">
        <span class="text-sm text-gray-500 sm:text-center">© 2024 <a href="/" class="hover:underline">Laboratorium Fakultas Saintek</a>. UIN Sunan Gunung Djati Bandung
        </span>
    </footer>
</body>

</html>
