<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('data-harga') }}">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('◀️ Tambah Data Harga Barang') }}
            </h2>
        </a>
    </x-slot>
    @if (session('success') || session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show"
            class="fixed top-20 right-10 p-3 rounded-md shadow-md transition-all duration-500"
            :class="{ 'bg-green-500 text-white': '{{ session('success') }}', 'bg-red-500 text-white': '{{ session('error') }}' }"
            x-transition:enter="transform ease-out duration-500" x-transition:enter-start="opacity-0 -translate-y-5"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transform ease-in duration-500"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-5">
            {{ session('success') ?? session('error') }}
        </div>
    @endif
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
                <form method="POST" action="{{ route('add-harga') }}" x-data="{
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
                        : '' }}',
                    products: [],
                    selectedProduct: null,
                    purchasePrice: 0,
                    async fetchProducts() {
                        if (this.location) {
                            const response = await fetch(`/assets?location=${this.location}`);
                            this.products = await response.json();
                        } else {
                            this.products = [];
                        }
                    }
                }" x-init="fetchProducts()">
                    @csrf
                    <div>
                        <x-input-label for="location" :value="__('Lokasi / Program Studi')" />
                        @if ($prodi != null)
                            <x-text-input id="location" class="block mt-1 w-full" location="text" name="location"
                                :value="old('location', $prodi)" autofocus autocomplete="location" readonly />
                        @else
                            <select id="location" name="location"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                                x-model="location" @change="fetchProducts()" required>
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
                        <x-input-label for="product" :value="__('Pilih Produk')" />
                        <select name="product_code"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                            required x-model="selectedProduct" x-ref="productSelect"
                            @change="
                                let selected = products.find(p => p.product_code === selectedProduct);
                                if (selected && selected.latest_plans) {
                                    purchasePrice = selected.latest_plans.purchase_price || 0;
                                } else {
                                    purchasePrice = 0;
                                }
                            ">
                            <option value="">Select Product</option>
                            <template x-for="product in products" :key="product.product_code">
                                <option :value="product.product_code"
                                    x-text="`${product.product_name} (${product.product_code}) ${product.product_detail ? '(' + product.product_detail + ')' : ''} ${product.merk ? '(' + product.merk + ')' : ''} ${product.product_type ? '(' + product.product_type + ')' : ''} (${product.product_unit})`">
                                </option>
                            </template>
                        </select>
                    </div>
                    <div class="mt-4">
                        <x-input-label for="price_type" :value="__('Jenis Harga')" />
                        <select id="price_type" name="price_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                            required>
                            <option value="">-- Pilih Jenis Harga --</option>
                            <option value="unit">Per Unit</option>
                            <option value="rental">Sewa Perjam</option>
                        </select>
                        <x-input-error :messages="$errors->get('price_type')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="purchase_price" :value="__('Harga Beli')" />
                        <div class="relative w-full">
                            <span
                                class="absolute left-3 top-1/2 transform text-sm -translate-y-1/2 text-gray-600">Rp.</span>
                            <x-text-input id="purchase_price" class="block mt-1 w-full pl-10" type="text"
                                name="purchase_price" :value="old('purchase_price', 0)" autofocus autocomplete="purchase_price"
                                x-model="purchasePrice" />
                        </div>
                        <x-input-error :messages="$errors->get('purchase_price')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="price" :value="__('Harga Sewa/Pakai')" />
                        <div class="relative w-full">
                            <span
                                class="absolute left-3 top-1/2 transform text-sm -translate-y-1/2 text-gray-600">Rp.</span>
                            <x-text-input id="price" class="block mt-1 w-full pl-10" type="text" name="price"
                                :value="old('price', 0)" autofocus autocomplete="price" />
                        </div>
                        <x-input-error :messages="$errors->get('price')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="effective_date" :value="__('Tanggal Berlaku')" />
                        <x-text-input id="effective_date" class="block mt-1 w-full" type="date" name="effective_date"
                            :value="old('effective_date', 0)" autofocus autocomplete="effective_date" />
                        <x-input-error :messages="$errors->get('effective_date')" class="mt-2" />
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('data-harga') }}"
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
