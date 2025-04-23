<aside :class="openSidebar ? 'w-64' : 'w-0 -translate-x-10 md:translate-x-0 md:w-20'" id="logo-sidebar"
    class="fixed top-0 left-0 z-40 h-screen pt-20 transition-all duration-300 bg-white shadow-md" aria-label="Sidebar">
    @php
        $menus = [
            [
                'route' =>
                    Auth::user()->usertype == 'admin'
                        ? route('admin.dashboard')
                        : (Auth::user()->usertype == 'user'
                            ? route('user.dashboard')
                            : route('dashboard')),
                'isActive' => Request::routeIs(['dashboard', 'admin.dashboard', 'user.dashboard']),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24" width="512" height="512"><path d="M7,0H4A4,4,0,0,0,0,4V7a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V4A4,4,0,0,0,7,0ZM9,7A2,2,0,0,1,7,9H4A2,2,0,0,1,2,7V4A2,2,0,0,1,4,2H7A2,2,0,0,1,9,4Z"/><path d="M20,0H17a4,4,0,0,0-4,4V7a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V4A4,4,0,0,0,20,0Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V4a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/><path d="M7,13H4a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4H7a4,4,0,0,0,4-4V17A4,4,0,0,0,7,13Zm2,7a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2H7a2,2,0,0,1,2,2Z"/><path d="M20,13H17a4,4,0,0,0-4,4v3a4,4,0,0,0,4,4h3a4,4,0,0,0,4-4V17A4,4,0,0,0,20,13Zm2,7a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2V17a2,2,0,0,1,2-2h3a2,2,0,0,1,2,2Z"/></svg></div>',
                'label' => 'Dashboard',
            ],
            [
                'route' => route('data-aset'),
                'isActive' => Request::routeIs(['data-aset', 'add-aset-inv']),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" id="Layer_1" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m24 11.5a4.476 4.476 0 0 0 -1.706-3.5 4.481 4.481 0 0 0 -2.794-8h-15a4.481 4.481 0 0 0 -2.794 8 4.443 4.443 0 0 0 0 7 4.481 4.481 0 0 0 2.794 8h15a4.481 4.481 0 0 0 2.794-8 4.476 4.476 0 0 0 1.706-3.5zm-22-7a2.5 2.5 0 0 1 2.5-2.5h.5v1a1 1 0 0 0 2 0v-1h2v1a1 1 0 0 0 2 0v-1h8.5a2.5 2.5 0 0 1 0 5h-15a2.5 2.5 0 0 1 -2.5-2.5zm20 14a2.5 2.5 0 0 1 -2.5 2.5h-15a2.5 2.5 0 0 1 0-5h.5v1a1 1 0 0 0 2 0v-1h2v1a1 1 0 0 0 2 0v-1h8.5a2.5 2.5 0 0 1 2.5 2.5zm-17.5-4.5a2.5 2.5 0 0 1 0-5h.5v1a1 1 0 0 0 2 0v-1h2v1a1 1 0 0 0 2 0v-1h8.5a2.5 2.5 0 0 1 0 5z"/></svg></div>',
                'label' => 'Aset Inventaris',
            ],
            [
                'route' => route('data-bhp'),
                'isActive' => Request::routeIs(['data-bhp', 'add-aset-bhp']),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Bold" viewBox="0 0 24 24" width="512" height="512"><path d="M.912,10.6,10.2,16.169a3.492,3.492,0,0,0,3.6,0L23.088,10.6a1.861,1.861,0,0,0,0-3.192L13.8,1.831a3.489,3.489,0,0,0-3.6,0h0L.912,7.4a1.861,1.861,0,0,0,0,3.192ZM11.743,4.4a.5.5,0,0,1,.514,0L19.918,9l-7.661,4.6a.5.5,0,0,1-.514,0L4.082,9Z"/><path d="M13.286,18.831a2.5,2.5,0,0,1-2.572,0L2.272,13.766A1.5,1.5,0,0,0,0,15.052H0a1.5,1.5,0,0,0,.728,1.286L9.17,21.4a5.488,5.488,0,0,0,5.66,0l8.442-5.065A1.5,1.5,0,0,0,24,15.052h0a1.5,1.5,0,0,0-2.272-1.286Z"/></svg></div>',
                'label' => 'Barang Habis Pakai',
            ],
            [
                'label' => 'Perencanaan',
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M19,2H5C2.243,2,0,4.243,0,7v10c0,2.757,2.243,5,5,5h14c2.757,0,5-2.243,5-5V7c0-2.757-2.243-5-5-5ZM5,4h14c1.654,0,3,1.346,3,3H2c0-1.654,1.346-3,3-3Zm-3,13V9H11v11H5c-1.654,0-3-1.346-3-3Zm17,3h-6V9h9v8c0,1.654-1.346,3-3,3Z"/></svg></div>',
                'isDropdown' => true,
                'isActive' => Request::routeIs([
                    'perencanaan-bhp',
                    'perencanaan-inv',
                    'prediksi',
                    'add-perencanaan.bhp',
                    'add-perencanaan.inv',
                    'detail-perencanaan',
                ]),
                'submenu' => [
                    [
                        'route' => route('perencanaan-inv'),
                        'icon' =>
                            '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M16.5,0c-4.206,0-7.5,1.977-7.5,4.5v2.587c-.483-.057-.985-.087-1.5-.087C3.294,7,0,8.977,0,11.5v8c0,2.523,3.294,4.5,7.5,4.5,3.407,0,6.216-1.297,7.16-3.131,.598,.087,1.214,.131,1.84,.131,4.206,0,7.5-1.977,7.5-4.5V4.5c0-2.523-3.294-4.5-7.5-4.5Zm5.5,12.5c0,1.18-2.352,2.5-5.5,2.5-.512,0-1.014-.035-1.5-.103v-1.984c.49,.057,.992,.087,1.5,.087,2.194,0,4.14-.538,5.5-1.411v.911ZM2,14.589c1.36,.873,3.306,1.411,5.5,1.411s4.14-.538,5.5-1.411v.911c0,1.18-2.352,2.5-5.5,2.5s-5.5-1.32-5.5-2.5v-.911Zm20-6.089c0,1.18-2.352,2.5-5.5,2.5-.535,0-1.06-.038-1.566-.112-.193-.887-.8-1.684-1.706-2.323,.984,.28,2.092,.435,3.272,.435,2.194,0,4.14-.538,5.5-1.411v.911Zm-5.5-6.5c3.148,0,5.5,1.32,5.5,2.5s-2.352,2.5-5.5,2.5-5.5-1.32-5.5-2.5,2.352-2.5,5.5-2.5ZM7.5,9c3.148,0,5.5,1.32,5.5,2.5s-2.352,2.5-5.5,2.5-5.5-1.32-5.5-2.5,2.352-2.5,5.5-2.5Zm0,13c-3.148,0-5.5-1.32-5.5-2.5v-.911c1.36,.873,3.306,1.411,5.5,1.411s4.14-.538,5.5-1.411v.911c0,1.18-2.352,2.5-5.5,2.5Zm9-3c-.512,0-1.014-.035-1.5-.103v-1.984c.49,.057,.992,.087,1.5,.087,2.194,0,4.14-.538,5.5-1.411v.911c0,1.18-2.352,2.5-5.5,2.5Z"/></svg></div>',
                        'label' => 'Aset Inventaris',
                        'isActive' => Request::routeIs(['perencanaan-inv', 'add-perencanaan.inv']),
                    ],
                    [
                        'route' => route('perencanaan-bhp'),
                        'icon' =>
                            '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512"><path d="M18.713,12H14a2,2,0,0,1-2-2V5.274a3,3,0,0,0-1.166-2.383,2.871,2.871,0,0,0-2.481-.534,10.969,10.969,0,0,0,.553,21.414A11,11,0,0,0,21.64,15.657a2.876,2.876,0,0,0-.533-2.485A3.055,3.055,0,0,0,18.713,12Zm.988,3.168A8.969,8.969,0,1,1,8.842,4.3a.871.871,0,0,1,.764.172,1.016,1.016,0,0,1,.4.806V10a4,4,0,0,0,4,4h4.712a1.041,1.041,0,0,1,.816.4A.884.884,0,0,1,19.7,15.168Z"/><path d="M23.651,7.446A10.073,10.073,0,0,0,16.582.372,2.1,2.1,0,0,0,16.038.3a2,2,0,0,0-2.019,2V7a3,3,0,0,0,3,3h4.719A2.008,2.008,0,0,0,23.651,7.446ZM21.153,8H17.015a1,1,0,0,1-1-1l-.008-4.693a.048.048,0,0,1,.025-.009l.026,0A8.072,8.072,0,0,1,21.734,8Z"/></svg></div>',
                        'label' => 'Barang Habis Pakai',
                        'isActive' => Request::routeIs(['perencanaan-bhp', 'add-perencanaan.bhp']),
                    ],
                    [
                        'route' => route('prediksi'),
                        'icon' =>
                            '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg"><svg class="size-4 fill-white" id="Layer_1" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m16 16a1 1 0 0 1 -1 1h-2v2a1 1 0 0 1 -2 0v-2h-2a1 1 0 0 1 0-2h2v-2a1 1 0 0 1 2 0v2h2a1 1 0 0 1 1 1zm6-5.515v8.515a5.006 5.006 0 0 1 -5 5h-10a5.006 5.006 0 0 1 -5-5v-14a5.006 5.006 0 0 1 5-5h4.515a6.958 6.958 0 0 1 4.95 2.05l3.484 3.486a6.951 6.951 0 0 1 2.051 4.949zm-6.949-7.021a5.01 5.01 0 0 0 -1.051-.78v4.316a1 1 0 0 0 1 1h4.316a4.983 4.983 0 0 0 -.781-1.05zm4.949 7.021c0-.165-.032-.323-.047-.485h-4.953a3 3 0 0 1 -3-3v-4.953c-.162-.015-.321-.047-.485-.047h-4.515a3 3 0 0 0 -3 3v14a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3z"/></svg></div>',
                        'label' => 'Prediksi BHP',
                        'isActive' => Request::routeIs('prediksi'),
                    ],
                ],
            ],
            [
                'route' => route('data-harga'),
                'isActive' => Request::routeIs(['data-harga', 'add-harga']),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24"><path d="m6,9c-3.421,0-6,1.505-6,3.5v8c0,1.995,2.579,3.5,6,3.5s6-1.505,6-3.5v-8c0-1.995-2.579-3.5-6-3.5Zm4,7.5c0,.529-1.519,1.5-4,1.5s-4-.971-4-1.5v-1.348c1.046.533,2.435.848,4,.848s2.954-.315,4-.848v1.348Zm-4-5.5c2.481,0,4,.971,4,1.5s-1.519,1.5-4,1.5-4-.971-4-1.5,1.519-1.5,4-1.5Zm0,11c-2.481,0-4-.971-4-1.5v-1.348c1.046.533,2.435.848,4,.848s2.954-.315,4-.848v1.348c0,.529-1.519,1.5-4,1.5ZM24,5v14c0,2.757-2.243,5-5,5h-5c-.553,0-1-.448-1-1s.447-1,1-1h5c1.654,0,3-1.346,3-3V5c0-1.654-1.346-3-3-3h-10c-1.654,0-3,1.346-3,3v1c0,.552-.447,1-1,1s-1-.448-1-1v-1C4,2.243,6.243,0,9,0h10c2.757,0,5,2.243,5,5Zm-11,5c-.553,0-1-.448-1-1s.447-1,1-1h5v-2h-8v.5c0,.552-.447,1-1,1s-1-.448-1-1v-.5c0-1.103.897-2,2-2h8c1.103,0,2,.897,2,2v2c0,1.103-.897,2-2,2h-5Zm1,8c0-.552.447-1,1-1h4c.553,0,1,.448,1,1s-.447,1-1,1h-4c-.553,0-1-.448-1-1Zm0-4v-1c0-.552.447-1,1-1s1,.448,1,1v1c0,.552-.447,1-1,1s-1-.448-1-1Zm6,0c0,.552-.447,1-1,1s-1-.448-1-1v-1c0-.552.447-1,1-1s1,.448,1,1v1Z"/></svg></div>',
                'label' => 'Data Harga Barang',
            ],
            [
                'route' => route('penggunaan'),
                'isActive' => Request::routeIs(['penggunaan', 'add-penggunaan', 'detail-penggunaan']),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" id="Layer_1" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m17 14a1 1 0 0 1 -1 1h-8a1 1 0 0 1 0-2h8a1 1 0 0 1 1 1zm-4 3h-5a1 1 0 0 0 0 2h5a1 1 0 0 0 0-2zm9-6.515v8.515a5.006 5.006 0 0 1 -5 5h-10a5.006 5.006 0 0 1 -5-5v-14a5.006 5.006 0 0 1 5-5h4.515a6.958 6.958 0 0 1 4.95 2.05l3.484 3.486a6.951 6.951 0 0 1 2.051 4.949zm-6.949-7.021a5.01 5.01 0 0 0 -1.051-.78v4.316a1 1 0 0 0 1 1h4.316a4.983 4.983 0 0 0 -.781-1.05zm4.949 7.021c0-.165-.032-.323-.047-.485h-4.953a3 3 0 0 1 -3-3v-4.953c-.162-.015-.321-.047-.485-.047h-4.515a3 3 0 0 0 -3 3v14a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3z"/></svg></div>',
                'label' => 'Penggunaan',
            ],
            [
                'route' => route('peminjaman'),
                'isActive' => Request::routeIs(['peminjaman', 'add-peminjaman', 'detail-peminjaman']),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24"><path d="m19.642,14.308c.274-.297.536-.625.741-.974.346-.59-.115-1.333-.799-1.333h-3.167c-.684,0-1.144.743-.799,1.333.204.349.466.677.741.974-2.376.879-4.358,3.52-4.358,6.204,0,1.923,1.57,3.488,3.5,3.488h5c1.93,0,3.5-1.565,3.5-3.488,0-2.684-1.982-5.325-4.358-6.204Zm.858,7.692h-5c-.827,0-1.5-.667-1.5-1.488,0-2.192,2.056-4.512,4-4.512s4,2.319,4,4.512c0,.821-.673,1.488-1.5,1.488Zm-1.5-20h-1v-1c0-.552-.447-1-1-1s-1,.448-1,1v1h-8v-1c0-.552-.448-1-1-1s-1,.448-1,1v1h-1C2.243,2,0,4.243,0,7v12c0,2.757,2.243,5,5,5h4c.552,0,1-.448,1-1s-.448-1-1-1h-4c-1.654,0-3-1.346-3-3v-9h20v1c0,.552.447,1,1,1s1-.448,1-1v-4c0-2.757-2.243-5-5-5ZM2,8v-1c0-1.654,1.346-3,3-3h14c1.654,0,3,1.346,3,3v1H2Z"/></svg></div>',
                'label' => 'Peminjaman',
            ],
            [
                'route' => route('report'),
                'isActive' => Request::routeIs('report'),
                'icon' =>
                    '<div class="p-2 bg-uinGreen group-hover:bg-uinBlue rounded-lg transition duration-500"><svg class="size-4 fill-white" id="Layer_1" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m6 15c.553 0 1 .448 1 1v3c0 .552-.447 1-1 1s-1-.448-1-1v-3c0-.552.447-1 1-1zm4-4c-.553 0-1 .448-1 1v7c0 .552.447 1 1 1s1-.448 1-1v-7c0-.552-.447-1-1-1zm14 10.833c0 1.377-1.641 2.167-4.5 2.167s-4.5-.79-4.5-2.167v-7.333c0-.883 1.85-1.5 4.5-1.5s4.5.617 4.5 1.5zm-4.5-5.833c-.956 0-1.802-.083-2.5-.227v1.769c.249.165.996.458 2.5.458 1.48 0 2.237-.31 2.5-.489v-1.737c-.698.144-1.544.227-2.5.227zm2.5 5.607v-1.938c-.662.201-1.48.331-2.5.331-1.041 0-1.85-.127-2.5-.321v1.928c.296.16 1.114.393 2.5.393s2.204-.233 2.5-.393zm-9-11.607c-1.654 0-3-1.346-3-3v-4.953c-.162-.016-.321-.047-.485-.047h-4.515c-1.654 0-3 1.346-3 3v14c0 1.654 1.346 3 3 3h7c.553 0 1 .448 1 1s-.447 1-1 1h-7c-2.757 0-5-2.243-5-5v-14c0-2.757 2.243-5 5-5h4.515c1.869 0 3.627.728 4.95 2.05l3.484 3.486c1.173 1.172 1.894 2.729 2.028 4.383.045.55-.365 1.033-.916 1.078-.586.056-1.064-.418-1.092-.997h-4.97zm0-2h4.316c-.218-.378-.468-.738-.781-1.05l-3.484-3.485c-.315-.315-.675-.564-1.051-.781v4.316c0 .551.448 1 1 1z"/></svg></div>',
                'label' => 'Stock Opname',
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
                            <span class="flex-1 ms-3 whitespace-nowrap transition-all duration-300"
                                :class="openSidebar ? 'opacity-100' : 'opacity-0'">{{ $menu['label'] }}</span>
                        </x-side-link>
                    </li>
                @else
                    <li x-data="{
                        open: JSON.parse(localStorage.getItem('sidebarOpen_{{ $menu['label'] }}')) || false,
                        toggle() {
                            this.open = !this.open;
                            localStorage.setItem('sidebarOpen_{{ $menu['label'] }}', JSON.stringify(this.open));
                        }
                    }">
                        <button @click="toggle()"
                            class="flex items-center w-full px-3 py-2 text-base rounded-lg group {{ $menu['isActive'] ? 'bg-uinOrange text-white' : 'bg-white text-gray-900 hover:bg-uinYellow hover:text-white' }}">
                            {!! str_replace('bg-uinGreen', $menu['isActive'] ? 'bg-uinBlue' : 'bg-uinGreen', $menu['icon']) !!}
                            <span
                                class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap transition-all duration-300"
                                :class="openSidebar ? 'opacity-100' : 'opacity-0'">{{ $menu['label'] }}</span>
                            <svg class="w-3 h-3 transition-transform duration-500 {{ $menu['isActive'] ? 'text-white' : 'text-gray-900 group-hover:text-white' }}"
                                :class="open ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <ul x-show="open" x-transition.opacity x-collapse class="py-2 space-y-2">
                            @foreach ($menu['submenu'] as $submenu)
                                <li class="pl-2">
                                    <x-side-link href="{{ $submenu['route'] }}" :active="$submenu['isActive']">
                                        {!! str_replace('bg-uinGreen', $submenu['isActive'] ? 'bg-uinBlue' : 'bg-uinGreen', $submenu['icon']) !!}
                                        <span class="flex-1 ms-3 whitespace-nowrap transition-all duration-300"
                                            :class="openSidebar ? 'opacity-100' : 'opacity-0'">{{ $submenu['label'] }}</span>
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
                    <x-side-link href="{{ route('admin.staff') }}" :active="Request::routeIs('admin.staff')">
                        <div @class([
                            'p-2 rounded-lg group-hover:bg-uinBlue',
                            'bg-uinGreen' => !Request::routeIs('admin.staff'),
                            'bg-uinBlue' => Request::routeIs('admin.staff'),
                        ])>
                            <svg class="size-4 fill-white" id="Layer_1" height="512" viewBox="0 0 24 24"
                                width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                                <path
                                    d="m7.5 13a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm0-7a2.5 2.5 0 1 0 2.5 2.5 2.5 2.5 0 0 0 -2.5-2.5zm7.5 17v-.5a7.5 7.5 0 0 0 -15 0v.5a1 1 0 0 0 2 0v-.5a5.5 5.5 0 0 1 11 0v.5a1 1 0 0 0 2 0zm9-5a7 7 0 0 0 -11.667-5.217 1 1 0 1 0 1.334 1.49 5 5 0 0 1 8.333 3.727 1 1 0 0 0 2 0zm-6.5-9a4.5 4.5 0 1 1 4.5-4.5 4.505 4.505 0 0 1 -4.5 4.5zm0-7a2.5 2.5 0 1 0 2.5 2.5 2.5 2.5 0 0 0 -2.5-2.5z" />
                            </svg>
                        </div>
                        <span class="flex-1 ms-3 whitespace-nowrap transition-all duration-300"
                            :class="openSidebar ? 'opacity-100' : 'opacity-0'">Manajemen Pengguna</span>
                    </x-side-link>
                </li>
            @endif

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}"
                        class="flex items-center px-3 py-2 text-uinRed rounded-lg hover:bg-uinRed hover:font-medium hover:text-white group transition-all duration-100"
                        onclick="event.preventDefault();
                                    this.closest('form').submit();">
                        <div class="p-2 rounded-lg bg-uinRed group-hover:bg-white transition duration-300">
                            <svg class="size-4 fill-white group-hover:fill-uinRed" xmlns="http://www.w3.org/2000/svg"
                                id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                                <path
                                    d="M11.476,15a1,1,0,0,0-1,1v3a3,3,0,0,1-3,3H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2H7.476a3,3,0,0,1,3,3V8a1,1,0,0,0,2,0V5a5.006,5.006,0,0,0-5-5H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H7.476a5.006,5.006,0,0,0,5-5V16A1,1,0,0,0,11.476,15Z" />
                                <path
                                    d="M22.867,9.879,18.281,5.293a1,1,0,1,0-1.414,1.414l4.262,4.263L6,11a1,1,0,0,0,0,2H6l15.188-.031-4.323,4.324a1,1,0,1,0,1.414,1.414l4.586-4.586A3,3,0,0,0,22.867,9.879Z" />
                            </svg>
                        </div>
                        <span class="flex-1 ms-3 whitespace-nowrap transition-all duration-300"
                            :class="openSidebar ? 'opacity-100' : 'opacity-0'">Sign Out</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</aside>
