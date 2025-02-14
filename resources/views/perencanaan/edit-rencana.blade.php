<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('detail-perencanaan', ['id' => $item->rencana->id]) }}">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('◀️ Edit Data Rencana') }}
            </h2>
        </a>
    </x-slot>
    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 grid lg:grid-cols-2 xl:grid-cols-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('rencana.update-rencana', $item->id) }}">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="product_code" :value="__('Kode Produk')" />
                        <x-text-input id="product_code" class="block mt-1 w-full" type="text" name="product_code"
                            :value="old('product_code', $item->product_code)" readonly disabled autofocus autocomplete="product_code" />
                        <x-input-error :messages="$errors->get('product_code')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_name" :value="__('Produk')" />
                        <x-text-input id="product_name" class="block mt-1 w-full" type="text" name="product_name"
                            :value="old(
                                'product_name',
                                $item->product->product_name .
                                    ' (' .
                                    $item->product->product_detail .
                                    ') ' .
                                    ' (' .
                                    $item->product->merk .
                                    ') ',
                            )" readonly disabled autofocus autocomplete="product_name" />
                        <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="type" :value="__('Tipe')" />
                        <x-text-input id="type" class="block mt-1 w-full" type="text" name="type"
                            :value="old('type', $item->product->type)" readonly disabled autofocus autocomplete="type" />
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_type" :value="__('Jenis Produk')" />
                        <x-text-input id="product_type" class="block mt-1 w-full" product_type="text"
                            name="product_type" :value="old('product_type', $item->product->product_type)" readonly disabled autofocus
                            autocomplete="product_type" />
                        <x-input-error :messages="$errors->get('product_type')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="stock" :value="__('Stok')" />
                        <x-text-input id="stock" class="block mt-1 w-full" type="text" name="stock"
                            :value="old('stock', $item->product->stock)" readonly disabled autofocus autocomplete="stock" />
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="jumlah_kebutuhan" :value="__('Jumlah Kebutuhan')" />
                        <x-text-input id="jumlah_kebutuhan" class="block mt-1 w-full" type="text"
                            name="jumlah_kebutuhan" :value="old('jumlah_kebutuhan', $item->jumlah_kebutuhan)" autofocus autocomplete="jumlah_kebutuhan" />
                        <x-input-error :messages="$errors->get('jumlah_kebutuhan')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_unit" :value="__('Satuan')" />
                        <x-text-input id="product_unit" class="block mt-1 w-full" type="text" name="product_unit"
                            :value="old('product_unit', $item->product->product_unit)" readonly disabled autofocus autocomplete="product_unit" />
                        <x-input-error :messages="$errors->get('product_unit')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('detail-perencanaan', ['id' => $item->rencana->id]) }}"
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Kembali
                        </a>
                        <x-primary-button class="ms-4">
                            {{ __('Simpan Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>
