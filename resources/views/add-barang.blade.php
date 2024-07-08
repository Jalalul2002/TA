<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('add-product') }}">
                    @csrf

                    <!-- Name -->
                    <div>
                        <x-input-label for="product_name" :value="__('Nama Barang')" />
                        <x-text-input id="product_name" class="block mt-1 w-full" type="text" name="product_name"
                            :value="old('product_name')" required autofocus autocomplete="product_name" />
                        <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                    </div>

                    {{-- Role --}}
                    <div class="mt-4">
                        <x-input-label for="product_description" :value="__('Deskripsi Poduk')" />
                        <x-text-input id="product_description" class="block mt-1 w-full" type="text" name="product_description"
                            :value="old('product_description')" autofocus autocomplete="product_description" />
                        <x-input-error :messages="$errors->get('product_description')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Tambah Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>
