<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Perencanaan BHP') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('add-perencanaan-bhp') }}">
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
                                :value="old('location', $prodi)" autofocus autocomplete="location" disabled />
                        @else
                            <select id="location" name="location" class="block mt-1 w-full" required>
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

                    <!-- Tipe (hidden) -->
                    <div class="mt-4 hidden">
                        <x-input-label for="type" :value="__('Tipe')" />
                        <x-text-input id="type" class="block mt-1 w-full" type="text" name="type"
                            :value="old('type', 'bhp')" autofocus autocomplete="type" />
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <!-- Items -->
                    <div class="mt-4">
                        <label for="items" class="block text-sm font-medium text-gray-700">Items</label>
                        <div class="mt-4" id="items" x-data="formHandler()">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex mb-2">
                                    <select name="items[][product_code]" class="mr-2" :id="'productSelect' + index"
                                        @change="updateStock(index)" required>
                                        <option value="">Select Product</option>
                                        @foreach ($assetbhps as $product)
                                            <option value="{{ $product->product_code }}"
                                                data-stock="{{ $product->stock }}"
                                                data-satuan="{{ $product->product_unit }}">
                                                {{ $product->product_name }} ({{ $product->product_code }})
                                                {{ empty($product->formula) ? '' : '(' . $product->formula . ')' }}
                                                {{ empty($product->merk) ? '' : '(' . $product->merk . ')' }}
                                                {{ empty($product->product_type) ? '' : '(' . $product->product_type . ')' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" name="items[][stock]" placeholder="Stok" class="mr-2"
                                        :id="'productStock' + index" required readonly>
                                    <input type="number" name="items[][quantity]" placeholder="Jumlah Kebutuhan"
                                        class="mr-2" required>
                                    <input type="text" name="items[][product_unit]" placeholder="Satuan"
                                        class="mr-2" :id="'productUnit' + index" readonly>
                                    <button type="button" @click="items.splice(index, 1)"
                                        class="bg-red-500 text-white px-4 py-2 rounded">Remove</button>
                                </div>
                            </template>
                            <button type="button" @click="items.push({product_code: '', stock: 0, quantity: 0})"
                                class="bg-blue-500 text-white px-4 py-2 rounded">Tambah Barang</button>
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('perencanaan-bhp') }}"
                            class="px-4 py-2 bg-uinRed border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">Kembali</a>
                        <x-primary-button class="ms-2">
                            {{ __('Tambah Data') }}
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
