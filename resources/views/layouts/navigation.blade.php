<nav x-data="{ open: false }" class="fixed top-0 z-50 w-full bg-uinBlue">
    <div class="p-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="flex ms-2 md:me-24 text-white">
                    <img src="{{ asset('Logo-UIN-Putih.png') }}" class="h-10 me-3" alt="UIN SGD Logo" />
                    {{-- <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap">{{ config('app.name') }}</span> --}}
                    <div class="flex flex-col justify-center">
                        <h2 class="font-semibold text-base leading-none">Sistem Manajemen Aset</h2>
                        <h1 class="text-lg leading-none">Laboratorium Saintek</h1>
                    </div>
                </a>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md text-white hover:text-uinOrange focus:outline-none transition ease-in-out duration-150">
                            {{ Auth::user()->name }}
                            <div class="ms-1">
                                <svg class="fill-current h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm/6 hover:bg-uinBlue transition-all duration-300">
                            <div
                                class="flex font-bold text-gray-900 size-11 leading-none flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white group-hover:text-uinBlue transition-all duration-300">
                                <p><i class="fi fi-rr-circle-user leading-none text-xl"></i></p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="block font-semibold text-gray-900 hover:text-white transition-all duration-300">
                                Profile
                                <span class="absolute inset-0"></span>
                            </a>
                        </div>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div
                                class="group relative flex items-center gap-x-6 rounded-lg p-2 text-sm/6 hover:bg-uinRed duration-300 transition">
                                <div
                                    class="flex font-bold text-white size-11 leading-none flex-none items-center justify-center rounded-lg bg-uinRed group-hover:bg-white group-hover:text-uinRed transition-all duration-300">
                                    <p><i class="fi fi-rr-exit leading-none text-xl"></i></p>
                                </div>
                                <a href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="block font-semibold text-uinRed hover:text-white transition-all duration-300">
                                    Sign Out
                                    <span class="absolute inset-0"></span>
                                </a>
                            </div>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                        <path class="inline-flex"
                            d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200 shadow-md">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Sign Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
