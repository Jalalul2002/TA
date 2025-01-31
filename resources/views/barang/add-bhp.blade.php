<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Aset BHP') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 grid lg:grid-cols-2 xl:grid-cols-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('add-aset-bhp') }}" x-data="{ location: '', initialCode: '' }" x-init="$watch('location', value => {
                    const locationToCodeMap = {
                        'Matematika': '701',
                        'Biologi': '702',
                        'Fisika': '703',
                        'Kimia': '704',
                        'Teknik Informatika': '705',
                        'Agroteknologi': '706',
                        'Teknik Elektro': '707',
                    };
                
                    initialCode = locationToCodeMap[value] || '';
                })">
                    @csrf
                    <div>
                        <x-input-label for="location" :value="__('Lokasi / Program Studi')" />
                        @if ($prodi != null)
                            <x-text-input id="location" class="block mt-1 w-full" location="text" name="location"
                                :value="old('location', $prodi)" autofocus autocomplete="location" disabled />
                        @else
                            <select id="location" name="location" class="block mt-1 w-full text-sm" x-model="location"
                                required>
                                <option value="">-- Pilih Program Studi --</option>
                                <option value="Matematika">701-Matematika</option>
                                <option value="Biologi">702-Biologi</option>
                                <option value="Fisika">703-Fisika</option>
                                <option value="Kimia">704-Kimia</option>
                                <option value="Teknik Informatika">705-Teknik Informatika</option>
                                <option value="Agroteknologi">706-Agroteknologi</option>
                                <option value="Teknik Elektro">707-Teknik Elektro</option>
                            </select>
                        @endif
                        <x-input-error :messages="$errors->get('location')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_code" :value="__('Kode Produk')" />
                        <div class="flex space-x-2 items-center">
                            <x-text-input id="initial_code" class="block mt-1 w-[62px]" type="text"
                                name="initial_code" x-model="initialCode" required autofocus autocomplete="initial_code"
                                readonly />
                            <p>-</p>
                            <x-text-input id="product_code" class="block mt-1 w-full" type="text" name="product_code"
                                :value="old('product_code')" required autofocus autocomplete="product_code" />
                        </div>
                        <x-input-error :messages="$errors->get('product_code')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_name" :value="__('Nama Produk')" />
                        <x-text-input id="product_name" class="block mt-1 w-full" type="text" name="product_name"
                            :value="old('product_name')" required autofocus autocomplete="product_name" />
                        <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="formula" :value="__('Formula')" />
                        <x-text-input id="formula" class="block mt-1 w-full" type="text" name="formula"
                            :value="old('formula')" autofocus autocomplete="formula" />
                        <x-input-error :messages="$errors->get('formula')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="merk" :value="__('Merk')" />
                        <x-text-input id="merk" class="block mt-1 w-full" type="text" name="merk"
                            :value="old('merk')" autofocus autocomplete="merk" />
                        <x-input-error :messages="$errors->get('merk')" class="mt-2" />
                    </div>
                    <div class="mt-4 hidden">
                        <x-input-label for="type" :value="__('Tipe')" />
                        <x-text-input id="type" class="block mt-1 w-full" type="text" name="type"
                            :value="old('type', 'bhp')" autofocus autocomplete="type" />
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_type" :value="__('Jenis Produk')" />
                        <select id="product_type" name="product_type" class="block mt-1 w-full text-sm" required>
                            <option value="">-- Pilih Jenis Produk --</option>
                            <option value="Cairan">Cairan</option>
                            <option value="Padatan">Padatan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <x-input-error :messages="$errors->get('product_type')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="location_detail" :value="__('Lokasi Penyimpanan')" />
                        <x-text-input id="location_detail" class="block mt-1 w-full" type="text"
                            name="location_detail" :value="old('location_detail')" autofocus autocomplete="location_detail" />
                        <x-input-error :messages="$errors->get('location_detail')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="stock" :value="__('Stok')" />
                        <x-text-input id="stock" class="block mt-1 w-full" type="text" name="stock"
                            :value="old('stock', 0)" autofocus autocomplete="stock" />
                        <x-input-error :messages="$errors->get('stock')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_unit" :value="__('Satuan')" />
                        <x-text-input id="product_unit" class="block mt-1 w-full" type="text" name="product_unit"
                            :value="old('product_unit')" required autofocus autocomplete="product_unit" />
                        <x-input-error :messages="$errors->get('product_unit')" class="mt-2" />
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
