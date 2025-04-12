<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('peminjaman') }}">
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                {{ __('◀️ Tambah Data Peminjaman Barang') }}
            </h2>
        </a>
    </x-slot>
    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 xl:grid xl:grid-cols-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 xl:col-span-2" x-data="{
                location: '{{ Auth::user()->prodi }}',
                type: 'inventaris',
                purpose: '',
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
                <template x-if="purpose === 'praktikum'">
                    <div
                        class="mb-3 flex items-center gap-2 bg-green-100 border border-green-300 text-green-800 text-sm font-semibold px-4 py-2 rounded-lg shadow-sm">
                        <svg class="size-5 fill-green-600" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                            data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                            <path
                                d="M12,24c-1.626,0-3.16-.714-4.208-1.959-1.54,.177-3.127-.406-4.277-1.555-1.149-1.15-1.729-2.74-1.59-4.362-1.211-.964-1.925-2.498-1.925-4.124s.714-3.16,1.96-4.208c-.175-1.537,.405-3.127,1.555-4.277,1.15-1.151,2.737-1.73,4.361-1.59,.964-1.21,2.498-1.925,4.124-1.925s3.16,.714,4.208,1.959c1.542-.176,3.127,.406,4.277,1.555,1.149,1.15,1.729,2.74,1.59,4.362,1.211,.964,1.925,2.498,1.925,4.124s-.714,3.16-1.96,4.208c.175,1.537-.405,3.127-1.555,4.277-1.151,1.15-2.741,1.729-4.361,1.59-.964,1.21-2.498,1.925-4.124,1.925Zm-4.127-3.924c.561,0,1.081,.241,1.448,.676,.668,.793,1.644,1.248,2.679,1.248s2.011-.455,2.679-1.248c.403-.479,.99-.721,1.616-.67,1.034,.087,2.044-.28,2.776-1.012,.731-.731,1.1-1.743,1.012-2.776-.054-.624,.19-1.213,.67-1.617,.792-.667,1.247-1.644,1.247-2.678s-.455-2.011-1.247-2.678c-.479-.403-.724-.993-.67-1.617,.088-1.033-.28-2.045-1.012-2.776s-1.748-1.096-2.775-1.012c-.626,.057-1.214-.191-1.617-.669-.668-.793-1.644-1.248-2.679-1.248s-2.011,.455-2.679,1.248c-.404,.479-.993,.719-1.616,.67-1.039-.09-2.044,.28-2.776,1.012-.731,.731-1.1,1.743-1.012,2.776,.054,.624-.19,1.213-.67,1.617-.792,.667-1.247,1.644-1.247,2.678s.455,2.011,1.247,2.678c.479,.403,.724,.993,.67,1.617-.088,1.033,.28,2.045,1.012,2.776,.732,.732,1.753,1.098,2.775,1.012,.057-.005,.113-.007,.169-.007Zm1.127-12.076c-.552,0-1,.448-1,1s.448,1,1,1,1-.448,1-1-.448-1-1-1Zm6,6c-.552,0-1,.448-1,1s.448,1,1,1,1-.448,1-1-.448-1-1-1Zm-4.168,1.555l4-6c.307-.459,.183-1.081-.277-1.387-.461-.308-1.081-.182-1.387,.277l-4,6c-.307,.459-.183,1.081,.277,1.387,.171,.114,.363,.168,.554,.168,.323,0,.641-.156,.833-.445Z" />
                        </svg>
                        <span>Potongan 100% untuk keperluan <strong>Praktikum</strong>! 🎉</span>
                    </div>
                </template>
                <form method="POST" action="{{ route('add-peminjaman') }}">
                    @csrf
                    <div>
                        <x-input-label for="purpose" :value="__('Keperluan *')" />
                        <select id="purpose" name="purpose" x-model="purpose"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                            required>
                            <option value="">-- Pilih Keperluan --</option>
                            <option value="praktikum">Praktikum</option>
                            <option value="penelitian">Penelitian</option>
                        </select>
                        <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                    </div>
                    <!-- Detail Pengguna -->
                    <div class="mt-4">
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
                            :value="old('telp')" required autofocus placeholder="08xxxx" autocomplete="telp"
                            oninput="this.value = this.value.replace(/[^0-9+]/g, '')" />
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
                    <!-- Items -->
                    <div class="mt-4">
                        <p class="block text-sm font-medium text-gray-900">Daftar barang
                            digunakan: <span class="text-red-500">*Jika barang tidak ada berarti
                                <strong>kosong/habis</strong></span></p>
                        <div class="mt-2 text-sm" id="items" x-data="formHandler()">
                            <template x-if="purpose === 'praktikum'">
                                <div
                                    class="mb-3 flex items-center gap-2 bg-green-100 border border-green-300 text-green-800 text-sm font-semibold px-4 py-2 rounded-lg shadow-sm">
                                    <svg class="size-5 fill-green-600" xmlns="http://www.w3.org/2000/svg"
                                        id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512"
                                        height="512">
                                        <path
                                            d="M12,24c-1.626,0-3.16-.714-4.208-1.959-1.54,.177-3.127-.406-4.277-1.555-1.149-1.15-1.729-2.74-1.59-4.362-1.211-.964-1.925-2.498-1.925-4.124s.714-3.16,1.96-4.208c-.175-1.537,.405-3.127,1.555-4.277,1.15-1.151,2.737-1.73,4.361-1.59,.964-1.21,2.498-1.925,4.124-1.925s3.16,.714,4.208,1.959c1.542-.176,3.127,.406,4.277,1.555,1.149,1.15,1.729,2.74,1.59,4.362,1.211,.964,1.925,2.498,1.925,4.124s-.714,3.16-1.96,4.208c.175,1.537-.405,3.127-1.555,4.277-1.151,1.15-2.741,1.729-4.361,1.59-.964,1.21-2.498,1.925-4.124,1.925Zm-4.127-3.924c.561,0,1.081,.241,1.448,.676,.668,.793,1.644,1.248,2.679,1.248s2.011-.455,2.679-1.248c.403-.479,.99-.721,1.616-.67,1.034,.087,2.044-.28,2.776-1.012,.731-.731,1.1-1.743,1.012-2.776-.054-.624,.19-1.213,.67-1.617,.792-.667,1.247-1.644,1.247-2.678s-.455-2.011-1.247-2.678c-.479-.403-.724-.993-.67-1.617,.088-1.033-.28-2.045-1.012-2.776s-1.748-1.096-2.775-1.012c-.626,.057-1.214-.191-1.617-.669-.668-.793-1.644-1.248-2.679-1.248s-2.011,.455-2.679,1.248c-.404,.479-.993,.719-1.616,.67-1.039-.09-2.044,.28-2.776,1.012-.731,.731-1.1,1.743-1.012,2.776,.054,.624-.19,1.213-.67,1.617-.792,.667-1.247,1.644-1.247,2.678s.455,2.011,1.247,2.678c.479,.403,.724,.993,.67,1.617-.088,1.033,.28,2.045,1.012,2.776,.732,.732,1.753,1.098,2.775,1.012,.057-.005,.113-.007,.169-.007Zm1.127-12.076c-.552,0-1,.448-1,1s.448,1,1,1,1-.448,1-1-.448-1-1-1Zm6,6c-.552,0-1,.448-1,1s.448,1,1,1,1-.448,1-1-.448-1-1-1Zm-4.168,1.555l4-6c.307-.459,.183-1.081-.277-1.387-.461-.308-1.081-.182-1.387,.277l-4,6c-.307,.459-.183,1.081,.277,1.387,.171,.114,.363,.168,.554,.168,.323,0,.641-.156,.833-.445Z" />
                                    </svg>
                                    <span>Potongan 100% untuk keperluan <strong>Praktikum</strong>! 🎉</span>
                                </div>
                            </template>
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex gap-2 border mb-2 border-gray-300 rounded-lg p-2">
                                    <div class="flex flex-col text-sm text-gray-900 gap-2">
                                        <select name="items[][product_code]"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                                            :id="'productSelect' + index" @change="updateStock(index)" required>
                                            <option value="">Select Product</option>
                                            <template x-for="product in products" :key="product.product_code">
                                                <option :value="product.product_code" :data-stock="product.stock"
                                                    :data-satuan="product.product_unit"
                                                    :data-type="product.latest_price ? product.latest_price.price_type : ''"
                                                    :data-price="product.latest_price ? product.latest_price.price : 0"
                                                    x-text="`${product.product_name} (${product.product_code}) ${product.product_detail ? '(' + product.product_detail + ')' : ''} ${product.merk ? '(' + product.merk + ')' : ''} ${product.product_type ? '(' + product.product_type + ')' : ''}`">
                                                </option>
                                            </template>
                                        </select>
                                        <div class="flex flex-row gap-x-2 justify-between">
                                            <div class="w-full flex">
                                                <input type="number" name="items[][stock]" placeholder="Stok"
                                                    class="w-2/3 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 mt-1"
                                                    :id="'productStock' + index" x-model="items[index].stock" required
                                                    step="any" readonly>
                                                <input type="text" name="items[][product_unit]"
                                                    placeholder="Satuan"
                                                    class="w-1/3 bg-gray-50 border-transparent text-gray-900 text-sm rounded-lg focus:ring-transparent focus:border-transparent block mt-1"
                                                    :id="'productUnit' + index" x-model="items[index].product_unit"
                                                    readonly>
                                            </div>
                                            <div class="relative w-full">
                                                <span
                                                    class="absolute left-3 top-1/2 transform text-sm -translate-y-1/2 text-gray-600">Rp.</span>
                                                <input type="number" name="items[][price]" placeholder="Harga"
                                                    class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full block p-2.5 mt-1"
                                                    :id="'productPrice' + index" required readonly
                                                    x-model="items[index].price">
                                            </div>
                                            <input type="number" name="items[][quantity]" placeholder="Jumlah"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 mt-1"
                                                min="0" :max="items[index].stock"
                                                x-model="items[index].quantity" step="any"
                                                @input="calculateTotalPrice(index)" required>
                                            <input type="number" name="items[][rental]" placeholder="Durasi"
                                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 mt-1"
                                                min="0" x-model="items[index].duration" step="any"
                                                :max="items[index].type === 'unit' ? 1 : null"
                                                :readonly="items[index].type === 'unit'"
                                                x-show="items[index].type === 'rental'"
                                                @input="calculateTotalPrice(index)" required>
                                            <input type="text" name="items[][typeUnit]" placeholder="Satuan"
                                                class="w-1/3 bg-gray-50 border-transparent text-gray-900 text-sm rounded-lg focus:ring-transparent focus:border-transparent block p-2.5 mt-1"
                                                :id="'typeUnit' + index" x-model="items[index].typeUnit" readonly>
                                            <div class="relative w-full">
                                                <span
                                                    class="absolute left-3 top-1/2 transform text-sm -translate-y-1/2 text-gray-600">Rp.</span>
                                                <input type="number" name="items[][total_price]"
                                                    placeholder="Total Harga"
                                                    class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full block p-2.5 mt-1"
                                                    min="0" required x-model="items[index].total_price"
                                                    @input="updateFromTotalPrice(index)">
                                            </div>
                                        </div>
                                        <div class="flex flex-row gap-x-2 justify-between w-full">
                                            <input type="text" name="items[][notes]" placeholder="Catatan"
                                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 mt-1">
                                        </div>
                                    </div>
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
                            </template>
                            <button type="button" @click="items.push({product_code: ''})"
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
                const typeUnit = document.getElementById('typeUnit' + index);
                const selectedOption = productSelect.options[productSelect.selectedIndex];

                const stock = selectedOption.getAttribute('data-stock');
                const satuan = selectedOption.getAttribute('data-satuan');
                const type = selectedOption.getAttribute('data-type');

                this.items[index].type = type;
                this.items[index].stock = stock ? parseInt(stock) : 0;
                this.items[index].price = selectedOption.getAttribute('data-price') || 0;
                this.items[index].product_unit = satuan;

                if (type === 'unit') {
                    typeUnit.value = satuan ? satuan : '';
                    this.items[index].typeUnit = satuan ? satuan : '';
                    this.items[index].duration = 1; // durasi otomatis 1
                } else if (type === 'rental') {
                    typeUnit.value = 'per jam';
                    this.items[index].typeUnit = 'per jam';
                    this.items[index].duration = ''; // durasi otomatis 1
                }

                this.calculateTotalPrice(index);

                // Pastikan quantity tidak lebih dari stock
                if (type === 'unit' && this.items[index].quantity > this.items[index].stock) {
                    this.items[index].quantity = this.items[index].stock;
                }
            },

            calculateTotalPrice(index) {
                this.items[index].total_price = this.items[index].price * this.items[index].quantity * this.items[index]
                    .duration;
            },
            updateFromTotalPrice(index) {
                if (this.items[index].price > 0) {
                    this.items[index].quantity = this.items[index].total_price / this.items[index].price;
                }
            }
        }
    }
</script>
