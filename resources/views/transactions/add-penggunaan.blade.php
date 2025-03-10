<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('penggunaan') }}">
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                {{ __('◀️ Tambah Data Penggunaan BHP') }}
            </h2>
        </a>
    </x-slot>
    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 xl:grid xl:grid-cols-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 xl:col-span-2" x-data="{
                location: '{{ Auth::user()->prodi }}',
                type: 'bhp',
                products: [],
                async fetchProducts() {
                    if (this.location) {
                        const response = await fetch(`/assets?type=${this.type}&location=${this.location}&stock_filter=1`);
                        this.products = await response.json();
                    } else {
                        this.products = [];
                    }
                }
            }"
                x-init="fetchProducts()">
                <form method="POST" action="{{ route('add-penggunaan') }}">
                    @csrf
                    <!-- Detail Pengguna -->
                    <div>
                        <x-input-label for="user_id" :value="__('ID Pengguna/NIM/NIP')" />
                        <x-text-input id="user_id" class="block mt-1 w-full" type="text" name="user_id"
                            :value="old('user_id')" required autofocus placeholder="123456xxx" autocomplete="user_id" />
                        <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="name" :value="__('Nama Pengguna')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                            :value="old('name')" required autofocus placeholder="Masukan Nama Pengguna/Peminjam"
                            autocomplete="name" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="prodi" :value="__('Prodi Pengguna/Peminjam')" />
                        <select id="prodi" name="prodi"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                            required>
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
                        <x-input-label for="telp" :value="__('Nomor Telepon')" />
                        <x-text-input id="telp" class="block mt-1 w-full" type="text" name="telp"
                            :value="old('telp')" required autofocus placeholder="08xxxx" autocomplete="telp" />
                        <x-input-error :messages="$errors->get('telp')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="detail" :value="__('Keterangan Penggunaan')" />
                        <x-text-input id="detail" class="block mt-1 w-full" type="text" name="detail"
                            :value="old('detail')" required autofocus placeholder="Masukan keterangan penggunaan"
                            autocomplete="detail" />
                        <x-input-error :messages="$errors->get('detail')" class="mt-2" />
                    </div>
                    <div class="mt-4">
                        <x-input-label for="location" :value="__('Lokasi / Program Studi')" />
                        @if ($prodi != null)
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location"
                                :value="old('location', $prodi)" autofocus autocomplete="location" readonly />
                        @else
                            <select id="location" name="location"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                                required x-model="location" @change="fetchProducts()">
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
                    <!-- Items -->
                    <div class="mt-4">
                        <p class="block text-sm font-medium text-gray-900">Daftar barang
                            digunakan: <span class="text-red-500">*Jika barang tidak ada berarti <strong>kosong/habis</strong></span></p>
                        <div class="mt-2 text-sm" id="items" x-data="formHandler()">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex flex-col lg:flex-row mb-2 text-sm text-gray-900 gap-x-2">
                                    <select name="items[][product_code]"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                                        :id="'productSelect' + index" @change="updateStock(index)" required>
                                        <option value="">Select Product</option>
                                        <template x-for="product in products" :key="product.product_code">
                                            <option :value="product.product_code" :data-stock="product.stock"
                                                :data-satuan="product.product_unit"
                                                x-text="`${product.product_name} (${product.product_code}) ${product.product_detail ? '(' + product.product_detail + ')' : ''} ${product.merk ? '(' + product.merk + ')' : ''} ${product.product_type ? '(' + product.product_type + ')' : ''}`">
                                            </option>
                                        </template>
                                    </select>
                                    <div class="flex flex-row gap-x-2 justify-between">
                                        <input type="number" name="items[][stock]" placeholder="Stok"
                                            class="w-2/3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 mt-1"
                                            :id="'productStock' + index" required readonly>
                                        <input type="number" name="items[][quantity]" placeholder="Jumlah Kebutuhan"
                                            class="w-2/3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 mt-1"
                                            min="0" required>
                                        <input type="text" name="items[][product_unit]" placeholder="Satuan"
                                            class="w-2/3 bg-gray-50 border-transparent text-gray-900 text-sm rounded-lg focus:ring-transparent focus:border-transparent block p-2.5 mt-1"
                                            :id="'productUnit' + index" readonly>
                                        <button type="button" @click="items.splice(index, 1)"
                                            class="bg-uinRed border hover:bg-red-700 border-uinRed text-white text-sm font-semibold rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 mt-1"><svg
                                                class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                id="Outline" viewBox="0 0 24 24" width="512" height="512">
                                                <path
                                                    d="M21,4H17.9A5.009,5.009,0,0,0,13,0H11A5.009,5.009,0,0,0,6.1,4H3A1,1,0,0,0,3,6H4V19a5.006,5.006,0,0,0,5,5h6a5.006,5.006,0,0,0,5-5V6h1a1,1,0,0,0,0-2ZM11,2h2a3.006,3.006,0,0,1,2.829,2H8.171A3.006,3.006,0,0,1,11,2Zm7,17a3,3,0,0,1-3,3H9a3,3,0,0,1-3-3V6H18Z" />
                                                <path d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18Z" />
                                                <path d="M14,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z" />
                                            </svg></button>
                                    </div>
                                </div>
                            </template>
                            <button type="button" @click="items.push({product_code: '', stock: 0, quantity: 0})"
                                class="bg-uinBlue hover:bg-uinNavy text-white px-4 py-2 rounded-md flex flex-row items-center font-semibold">
                                <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 448 512">
                                    <path
                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                </svg>
                                <span>Tambah Barang </span></button>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('penggunaan') }}"
                            class="px-4 py-2 bg-uinRed border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Kembali</a>
                        <x-primary-button class="ms-2">
                            {{ __('Simpan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function formHandler() {
        return {
            items: [],
            updateStock(index) {
                const productSelect = document.getElementById('productSelect' + index);
                const productStock = document.getElementById('productStock' + index);
                const productUnit = document.getElementById('productUnit' + index);
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const stock = selectedOption.getAttribute('data-stock');
                const satuan = selectedOption.getAttribute('data-satuan');
                productStock.value = stock ? stock : '';
                productUnit.value = satuan ? satuan : '';
            }
        }
    }
</script>
