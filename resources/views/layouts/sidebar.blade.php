<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white sm:translate-x-0"
    aria-label="Sidebar">
    @php
        $menus = [
            [
                'route' => Auth::user()->usertype == 'admin' ? route('admin.dashboard') : (Auth::user()->usertype == 'user' ? route('user.dashboard') : route('dashboard')),
                'isActive' => Request::routeIs(['dashboard', 'admin.dashboard', 'user.dashboard']),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="512" height="512"><path d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z"/><path d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/><path d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z"/><path d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/></svg></div>',
                'label' => 'Dashboard',
            ],
            [
                'route' => '/data-aset',
                'isActive' => Request::routeIs('data-aset'),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" id="Layer_1" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m24 11.5a4.476 4.476 0 0 0 -1.706-3.5 4.481 4.481 0 0 0 -2.794-8h-15a4.481 4.481 0 0 0 -2.794 8 4.443 4.443 0 0 0 0 7 4.481 4.481 0 0 0 2.794 8h15a4.481 4.481 0 0 0 2.794-8 4.476 4.476 0 0 0 1.706-3.5zm-22-7a2.5 2.5 0 0 1 2.5-2.5h.5v1a1 1 0 0 0 2 0v-1h2v1a1 1 0 0 0 2 0v-1h8.5a2.5 2.5 0 0 1 0 5h-15a2.5 2.5 0 0 1 -2.5-2.5zm20 14a2.5 2.5 0 0 1 -2.5 2.5h-15a2.5 2.5 0 0 1 0-5h.5v1a1 1 0 0 0 2 0v-1h2v1a1 1 0 0 0 2 0v-1h8.5a2.5 2.5 0 0 1 2.5 2.5zm-17.5-4.5a2.5 2.5 0 0 1 0-5h.5v1a1 1 0 0 0 2 0v-1h2v1a1 1 0 0 0 2 0v-1h8.5a2.5 2.5 0 0 1 0 5z"/></svg></div>',
                'label' => 'Aset Inventaris',
            ],
            [
                'route' => '/data-barang-habis-pakai',
                'isActive' => Request::routeIs('data-bhp'),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Bold" viewBox="0 0 24 24" width="512" height="512"><path d="M.912,10.6,10.2,16.169a3.492,3.492,0,0,0,3.6,0L23.088,10.6a1.861,1.861,0,0,0,0-3.192L13.8,1.831a3.489,3.489,0,0,0-3.6,0h0L.912,7.4a1.861,1.861,0,0,0,0,3.192ZM11.743,4.4a.5.5,0,0,1,.514,0L19.918,9l-7.661,4.6a.5.5,0,0,1-.514,0L4.082,9Z"/><path d="M13.286,18.831a2.5,2.5,0,0,1-2.572,0L2.272,13.766A1.5,1.5,0,0,0,0,15.052H0a1.5,1.5,0,0,0,.728,1.286L9.17,21.4a5.488,5.488,0,0,0,5.66,0l8.442-5.065A1.5,1.5,0,0,0,24,15.052h0a1.5,1.5,0,0,0-2.272-1.286Z"/></svg></div>',
                'label' => 'Barang Habis Pakai',
            ],
            [
                'label' => 'Perencanaan',
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M19,2H5C2.243,2,0,4.243,0,7v10c0,2.757,2.243,5,5,5h14c2.757,0,5-2.243,5-5V7c0-2.757-2.243-5-5-5ZM5,4h14c1.654,0,3,1.346,3,3H2c0-1.654,1.346-3,3-3Zm-3,13V9H11v11H5c-1.654,0-3-1.346-3-3Zm17,3h-6V9h9v8c0,1.654-1.346,3-3,3Z"/></svg></div>',
                'isDropdown' => true,
                'isActive' => Request::routeIs(['perencanaan-bhp', 'perencanaan-inv', 'prediksi']),
                'submenu' => [
                    [
                        'route' => '/perencanaan-inv',
                        'label' => 'Aset Inventaris',
                        'isActive' => Request::routeIs('perencanaan-inv'),
                    ],
                    [
                        'route' => '/perencanaan-bhp',
                        'label' => 'Barang Habis Pakai',
                        'isActive' => Request::routeIs('perencanaan-bhp'),
                    ],
                    [
                        'route' => '/prediksi',
                        'label' => 'Prediksi Barang Habis Pakai',
                        'isActive' => Request::routeIs('prediksi'),
                    ],
                ],
            ],
            [
                'route' => '/penggunaan',
                'isActive' => Request::routeIs('penggunaan'),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" id="Layer_1" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m17 14a1 1 0 0 1 -1 1h-8a1 1 0 0 1 0-2h8a1 1 0 0 1 1 1zm-4 3h-5a1 1 0 0 0 0 2h5a1 1 0 0 0 0-2zm9-6.515v8.515a5.006 5.006 0 0 1 -5 5h-10a5.006 5.006 0 0 1 -5-5v-14a5.006 5.006 0 0 1 5-5h4.515a6.958 6.958 0 0 1 4.95 2.05l3.484 3.486a6.951 6.951 0 0 1 2.051 4.949zm-6.949-7.021a5.01 5.01 0 0 0 -1.051-.78v4.316a1 1 0 0 0 1 1h4.316a4.983 4.983 0 0 0 -.781-1.05zm4.949 7.021c0-.165-.032-.323-.047-.485h-4.953a3 3 0 0 1 -3-3v-4.953c-.162-.015-.321-.047-.485-.047h-4.515a3 3 0 0 0 -3 3v14a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3z"/></svg></div>',
                'label' => 'Penggunaan',
            ],
        ];
    @endphp
    <div class="h-full px-3 pb-4 overflow-y-auto shadow-md">
        <ul class="space-y-2 font-normal">
            @foreach ($menus as $menu)
                @if (!isset($menu['isDropdown']))
                    <li>
                        <x-side-link href="{{ $menu['route'] }}" :active="$menu['isActive']">
                            {!! str_replace('bg-uinGreen', $menu['isActive'] ? 'bg-uinBlue' : 'bg-uinGreen', $menu['icon']) !!}
                            <span class="flex-1 ms-3 whitespace-nowrap">{{ $menu['label'] }}</span>
                        </x-side-link>
                    </li>
                @else
                    <li x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center w-full px-3 py-4 text-base transition duration-500 rounded-lg group {{ $menu['isActive'] ? 'bg-uinOrange text-white' : 'bg-white text-gray-900 hover:bg-uinYellow hover:text-white' }}">
                            {!! str_replace('bg-uinGreen', $menu['isActive'] ? 'bg-uinBlue' : 'bg-uinGreen', $menu['icon']) !!}
                            <span
                                class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $menu['label'] }}</span>
                            <svg class="w-3 h-3 transition-transform duration-500 {{ $menu['isActive'] ? 'text-white' : 'text-gray-900 group-hover:text-white' }}"
                                :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <ul x-show="open" x-transition.opacity x-collapse class="py-2 space-y-2">
                            @foreach ($menu['submenu'] as $submenu)
                                <li>
                                    <x-side-link href="{{ $submenu['route'] }}" :active="$submenu['isActive']">
                                        {{ $submenu['label'] }}
                                    </x-side-link>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach

            {{-- Admin Link --}}
            @if (Auth::user()->usertype == 'admin')
                <li>
                    <x-side-link href="/admin/data-staff" :active="request()->is('admin/data-staff')">
                        <div class="p-2 rounded-lg bg-uinGreen group-hover:bg-uinBlue">
                            <svg class="size-4 fill-white" id="Layer_1" height="512" viewBox="0 0 24 24" width="512"
                                xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                                <path
                                    d="m7.5 13a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm0-7a2.5 2.5 0 1 0 2.5 2.5 2.5 2.5 0 0 0 -2.5-2.5zm7.5 17v-.5a7.5 7.5 0 0 0 -15 0v.5a1 1 0 0 0 2 0v-.5a5.5 5.5 0 0 1 11 0v.5a1 1 0 0 0 2 0zm9-5a7 7 0 0 0 -11.667-5.217 1 1 0 1 0 1.334 1.49 5 5 0 0 1 8.333 3.727 1 1 0 0 0 2 0zm-6.5-9a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm0-7a2.5 2.5 0 1 0 2.5 2.5 2.5 2.5 0 0 0 -2.5-2.5z" />
                            </svg>
                        </div>
                        <span class="flex-1 ms-3 whitespace-nowrap">Manajemen Pengguna</span>
                    </x-side-link>
                </li>
            @endif

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}" class="flex items-center px-3 py-4 text-uinRed rounded-lg hover:bg-uinRed hover:font-medium hover:text-white group transition duration-300"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        <div class="p-2 rounded-lg bg-uinRed group-hover:bg-white transition duration-300">
                            <svg class="size-4 fill-white group-hover:fill-uinRed" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                                data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                                <path
                                    d="M11.476,15a1,1,0,0,0-1,1v3a3,3,0,0,1-3,3H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2H7.476a3,3,0,0,1,3,3V8a1,1,0,0,0,2,0V5a5.006,5.006,0,0,0-5-5H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H7.476a5.006,5.006,0,0,0,5-5V16A1,1,0,0,0,11.476,15Z" />
                                <path
                                    d="M22.867,9.879,18.281,5.293a1,1,0,1,0-1.414,1.414l4.262,4.263L6,11a1,1,0,0,0,0,2H6l15.188-.031-4.323,4.324a1,1,0,1,0,1.414,1.414l4.586-4.586A3,3,0,0,0,22.867,9.879Z" />
                            </svg>
                        </div>
                        <span class="flex-1 ms-3 whitespace-nowrap">Sign Out</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</aside>
