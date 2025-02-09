<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laboratorium Fakultas Sains dan Teknologi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://api.fontshare.com/v2/css?f[]=clash-display@200,300,400,500,600,700,1&display=swap"
        rel="stylesheet">
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $programs = [
            ['code' => '701', 'name' => 'Matematika', 'link' => 'https://math.uinsgd.ac.id/'],
            ['code' => '702', 'name' => 'Biologi', 'link' => 'http://bio.uinsgd.ac.id/'],
            ['code' => '703', 'name' => 'Fisika', 'link' => 'https://fi.uinsgd.ac.id/'],
            ['code' => '704', 'name' => 'Kimia', 'link' => 'https://chem.uinsgd.ac.id/'],
            ['code' => '705', 'name' => 'Teknik Informatika', 'link' => 'https://if.uinsgd.ac.id/'],
            ['code' => '706', 'name' => 'Agroteknologi', 'link' => 'https://agrotek.uinsgd.ac.id/'],
            ['code' => '707', 'name' => 'Teknik Elektro', 'link' => 'https://ee.uinsgd.ac.id/'],
        ];
    @endphp
</head>

<body x-data x-init="const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate__animated', 'animate__zoomIn');
            observer.unobserve(entry.target); // Stop observing once animated
        }
    });
});

document.querySelectorAll('.animate-on-scroll').forEach(el => {
    observer.observe(el);
});" class="font-sans antialiased h-full text-base">
    <header x-data="{ isOpen: false, hasScrolled: false }" @scroll.window="hasScrolled = (window.scrollY > 0)"
        :class="hasScrolled ? 'bg-white shadow-lg' : ''" class="fixed inset-x-0 top-0 z-50 transition-all duration-300">
        <nav :class="hasScrolled ? 'py-6' : 'py-6 lg:py-8'"
            class="mx-auto flex max-w-6xl items-center justify-between px-3 lg:px-0 transition-all duration-300"
            aria-label="Global">
            <div class="flex lg:flex-1">
                <a href="/" class="-m-1.5 p-1.5">
                    <div class="flex items-center">
                        <div class="mr-2">
                            <img src="{{ asset('Logo-uinsgd_official.png') }}" alt="Logo UIN" class="w-auto h-10">
                        </div>
                        <div>
                            <p class="font-semibold text-base sm:text-lg leading-none">Laboratorium Terpadu</p>
                            <p class="text-xs sm:text-sm leading-none">Fakultas Sains dan Teknologi</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="flex gap-x-4 items-center lg:hidden">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ Auth::user()->usertype === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                            class="text-xs font-semibold text-white py-2 px-4 bg-uinBlue rounded-full">
                            SIMA Lab <span aria-hidden="true">&rarr;</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-xs font-semibold text-white py-2 px-4 bg-uinBlue rounded-full">
                            SIMA Lab <span aria-hidden="true">&rarr;</span>
                        </a>
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
                                    class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm hover:bg-uinBlue">
                                    <div
                                        class="flex font-bold text-gray-900 size-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-uinOrange group-hover:text-white">
                                        <p>{{ $program['code'] }}</p>
                                    </div>
                                    <a href="{{ $program['link'] }}" class="block font-semibold text-gray-900 group-hover:text-white">
                                        {{ $program['name'] }}
                                        <span class="absolute inset-0"></span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <a href="#" class="text-sm font-semibold text-gray-900 hover:text-uinBlue">Denah Lab</a>
                <a href="https://fst.uinsgd.ac.id/" class="text-sm font-semibold text-gray-900 hover:text-uinBlue">Web
                    Fakultas</a>
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ Auth::user()->usertype === 'admin' ? route('admin.dashboard') : route('dashboard') }}"
                            class="text-sm font-semibold text-white py-3 px-8 bg-uinBlue hover:bg-uinYellow rounded-full">SIMA
                            Lab <span aria-hidden="true">&rarr;</span></a>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-sm font-semibold text-white py-3 px-8 bg-uinBlue hover:bg-uinYellow rounded-full">SIMA
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
                x-transition:enter-start="opacity-0 translate-x-full"
                x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 translate-x-full"
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
                    <div class="-my-6 divide-y divide-white/10">
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
                                            class="block rounded-lg py-2 pl-6 pr-3 text-sm/7 font-semibold text-gray-900 hover:bg-gray-50">{{ $program['code'] . ' ' . $program['name'] }}</a>
                                    @endforeach
                                </div>
                            </div>
                            <a href="#"
                                class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-gray-900 hover:bg-gray-50">Denah Lab</a>
                            <a href="https://fst.uinsgd.ac.id/"
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
                <div class="text-left py-24 sm:py-32 lg:py-56 animate__animated animate__zoomIn">
                    <h1
                        class="text-balance text-5xl lg:text-7xl font-semibold tracking-tight text-gray-900 font-[clash-display]">
                        Laboratorium <br> Terpadu</h1>
                    <p class="mt-8 text-pretty text-lg font-base text-gray-900">Fakultas Sains dan
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
                <div class="h-full animate__animated animate__zoomIn animate__slow">
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

    <section class="layanan">
        <div class="relative">
            <div
                class="pt-10 md:pt-0 md:absolute inset-x-0 -top-16 z-20 transform-gpu md:flex justify-center items-center px-6">
                <div
                    class="bg-white max-w-6xl py-6 px-6 lg:px-8 rounded-3xl shadow-md font-semibold text-base lg:text-xl sm:flex sm:gap-x-14 animate__animated animate__zoomIn animate__slow">
                    <div class="flex mb-2 sm:mb-0 gap-x-2 items-center">
                        <img src="icon/booking.png" alt="icon booking" class="w-10 md:w-14 lg:w-16">
                        <h2 class="leading-none">Penggunaan Ruangan Lab</h2>
                    </div>
                    <div class="flex mb-2 sm:mb-0 gap-x-2 items-center">
                        <img src="icon/lab-tool.png" alt="icon booking" class="w-10 md:w-14 lg:w-16">
                        <h2 class="leading-none">Peminjaman Alat Lab</h2>
                    </div>
                    <div class="flex mb-2 sm:mb-0 gap-x-4 items-center">
                        <img src="icon/lab.png" alt="icon booking" class="w-10 md:w-14 lg:w-16">
                        <h2 class="leading-none">Peminjaman Bahan Lab</h2>
                    </div>
                </div>
            </div>
            <div class="bg-white py-24 sm:py-32">
                <div class="mx-auto max-w-6xl px-6 lg:px-0">
                    <div class="mx-auto lg:text-center animate-on-scroll">
                        <h2
                            class="mt-2 text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl lg:text-balance font-[clash-display]">
                            Visi</h2>
                        <p class="mt-6 text-lg/8 text-gray-600">"Menuju Fakultas yang Unggul dan Kompetitif dalam
                            Bidang
                            Sains dan Teknologi berbasis Wahyu Memandu Ilmu (WMI) dalam Bingkai Akhlak Karimah di Asia
                            Tenggara tahun 2025."</p>
                    </div>
                    <div class="bg-white pt-24 sm:pt-32">
                        <div class="mx-auto max-w-6xl">
                            <div
                                class="mx-auto grid grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2">
                                <img src="Lab3.png" alt="Product screenshot"
                                    class="w-80 max-w-xl md:w-[48rem] animate-on-scroll" width="2432"
                                    height="1442">
                                <div class="lg:pt-4 lg:pr-8">
                                    <div class="lg:max-w-lg animate-on-scroll">
                                        <p
                                            class="mt-2 text-4xl font-semibold tracking-tight text-pretty text-gray-900 sm:text-5xl font-[clash-display]">
                                            Misi</p>
                                        <dl class="mt-10 max-w-xl space-y-8 text-base/7 text-gray-600 lg:max-w-none">
                                            <div class="relative pl-9">
                                                <dt class="inline font-semibold text-gray-900">
                                                    <i
                                                        class="fi fi-sr-hand-back-point-right text-uinOrange absolute top-1 left-1 text-xl"></i>
                                                    1.
                                                </dt>
                                                <dd class="inline">Meningkatkan Kualitas Penyelenggaraan Tridharma
                                                    Perguruan Tinggi
                                                    Berbasis WMI dalam Bidang Sains dan Teknologi secara Terintegrasi
                                                    dan Inovatif;</dd>
                                            </div>
                                            <div class="relative pl-9">
                                                <dt class="inline font-semibold text-gray-900">
                                                    <i
                                                        class="fi fi-sr-hand-back-point-right text-uinOrange absolute top-1 left-1 text-xl"></i>
                                                    2.
                                                </dt>
                                                <dd class="inline">Meningkatkan Kompetensi dan Profesionalisme SDM
                                                    dalam Bingkai Akhlak Karimah;</dd>
                                            </div>
                                            <div class="relative pl-9">
                                                <dt class="inline font-semibold text-gray-900">
                                                    <i
                                                        class="fi fi-sr-hand-back-point-right text-uinOrange absolute top-1 left-1 text-xl"></i>
                                                    3.
                                                </dt>
                                                <dd class="inline">Menyelenggarakan Tata Kelola Pendidikan yang
                                                    Profesional dan Akuntabel;</dd>
                                            </div>
                                            <div class="relative pl-9">
                                                <dt class="inline font-semibold text-gray-900">
                                                    <i
                                                        class="fi fi-sr-hand-back-point-right text-uinOrange absolute top-1 left-1 text-xl"></i>
                                                    4.
                                                </dt>
                                                <dd class="inline">Melaksanakan Kerjasama Strategis dengan Berbagai
                                                    Pihak, baik dalam Skala Nasional maupun Regional.</dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 py-24 sm:py-32">
                <div class="mx-auto max-w-2xl px-6 lg:max-w-6xl lg:px-0">
                    <p
                        class="animate-on-scroll mx-auto mt-2 max-w-lg text-center text-4xl font-semibold tracking-tight text-balance text-gray-950 sm:text-5xl font-[clash-display]">
                        Tujuan</p>
                    <div class="mt-10 grid gap-4 sm:mt-16 lg:grid-cols-3 lg:grid-rows-2">
                        <div class="relative lg:row-span-2 animate-on-scroll">
                            <div class="absolute inset-px rounded-lg bg-white lg:rounded-l-[2rem]"></div>
                            <div
                                class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] lg:rounded-l-[calc(2rem+1px)]">
                                <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">
                                        1. Produk</p>
                                    <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center">Menghasilkan
                                        Produk Inovasi Tridharma Perguruan Tinggi yang berdaya Saing Nasional menuju
                                        Skala Regional;</p>
                                </div>
                                <div
                                    class="@container relative min-h-[30rem] w-full grow max-lg:mx-auto max-lg:max-w-sm">
                                    <div
                                        class="absolute inset-x-10 top-10 bottom-0 overflow-hidden rounded-t-[12cqw] border-x-[1cqw] border-t-[1cqw] border-gray-700 bg-gray-900 shadow-2xl">
                                        <img class="size-full object-cover object-top"
                                            src="https://stickearn.com/storage/app/uploads/public/651/696/f53/651696f5357d4533218433.jpg"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                            <div
                                class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/5 lg:rounded-l-[2rem]">
                            </div>
                        </div>
                        <div class="relative max-lg:row-start-1 animate-on-scroll">
                            <div class="absolute inset-px rounded-lg bg-white max-lg:rounded-t-[2rem]"></div>
                            <div
                                class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-t-[calc(2rem+1px)]">
                                <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">
                                        2. Civitas Akademika</p>
                                    <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center">Membentuk Insan
                                        Akademik (Dosen, Tenaga Kependidikan, dan
                                        Mahasiswa) yang Kompeten, Professional dan Berakhlak Karimah;</p>
                                </div>
                                <div
                                    class="flex flex-1 items-center justify-center px-8 max-lg:pt-10 max-lg:pb-12 sm:px-10 lg:pb-2">
                                    <img class="w-full max-lg:max-w-xs"
                                        src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEinyDcrvVceFAox56NGFEc9tDWjZIr4un5nj3EXVaNwN9yWLojKnKBNgCAOCOFrICKD0VxvuHbz5gXGiVD5E1UukK6xdNfejO3ltA6fyegnKmn8giKSVL1I-rczo6kiefRJT_3B0CY20Po/s391/Pengertian+Kompeten%252C+Kompetensi%252C+Unsur%252C+dan+Jenisnya.png"
                                        alt="">
                                </div>
                            </div>
                            <div
                                class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/5 max-lg:rounded-t-[2rem]">
                            </div>
                        </div>
                        <div class="relative max-lg:row-start-3 lg:col-start-2 lg:row-start-2 animate-on-scroll">
                            <div class="absolute inset-px rounded-lg bg-white"></div>
                            <div
                                class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)]">
                                <div class="px-8 pt-8 sm:px-10 sm:pt-10">
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">
                                        3. Tata Kelola</p>
                                    <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center">Mewujudkan Good
                                        Faculty Governance;</p>
                                </div>
                                <div class="@container flex flex-1 items-center max-lg:py-6 lg:pb-2">
                                    <img class="h-[min(152px,40cqw)] object-cover"
                                        src="https://asset.kompas.com/crops/YvRgBKJHXXVCMF0HjcrIuSX1WUs=/0x0:2216x1477/780x390/data/photo/2022/12/14/639952eb0e414.jpg"
                                        alt="">
                                </div>
                            </div>
                            <div
                                class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/5">
                            </div>
                        </div>
                        <div class="relative lg:row-span-2 animate-on-scroll">
                            <div
                                class="absolute inset-px rounded-lg bg-white max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]">
                            </div>
                            <div
                                class="relative flex h-full flex-col overflow-hidden rounded-[calc(var(--radius-lg)+1px)] max-lg:rounded-b-[calc(2rem+1px)] lg:rounded-r-[calc(2rem+1px)]">
                                <div class="px-8 pt-8 pb-3 sm:px-10 sm:pt-10 sm:pb-0">
                                    <p
                                        class="mt-2 text-lg font-medium tracking-tight text-gray-950 max-lg:text-center">
                                        4. Kemitraan</p>
                                    <p class="mt-2 max-w-lg text-sm/6 text-gray-600 max-lg:text-center">Menghasilkan
                                        Produk Kerjasama yang Mendukung Peningkatan Kualitas Tridharma Perguruan Tinggi.
                                    </p>
                                </div>
                                <div class="relative min-h-[30rem] w-full grow">
                                    <div
                                        class="absolute top-10 right-0 bottom-0 left-10 overflow-hidden rounded-tl-xl bg-gray-900 shadow-2xl">
                                        <img class="size-full object-cover object-top"
                                            src="https://pkebs.feb.ugm.ac.id/wp-content/uploads/sites/449/2018/02/Mitra-bisnis.jpg"
                                            alt="">
                                    </div>
                                </div>
                            </div>
                            <div
                                class="pointer-events-none absolute inset-px rounded-lg ring-1 shadow-sm ring-black/5 max-lg:rounded-b-[2rem] lg:rounded-r-[2rem]">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer
        class="bg-center bg-cover bg-no-repeat bg-[url('https://uinsgd.ac.id/wp-content/uploads/elementor/thumbs/IMG_9146-scaled-qibumy4zfccfw6i2ubygu8lhlobxchr6yb3uoza79c.jpg')] bg-sky-600 bg-blend-multiply">
        <div class="mx-auto w-full max-w-6xl py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="px-6 md:px-0 mb-6 md:mb-0 md:p-6 grid grid-cols-3 gap-2 md:gap-6 lg:gap-8">
                    <a href="/" class="mr-5">
                        <img src="Logo-UIN-Putih.png" class="h-40 md:h-52 me-3" alt="UIN Logo Putih" />
                    </a>
                    <div class="text-white text-2xl lg:text-4xl font-semibold text-justify col-span-2 max-w-72">
                        <h3>Laboratorium</h3>
                        <h3 class="text-sm lg:text-xl font-light">Fakultas Sains dan Teknologi</h3>
                        <div class="font-sans text-xs lg:text-base py-4">
                            <p>Jl. A.H. Nasution No. 105 Cibiru Kota Bandung 40614
                                Jawa Barat – Indonesia</p>
                            <p class="pt-4">
                                fst@uinsgd.ac.id
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-6 md:px-0 grid grid-cols-2 gap-1 xl:gap-8 lg:gap-6 lg:grid-cols-3">
                    <div>
                        <h2 class="mb-6 text-xs lg:text-sm font-semibold text-white uppercase">Program Studi</h2>
                        <ul class="text-white text-sm lg:text-base font-medium">
                            <li class="mb-4">
                                <a href="https://math.uinsgd.ac.id/" class="hover:underline ">Matematika</a>
                            </li>
                            <li class="mb-4">
                                <a href="http://bio.uinsgd.ac.id/" class="hover:underline ">Biologi</a>
                            </li>
                            <li class="mb-4">
                                <a href="https://fi.uinsgd.ac.id/" class="hover:underline ">Fisika</a>
                            </li>
                            <li class="mb-4">
                                <a href="https://chem.uinsgd.ac.id/" class="hover:underline ">Kimia</a>
                            </li>
                        </ul>
                    </div>
                    <div class="pt-11">
                        <ul class="text-white text-sm lg:text-base font-medium">

                            <li class="mb-4">
                                <a href="https://if.uinsgd.ac.id/" class="hover:underline ">Teknik
                                    Informatika</a>

                            </li>
                            <li class="mb-4">
                                <a href="https://agrotek.uinsgd.ac.id/" class="hover:underline ">Agroteknologi</a>
                            </li>
                            <li class="mb-4">
                                <a href="https://ee.uinsgd.ac.id/" class="hover:underline ">Teknik
                                    Elektro</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-white sm:mx-auto lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between px-6 md:px-0">
                <span class="text-sm text-white sm:text-center">© 2024 <a href="https://flowbite.com/"
                        class="hover:underline">Lab Fakultas Saintek</a>. UIN Sunan Gunung Djati Bandung.
                </span>
                <div class="flex mt-4 sm:justify-center sm:mt-0">
                    <a href="#" class="text-white hover:text-uinYellow">
                        <i class="fi fi-brands-facebook"></i>
                        <span class="sr-only">Facebook page</span>
                    </a>
                    <a href="#" class="text-white hover:text-uinYellow ms-5">
                        <i class="fi fi-brands-instagram"></i>
                        <span class="sr-only">Instagram account</span>
                    </a>
                    <a href="#" class="text-white hover:text-uinYellow ms-5">
                        <i class="fi fi-brands-twitter-alt"></i>
                        <span class="sr-only">X account</span>
                    </a>
                    <a href="#" class="text-white hover:text-uinYellow ms-5">
                        <i class="fi fi-brands-youtube"></i>
                        <span class="sr-only">Youtube account</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>

</html>
