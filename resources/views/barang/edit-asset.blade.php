<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Aset') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('update-aset', $assetLab->product_code) }}">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="location" :value="__('Lokasi / Program Studi')" />
                        <x-text-input id="location" class="block mt-1 w-full" location="text" name="location"
                            :value="old('location', $assetLab->location)" autofocus autocomplete="location" readonly />
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_code" :value="__('Kode Produk')" />
                        <x-text-input id="product_code" class="block mt-1 w-full" type="text" name="product_code"
                            :value="old('product_code', $assetLab->product_code)" readonly required autofocus autocomplete="product_code" />
                        <x-input-error :messages="$errors->get('product_code')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_name" :value="__('Nama Produk')" />
                        <x-text-input id="product_name" class="block mt-1 w-full" type="text" name="product_name"
                            :value="old('product_name', $assetLab->product_name)" required autofocus autocomplete="product_name" />
                        <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="formula" :value="__('Formula')" />
                        <x-text-input id="formula" class="block mt-1 w-full" type="text" name="formula"
                            :value="old('formula', $assetLab->formula)" autofocus autocomplete="formula" />
                        <x-input-error :messages="$errors->get('formula')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="merk" :value="__('Merk')" />
                        <x-text-input id="merk" class="block mt-1 w-full" type="text" name="merk"
                            :value="old('merk', $assetLab->merk)" autofocus autocomplete="merk" />
                        <x-input-error :messages="$errors->get('merk')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="type" :value="__('Tipe')" />
                        <x-text-input id="type" class="block mt-1 w-full" type="text" name="type"
                            :value="old('type', $assetLab->type)" readonly autofocus autocomplete="type" />
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_type" :value="__('Jenis Produk')" />
                        <select id="product_type" name="product_type" class="block mt-1 w-full" required>
                            <option value="">-- Pilih Jenis Produk --</option>
                            <option value="Cairan"
                                {{ old('product_type', $assetLab->product_type) == 'Cairan' ? 'selected' : '' }}>Cairan
                            </option>
                            <option value="Padatan"
                                {{ old('product_type', $assetLab->product_type) == 'Padatan' ? 'selected' : '' }}>
                                Padatan</option>
                            <option value="Lainnya"
                                {{ old('product_type', $assetLab->product_type) == 'Lainnya' ? 'selected' : '' }}>
                                Lainnya</option>
                        </select>
                        <x-input-error :messages="$errors->get('product_type')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="location_detail" :value="__('Lokasi Penyimpanan')" />
                        <x-text-input id="location_detail" class="block mt-1 w-full" type="text"
                            name="location_detail" :value="old('location_detail', $assetLab->location_detail)" autofocus autocomplete="location_detail" />
                        <x-input-error :messages="$errors->get('location_detail')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="stock" :value="__('Stok')" />
                        <x-text-input id="stock" class="block mt-1 w-full" type="text" name="stock"
                            :value="old('stock', $assetLab->stock)" autofocus autocomplete="stock" />
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_unit" :value="__('Satuan')" />
                        <x-text-input id="product_unit" class="block mt-1 w-full" type="text" name="product_unit"
                            :value="old('product_unit', $assetLab->product_unit)" required autofocus autocomplete="product_unit" />
                        <x-input-error :messages="$errors->get('product_unit')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ms-4">
                            {{ __('Simpan Data') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
</x-app-layout>
