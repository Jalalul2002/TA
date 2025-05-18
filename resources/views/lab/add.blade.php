<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('lab.index') }}">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('◀️ Tambah Data Lab') }}
            </h2>
        </a>
    </x-slot>
    @if (session('success') || session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="fixed top-20 right-10 p-3 rounded-md shadow-md transition-all duration-500"
            :class="{ 'bg-green-500 text-white': '{{ session('success') }}', 'bg-red-500 text-white': '{{ session('error') }}' }"
            x-transition:enter="transform ease-out duration-500" x-transition:enter-start="opacity-0 -translate-y-5"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transform ease-in duration-500"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-5">
            {{ session('success') ?? session('error') }}
        </div>
    @endif
    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 grid lg:grid-cols-2 xl:grid-cols-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('lab.add') }}">
                    @csrf
                    <div class="mt-4">
                        <x-input-label for="lab_code" :value="__('Kode Lab')" />
                        <x-text-input id="lab_code" class="block mt-1 w-full" type="text" name="lab_code"
                            :value="old('lab_code')" required autofocus autocomplete="lab_code" />
                        <x-input-error :messages="$errors->get('product_code')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="name" :value="__('Nama Lab')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="prodi" :value="__('Program Studi (Optional)')" />
                        <select id="prodi" name="prodi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1">
                            <option value="">-- Pilih Program Studi --</option>
                            <option value="Matematika">Matematika</option>
                            <option value="Biologi">Biologi</option>
                            <option value="Fisika">Fisika</option>
                            <option value="Kimia">Kimia</option>
                            <option value="Teknik Informatika">Teknik Informatika</option>
                            <option value="Agroteknologi">Agroteknologi</option>
                            <option value="Teknik Elektro">Teknik Elektro</option>
                        </select>
                        <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="capacity" :value="__('Kapasitas (Optional)')" />
                        <x-text-input id="capacity" class="block mt-1 w-full" type="number" name="capacity"
                            :value="old('capacity')" autofocus autocomplete="capacity" min='0' />
                        <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('lab.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                        <x-primary-button class="ms-4">
                            {{ __('Simpan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>
