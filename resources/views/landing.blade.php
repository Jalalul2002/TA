<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laboratorium Fakultas Sains dan Teknologi</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@200,300,400,500,600,700,1&display=swap"
        rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $programs = [
            ['code' => '701', 'name' => 'Matematika', 'link' => '#'],
            ['code' => '702', 'name' => 'Biologi', 'link' => '#'],
            ['code' => '703', 'name' => 'Fisika', 'link' => '#'],
            ['code' => '704', 'name' => 'Kimia', 'link' => '#'],
            ['code' => '705', 'name' => 'Teknik Informatika', 'link' => '#'],
            ['code' => '706', 'name' => 'Agroteknologi', 'link' => '#'],
            ['code' => '707', 'name' => 'Teknik Elektro', 'link' => '#'],
        ];
    @endphp
</head>

<body class="font-sans antialiased h-full text-base">
    <header x-data="{ isOpen: false }" class="absolute inset-x-0 top-0 z-50">
        <nav class="mx-auto flex max-w-6xl items-center justify-between px-3 py-6 lg:py-8" aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="/" class="-m-1.5 p-1.5">
                    <div class="flex items-center">
                        <img src="favicon.png" alt="Logo UIN" class="w-auto h-9 mr-4">
                        <div>
                            <p class="font-semibold text-base sm:text-lg leading-none">Laboratorium Terpadu</p>
                            <p class="text-xs sm:text-sm">Fakultas Sains dan Teknologi</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="flex gap-x-4 items-center lg:hidden">
                @if (Route::has('login'))
                    @auth
                        <a href="/dashboard"
                            class="text-xs/6 font-semibold text-white py-2 px-4 bg-uinBlue rounded-full">SIMA
                            Lab <span aria-hidden="true">&rarr;</span></a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-xs/6 font-semibold text-white py-2 px-4 bg-uinBlue rounded-full">SIMA
                            Lab <span aria-hidden="true">&rarr;</span></a>

                    @endauth
                @endif
                <button type="button" @click="isOpen = !isOpen"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700">
                    <span class="sr-only">Open main menu</span>
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        aria-hidden="true" data-slot="icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:gap-x-12">
                <a href="/" class="text-sm/6 font-semibold text-gray-900 hover:text-uinBlue">Beranda</a>
                <div class="relative">
                    <button type="button" @click="isOpen = !isOpen"
                        class="flex items-center gap-x-1 text-sm/6 font-semibold text-gray-900 hover:text-uinBlue"
                        aria-expanded="false">
                        Program Studi
                        <svg class="size-5 flex-none text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true" data-slot="icon">
                            <path fill-rule="evenodd"
                                d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute -left-8 top-full z-10 mt-3 w-screen max-w-xs overflow-hidden rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5">
                        <div class="p-4">
                            @foreach ($programs as $program)
                                <div
                                    class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm/6 hover:bg-gray-50">
                                    <div
                                        class="flex font-bold text-gray-900 size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white group-hover:text-uinNavy">
                                        <p>{{ $program['code'] }}</p>
                                    </div>
                                    <a href="{{ $program['link'] }}" class="block font-semibold text-gray-900">
                                        {{ $program['name'] }}
                                        <span class="absolute inset-0"></span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a href="#" class="text-sm/6 font-semibold text-gray-900 hover:text-uinBlue">Lokasi</a>
                <a href="#" class="text-sm/6 font-semibold text-gray-900 hover:text-uinBlue">Web Fakultas</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                @if (Route::has('login'))
                    @auth
                        <a href="/dashboard"
                            class="text-sm/6 font-semibold text-white py-3 px-8 bg-uinBlue hover:bg-uinYellow rounded-full">SIMA
                            Lab <span aria-hidden="true">&rarr;</span></a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm/6 font-semibold text-white py-3 px-8 bg-uinBlue hover:bg-uinYellow rounded-full">SIMA
                            Lab <span aria-hidden="true">&rarr;</span></a>
                    @endauth
                @endif

            </div>
        </nav>
        <!-- Mobile menu, show/hide based on menu open state. -->
        <div class="lg:hidden" role="dialog" aria-modal="true">
            <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-1" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-1"
                class="fixed inset-0 bg-black bg-opacity-50 z-10"></div>
            <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
                class="fixed inset-y-0 right-0 z-10 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-gray-900/10">
                <div class="flex items-center justify-between">
                    <a href="#" class="-m-1.5 p-1.5">
                        <div class="flex items-center">
                            <img src="favicon.png" alt="Logo UIN" class="w-auto h-8 mr-4">
                            <div>
                                <p class="font-semibold text-lg leading-none">Laboratorium Terpadu</p>
                                <p class="text-sm">Fakultas Sains dan Teknologi</p>
                            </div>
                        </div>
                    </a>
                    <button type="button" @click="isOpen = !isOpen" class="-m-2.5 rounded-md p-2.5 text-gray-700">
                        <span class="sr-only">Close menu</span>
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-6 flow-root">
                    <div class="-my-6 divide-y divide-gray-500/10">
                        <div class="space-y-2 py-6">
                            <a href="/"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Beranda</a>
                            <div x-data="{ open: false }" class="-mx-3">
                                <button type="button" @click="open = !open"
                                    class="flex w-full items-center justify-between rounded-lg py-2 pl-3 pr-3.5 text-base/7 font-semibold text-gray-900 hover:bg-gray-50"
                                    aria-controls="disclosure-1" aria-expanded="false">
                                    Program Studi
                                    <svg :class="{ 'rotate-180': open }" class="size-5 flex-none" viewBox="0 0 20 20"
                                        fill="currentColor" aria-hidden="true" data-slot="icon">
                                        <path fill-rule="evenodd"
                                            d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open" x-collapse class="mt-2 space-y-2" id="disclosure-1">
                                    @foreach ($programs as $program)
                                        <a href="{{ $program['link'] }}"
                                            class="block rounded-lg py-2 pl-6 pr-3 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{$program['code'] . " " . $program['name']}}</a>
                                    @endforeach
                                </div>
                            </div>
                            <a href="#"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Lokasi</a>
                            <a href="#"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Web
                                Fakultas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-80"
                aria-hidden="true">
                <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-uinBlue to-uinGreen opacity-30 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>
            <div class="mx-auto max-w-6xl flex gap-x-24 items-center sm:flex-row flex-col">
                <div class="text-left py-32 sm:py-32 lg:py-56">
                    <h1 class="text-balance text-5xl lg:text-7xl font-semibold tracking-tight text-gray-900 font-[clash-display]">
                        Laboratorium <br> Terpadu</h1>
                    <p class="mt-8 text-pretty text-lg font-base text-gray-500">Fakultas Sains dan
                        Teknologi UIN Sunan Gunung Djati Bandung</p>
                    <div class="mt-10 flex items-center gap-x-6">
                        @if (Route::has('login'))
                            @auth
                                <a href="/dashboard"
                                    class="rounded-md bg-uinYellow px-6 py-2.5 text-lg font-light text-white shadow-sm hover:bg-uinBlue focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Masuk
                                    SIMA Lab</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="rounded-md bg-uinYellow px-6 py-2.5 text-lg font-light text-white shadow-sm hover:bg-uinBlue focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Masuk
                                    SIMA Lab</a>
                            @endauth
                        @endif
                        <a href="#" class="text-sm/6 font-semibold text-gray-900 hover:text-uinBlue">Lihat lebih
                            banyak <span aria-hidden="true">→</span></a>
                    </div>
                </div>
                <div class="h-full">
                    <img class="h-full w-ful rounded-3xl object-cover" src="Lab2.png"
                        alt="Laboratorium Foto by AI Generated">
                </div>
            </div>
            <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]"
                aria-hidden="true">
                <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-uinGreen to-uinBlue opacity-30 sm:left-[calc(50%+36rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>
        </div>
    </section>

    <section class="">

    </section>

</body>

</html>
