<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('mahasiswa.pengajuan') }}">
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                {{ __('◀️ Tambah Pegajuan') }}
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
    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 xl:grid xl:grid-cols-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 xl:col-span-2" x-data="{
                location: '',
                type: '',
                purpose: '',
                products: [],
                approverList: window.approverList,
                async fetchProducts() {
                    if (this.type) {
                        const response = await fetch(`/assets?type=${this.type}`);
                        this.products = await response.json();
                    } else {
                        this.products = [];
                    }
                }
            }"
                x-init="$watch('purpose', value => {
                    if (value === 'P02') {
                        type = 'inventaris';
                        fetchProducts();
                    } else if (value === 'P03') {
                        type = 'bhp';
                        fetchProducts();
                    } else {
                        type = '';
                        products = [];
                    }
                });">
                <form method="POST" action="{{ route('mahasiswa.add') }}">
                    @csrf
                    <div>
                        <x-input-label for="purpose" :value="__('Keperluan *')" />
                        <select id="purpose" name="purpose" x-model="purpose"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                            required>
                            <option value="">-- Pilih Keperluan --</option>
                            <option value="P01">Peminjaman Ruang Lab</option>
                            <option value="P02">Peminjaman Alat Lab</option>
                            <option value="P03">Permintaan Bahan/Zat</option>
                            <option value="P04">Izin Pemakaian diluar Jam Kerja</option>
                        </select>
                        <x-input-error :messages="$errors->get('purpose')" class="mt-2" />
                    </div>

                    <div x-show="purpose != ''" x-transition>
                        <!-- Detail Pengguna -->
                        <input type="text" name="mahasiswa_id" value="{{ auth()->user()->id }}" readonly
                            class="hidden" />
                        <div class="mt-4">
                            <x-input-label for="nim" :value="__('NIM')" />
                            <x-text-input id="nim" class="block mt-1 w-full" type="text" name="nim"
                                :value="auth()->user()->username" required autofocus placeholder="123456xxx" autocomplete="nim"
                                readonly />
                            <x-input-error :messages="$errors->get('nim')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Nama')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="auth()->user()->name" required autofocus placeholder="123456xxx" autocomplete="name"
                                readonly />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="prodi" :value="__('Program Studi')" />
                            <x-text-input id="prodi" class="block mt-1 w-full" type="text" name="prodi"
                                :value="auth()->user()->prodi" required autofocus placeholder="123456xxx" autocomplete="prodi"
                                readonly />
                            <x-input-error :messages="$errors->get('prodi')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                :value="auth()->user()->email" required autofocus placeholder="Email" autocomplete="email" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="telp" :value="__('Telpon')" />
                            <x-text-input id="telp" class="block mt-1 w-full" type="text" name="telp"
                                :value="old('telp')" required autofocus placeholder="123456xxx" autocomplete="telp" />
                            <x-input-error :messages="$errors->get('telp')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="lab_code" :value="__('Lab')" />
                            <select
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1">
                                <option value="">
                                    -- Pilih Lab --
                                </option>
                                @foreach ($dataLabs as $data)
                                    <option value="{{ $data->id }}">
                                        {{ $data->name }}
                                    </option>
                                @endforeach
                            </select><x-input-error :messages="$errors->get('lab_code')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="start_date" :value="__('Tanggal Mulai')" />
                            <input id="start_date" type="date"
                                class="border border-gray-300 text-gray-900 rounded-lg text-sm p-2 w-full">
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="end_date" :value="__('Tanggal Berakhir')" />
                            <input id="end_date" type="date"
                                class="border border-gray-300 text-gray-900 rounded-lg text-sm p-2 w-full">
                            <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                        </div>
                    </div>
                    <div class="mt-4" x-show="purpose == 'P02' || purpose == 'P03'" x-transition>
                        <!-- Items -->
                        <div class="mt-4">
                            <p class="block text-sm font-medium text-gray-900">Daftar Items:</p>
                            <div class="mt-2 text-sm pl-2" id="items" x-data="formHandler()">
                                <h3 class="text-sm font-semibold">Tambah Item</h3>
                                <template x-for="(item, index) in items" :key="index">
                                    <div class="flex mb-2 text-sm text-gray-900 gap-x-2">
                                        <div class="w-full">
                                            <select :name="`items[${index}][product_code]`"
                                                class="w-full bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 mt-1"
                                                :id="'productSelect' + index" @change="updateStock(index)" required>
                                                <option value="">Select Product</option>
                                                <template x-for="product in products" :key="product.product_code">
                                                    <option :value="product.product_code"
                                                        :data-satuan="product.product_unit"
                                                        x-text="`${product.product_name} (${product.product_code}) ${product.product_detail ? '(' + product.product_detail + ')' : ''} ${product.merk ? '(' + product.merk + ')' : ''} ${product.product_type ? '(' + product.product_type + ')' : ''}`">
                                                    </option>
                                                </template>
                                            </select>
                                            <div class="flex flex-row gap-x-2 justify-between w-full">
                                                <input type="number" :name="`items[${index}][quantity]`"
                                                    placeholder="Jumlah" step="any"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full block p-2.5 mt-1"
                                                    min="0" required x-model="items[index].quantity">
                                                <input type="text" :name="`items[${index}][product_unit]`"
                                                    placeholder="Satuan"
                                                    class="bg-transparent border-none text-gray-900 text-sm rounded-lg w-1/3 block focus:ring-transparent p-2.5 mt-1"
                                                    :id="'productUnit' + index" readonly
                                                    x-model="items[index].product_unit">
                                            </div>
                                        </div>
                                        <button type="button" @click="items.splice(index, 1)"
                                            class="bg-uinRed border hover:bg-red-700 border-uinRed text-white text-sm font-semibold rounded-lg focus:ring-blue-500 focus:border-blue-500 block px-3 py-2 mt-1"><svg
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

                            <div class="mt-4 text-sm w-full pl-2" x-data="newProductHandler()">
                                <h3 class="text-sm font-semibold">Tambah Item Baru</h3>
                                <template x-for="(product, index) in newProducts" :key="index">
                                    <div
                                        class="mb-2 w-full border border-gray-300 rounded-xl p-4 flex flex-row gap-x-2">
                                        <div class="flex flex-col gap-y-2 w-full">
                                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                                                <input id="new_product_name" name="new_product_name" type="text"
                                                    x-model="product.product_name" placeholder="Nama Produk" required
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 p-2.5">
                                                <input type="text" x-model="product.product_detail"
                                                    placeholder="Keterangan/Formula"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 p-2.5">
                                                <input type="text" x-model="product.merk" placeholder="Merk"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 p-2.5">
                                            </div>
                                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
                                                <select id="product_type" name="product_type"
                                                    x-model="product.product_type"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                    required>
                                                    <option value="">-- Pilih Jenis Produk --</option>
                                                    <option value="Cairan">Cairan</option>
                                                    <option value="Padatan">Padatan</option>
                                                    <option value="Lainnya">Lainnya</option>
                                                </select>
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
                                                <input id="new_quantity" name="new_quantity" type="number"
                                                    step="any" x-model="product.quantity"
                                                    placeholder="Jumlah Kebutuhan" required min="0"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 p-2.5">
                                                <select id="product_unit" name="product_unit"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                                    required x-model="product.product_unit">
                                                    <option value="">-- Pilih Satuan --</option>
                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit }}">{{ $unit }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" @click="newProducts.splice(index, 1)"
                                            class="bg-red-500 hover:bg-red-700 text-white text-sm rounded-lg px-3 py-2"><svg
                                                class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                id="Outline" viewBox="0 0 24 24" width="512" height="512">
                                                <path
                                                    d="M21,4H17.9A5.009,5.009,0,0,0,13,0H11A5.009,5.009,0,0,0,6.1,4H3A1,1,0,0,0,3,6H4V19a5.006,5.006,0,0,0,5,5h6a5.006,5.006,0,0,0,5-5V6h1a1,1,0,0,0,0-2ZM11,2h2a3.006,3.006,0,0,1,2.829,2H8.171A3.006,3.006,0,0,1,11,2Zm7,17a3,3,0,0,1-3,3H9a3,3,0,0,1-3-3V6H18Z" />
                                                <path d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18Z" />
                                                <path d="M14,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z" />
                                            </svg></button>
                                    </div>
                                </template>
                                <button type="button" @click="newProducts.push({product_name: '', product_unit: ''})"
                                    class="bg-uinYellow hover:bg-amber-700 text-white px-4 py-2 rounded-md flex flex-row items-center font-semibold"><svg
                                        class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 448 512">
                                        <path
                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                    </svg>
                                    <span>Tambah Barang Baru</span></button>
                                <input type="hidden" name="new_products" x-model="JSON.stringify(newProducts)">
                            </div>
                        </div>
                    </div>
                    <div class="mt-4" x-show="purpose != ''" x-transition>
                        <p class="block text-sm font-medium text-gray-900">Approver: <span
                                class="text-red-500"><strong>*</strong></span></p>
                        <div class="mt-2 text-sm" id="approver" x-data="formApprover()">
                            <template x-for="(item, index) in approver" :key="index">
                                <div class="flex gap-2 border mb-2 border-gray-300 rounded-lg p-2">
                                    <p class="w-6 text-center font-semibold" x-text="index + 1"></p>

                                    <!-- Approver Select -->
                                    <select :name="`approver[${index}][id]`"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5 mt-1"
                                        required>
                                        <option value="">Select Approver</option>
                                        <template x-for="user in approverList" :key="user.id">
                                            <option :value="user.id" x-text="`${user.username} - ${user.name}`">
                                            </option>
                                        </template>
                                    </select>

                                    <!-- Level Select -->
                                    <select :name="`approver[${index}][level]`"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                                        required>
                                        <option value="">Select Level</option>
                                        <option value="dosen">Dosen</option>
                                        <option value="laboran">Laboran</option>
                                        <option value="koordinator">Koordinator Lab</option>
                                        <option value="kepala">Ketua Lab Fakultas</option>
                                        <option value="kepala">Ketua Lab Terpadu</option>
                                    </select>

                                    <!-- Delete Button -->
                                    <button type="button" @click="approver.splice(index, 1)"
                                        class="bg-uinRed border hover:bg-red-700 border-uinRed text-white text-sm font-semibold rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2 mt-1">
                                        <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M21,4H17.9A5.009,5.009,0,0,0,13,0H11A5.009,5.009,0,0,0,6.1,4H3A1,1,0,0,0,3,6H4V19a5.006,5.006,0,0,0,5,5h6a5.006,5.006,0,0,0,5-5V6h1a1,1,0,0,0,0-2ZM11,2h2a3.006,3.006,0,0,1,2.829,2H8.171A3.006,3.006,0,0,1,11,2Zm7,17a3,3,0,0,1-3,3H9a3,3,0,0,1-3-3V6H18Z" />
                                            <path d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18Z" />
                                            <path d="M14,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z" />
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <!-- Button Add Approver -->
                            <button type="button" @click="approver.push({id: '', level: ''})"
                                class="bg-uinBlue hover:bg-uinNavy text-white px-4 py-2 rounded-md flex flex-row items-center font-semibold">
                                <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 448 512">
                                    <path
                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                </svg>
                                <span>Tambah Approver</span>
                            </button>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('mahasiswa.pengajuan') }}"
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
    window.approverList = @json($dataApprover);

    function formHandler() {
        return {
            items: [],
            updateStock(index) {
                const productSelect = document.getElementById('productSelect' + index);
                const selectedOption = productSelect.options[productSelect.selectedIndex];

                this.items[index].product_code = selectedOption.value;
                this.items[index].product_unit = selectedOption.getAttribute('data-satuan') || '';
            },
        };
    }

    function newProductHandler() {
        return {
            newProducts: [],
        };
    }

    function formApprover() {
        return {
            approver: [],
        }
    }
</script>
