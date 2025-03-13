<x-app-layout>
    <x-slot name="header">
        <a href="{{ route('perencanaan-inv') }}">
            <h2 class="font-semibold text-xl text-gray-900 leading-tight">
                {{ __('◀️ Tambah Data Perencanaan Inventaris') }}
            </h2>
        </a>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 xl:grid xl:grid-cols-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 xl:col-span-2">
                <form method="POST" action="{{ route('add-perencanaan.inv') }}">
                    @csrf
                    @php
                        $currentYear = date('Y');
                        $currentMonth = date('m');
                        $semester = $currentMonth <= 6 ? '1' : '2';
                        $namaPerencanaan = "{$currentYear}-{$semester}";
                    @endphp
                    <!-- Nama Perencanaan -->
                    <div>
                        <x-input-label for="nama_perencanaan" :value="__('Tahun Perencanaan')" />
                        <x-text-input id="nama_perencanaan" class="block mt-1 w-full" type="text"
                            name="nama_perencanaan" :value="old('nama_perencanaan', $namaPerencanaan)" required autofocus
                            autocomplete="nama_perencanaan" />
                        <x-input-error :messages="$errors->get('nama_perencanaan')" class="mt-2" />
                    </div>

                    <!-- Lokasi / Program Studi -->
                    <div class="mt-4">
                        <x-input-label for="location" :value="__('Lokasi / Program Studi')" />
                        @if ($prodi != null)
                            <x-text-input id="location" class="block mt-1 w-full" type="text" name="location"
                                :value="old('location', $prodi)" autofocus autocomplete="location" readonly />
                        @else
                            <select id="location" name="location"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                                required>
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

                    <!-- Tipe (hidden) -->
                    <div class="mt-4 hidden">
                        <x-input-label for="type" :value="__('Tipe')" />
                        <x-text-input id="type" class="block mt-1 w-full" type="text" name="type"
                            :value="old('type', 'inventaris')" autofocus autocomplete="type" />
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <!-- Items -->
                    <div class="mt-4">
                        <label for="items" class="block text-sm font-medium text-gray-900">Daftar Items: <span
                                class="text-red-600">*! Jika produk tidak ditemukan maka simpan dulu dan lakukan edit
                                dibagian detail perencanaan</span></label>
                        <div class="mt-2 text-sm" id="items" x-data="formHandler()">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex flex-col lg:flex-row mb-2 text-sm text-gray-900 gap-x-2">
                                    <select name="items[][product_code]"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block lg:w-7/12 p-2.5 mt-1"
                                        :id="'productSelect' + index" @change="updateStock(index)" required>
                                        <option value="">Select Product</option>
                                        @foreach ($assetsinv as $product)
                                            <option value="{{ $product->product_code }}"
                                                data-stock="{{ $product->stock }}"
                                                data-satuan="{{ $product->product_unit }}">
                                                {{ $product->product_name }} ({{ $product->product_code }})
                                                {{ $product->product_detail ? "({$product->product_detail})" : '' }}
                                                {{ $product->merk ? "({$product->merk})" : '' }}
                                                {{ $product->product_type ? "({$product->product_type})" : '' }}
                                                @if (Auth::user()->usertype === 'admin')
                                                    {{ $product->location ? "({$product->location})" : '' }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="flex flex-row gap-x-2 justify-between lg:w-6/12">
                                        <input type="number" name="items[][stock]" placeholder="Stok"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full block p-2.5 mt-1"
                                            :id="'productStock' + index" required readonly>
                                        <input type="number" name="items[][quantity]" placeholder="Jumlah"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full block p-2.5 mt-1"
                                            min="0" required>
                                        <input type="text" name="items[][product_unit]" placeholder="Satuan"
                                            class="bg-transparent border-none text-gray-900 text-sm rounded-lg w-full block focus:ring-transparent p-2.5 mt-1"
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
                        <a href="{{ route('perencanaan-inv') }}"
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
