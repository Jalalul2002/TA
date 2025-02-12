<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('data-bhp') }}">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('◀️ Tambah Data Aset Barang Habis Pakai') }}
            </h2>
        </a>
    </x-slot>

    <div class="py-6">
        <div x-data="{ showModal: @json(session('duplicate_error') ? true : false) }">
            <!-- MODAL -->
            <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
                    <h2 class="text-lg font-semibold text-red-600">❌ Gagal Menambahkan Data</h2>
                    <p class="mt-2 text-gray-600">{{ session('duplicate_error') }}</p>
                    <button @click="showModal = false"
                        class="mt-4 bg-red-500 text-white px-4 py-2 rounded">Tutup</button>
                </div>
            </div>
        </div>
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 grid lg:grid-cols-2 xl:grid-cols-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('add-aset-bhp') }}" x-data="{
                    location: '{{ $prodi ?? '' }}',
                    initialCode: '{{ $prodi
                        ? [
                                'Umum' => '700',
                                'Matematika' => '701',
                                'Biologi' => '702',
                                'Fisika' => '703',
                                'Kimia' => '704',
                                'Teknik Informatika' => '705',
                                'Agroteknologi' => '706',
                                'Teknik Elektro' => '707',
                            ][$prodi ?? ''] ?? ''
                        : '' }}'
                }"
                    x-init="$watch('location', value => {
                        const locationToCodeMap = {
                            'Umum': '700',
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
                                :value="old('location', $prodi)" autofocus autocomplete="location" readonly />
                        @else
                            <select id="location" name="location"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                                x-model="location" required>
                                <option value="">-- Pilih Program Studi --</option>
                                <option value="Umum">700-Umum</option>
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
                        <x-input-label for="product_detail" :value="__('Keterangan/Formula (Optional)')" />
                        <x-text-input id="product_detail" class="block mt-1 w-full" type="text" name="product_detail"
                            :value="old('product_detail')" autofocus autocomplete="product_detail" />
                        <x-input-error :messages="$errors->get('product_detail')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="merk" :value="__('Merk (Optional)')" />
                        <x-text-input id="merk" class="block mt-1 w-full" type="text" name="merk"
                            :value="old('merk')" autofocus autocomplete="merk" />
                        <x-input-error :messages="$errors->get('merk')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="type" :value="__('Tipe')" />
                        <x-text-input id="type" class="block mt-1 w-full" type="text" name="type"
                            :value="old('type', 'bhp')" autofocus readonly autocomplete="type" />
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="product_type" :value="__('Jenis Produk')" />
                        <select id="product_type" name="product_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                            required>
                            <option value="">-- Pilih Jenis Produk --</option>
                            <option value="Cairan">Cairan</option>
                            <option value="Padatan">Padatan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <x-input-error :messages="$errors->get('product_type')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="location_detail" :value="__('Lokasi Penyimpanan (Optional)')" />
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
                        @php
                            $units = [
                                'bks',
                                'botol',
                                'cm',
                                'g',
                                'pcs',
                                'unit',
                                'karung',
                                'lembar',                                
                                'ml',
                                'pak',
                                'paket',
                                'petri',
                                'rol',
                                'set',
                            ];
                        @endphp
                        <select id="product_unit" name="product_unit"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                            required>
                            @foreach ($units as $unit)
                                <option value="{{ $unit }}">{{ $unit }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('product_unit')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('data-bhp') }}"
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
