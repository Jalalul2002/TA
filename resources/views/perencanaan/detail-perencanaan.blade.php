<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ $dataPerencanaan->type === 'bhp' ? route('perencanaan-bhp') : route('perencanaan-inv') }}">
                {{ __('◀️ Detail Perencanaan Barang') }}
            </a>
        </h2>
    </x-slot>
    <form id="complete-form" action="{{ route('perencanaan.complete', $dataPerencanaan->id) }}" method="POST"
        style="display: none;">
        @csrf
    </form>
    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-6 mb-6 border border-gray-200">
                <div class="flex flex-col space-y-4">
                    <div class="flex justify-between items-center border-b pb-4">
                        <h1 class="text-2xl font-bold text-gray-900">Detail Perencanaan {{ strtoupper($dataPerencanaan->type) }}</h1>
                        <span
                            class="text-sm font-bold rounded-full px-4 py-2
                            {{ $dataPerencanaan->status == 'belum' ? 'bg-red-100 text-red-500' : 'bg-green-100 text-green-500' }}">{{ $dataPerencanaan->status == 'belum' ? 'Belum Diselesaikan' : 'Sudah Diselesaikan' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <h2 class="text-gray-500 text-sm font-medium">Perencanaan</h2>
                            <h1 class="text-lg font-semibold text-gray-900">
                                {{ $dataPerencanaan->nama_perencanaan . ' - ' . $dataPerencanaan->prodi }}
                            </h1>
                        </div>
                        <div>
                            <h2 class="text-gray-500 text-sm font-medium">Diupdate</h2>
                            <h1 class="text-lg font-semibold text-gray-900">
                                {{ ($dataPerencanaan->latestUpdater?->updater?->name ?? $dataPerencanaan->updater->name) . ' | ' . ($dataPerencanaan->latestUpdater?->updated_at ?? $dataPerencanaan->updated_at) }}
                            </h1>
                        </div>
                        @if ($dataPerencanaan->status === 'belum')
                            <div class="flex justify-end flex-row">
                                <button
                                    onclick="event.preventDefault(); document.getElementById('complete-form').submit();"
                                    class="inline-flex items-center px-4 py-2 gap-x-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Outline"
                                        viewBox="0 0 24 24" width="512" height="512">
                                        <path
                                            d="M12,10a4,4,0,1,0,4,4A4,4,0,0,0,12,10Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,16Z" />
                                        <path
                                            d="M22.536,4.122,19.878,1.464A4.966,4.966,0,0,0,16.343,0H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V7.657A4.966,4.966,0,0,0,22.536,4.122ZM17,2.08V3a3,3,0,0,1-3,3H10A3,3,0,0,1,7,3V2h9.343A2.953,2.953,0,0,1,17,2.08ZM22,19a3,3,0,0,1-3,3H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2V3a5.006,5.006,0,0,0,5,5h4a4.991,4.991,0,0,0,4.962-4.624l2.16,2.16A3.02,3.02,0,0,1,22,7.657Z" />
                                    </svg>
                                    <span>Selesaikan Perencanaan</span>
                                </button>
                            </div>
                        @else
                            <div>
                                <h2 class="text-gray-500 text-sm font-medium">Diselesaikan</h2>
                                <h1 class="text-lg font-semibold text-gray-900">
                                    {{ $dataPerencanaan->updater->name . ' | ' . $dataPerencanaan->updated_at }}
                                </h1>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-6">
                    <div
                        class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-2">
                        <label for="search" class="sr-only">Search</label>
                        <div class="relative">
                            <div
                                class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500" aria-hidden="true" fill="currentColor"
                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input x-data="{ search: '{{ request('search') }}' }" x-on:input="search = $event.target.value" type="text"
                                id="search" name="search" x-model="search"
                                class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search for items" autocomplete="off">
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('perencanaan.download', $dataPerencanaan->id) }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-500 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="size-4 me-2 fill-white" xmlns="http://www.w3.org/2000/svg" id="Outline"
                                    viewBox="0 0 24 24" width="512" height="512">
                                    <path
                                        d="M9.878,18.122a3,3,0,0,0,4.244,0l3.211-3.211A1,1,0,0,0,15.919,13.5l-2.926,2.927L13,1a1,1,0,0,0-1-1h0a1,1,0,0,0-1,1l-.009,15.408L8.081,13.5a1,1,0,0,0-1.414,1.415Z" />
                                    <path
                                        d="M23,16h0a1,1,0,0,0-1,1v4a1,1,0,0,1-1,1H3a1,1,0,0,1-1-1V17a1,1,0,0,0-1-1H1a1,1,0,0,0-1,1v4a3,3,0,0,0,3,3H21a3,3,0,0,0,3-3V17A1,1,0,0,0,23,16Z" />
                                </svg>
                                Download Data
                            </a>
                            @if ($dataPerencanaan->status === 'belum')
                                <button @click="openModal = true"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-uinBlue hover:bg-uinNavy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 448 512">
                                        <path
                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                    </svg>
                                    Tambah Produk
                                </button>
                            @endif
                        </div>
                    </div>
                    <div>
                        {{ $products->appends(['search' => request('search')])->links('pagination::tailwind') }}
                    </div>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-white uppercase bg-uinTosca">
                                <tr>
                                    <th scope="col" class="px-1 text-center py-3">
                                        No
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Kode Produk
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Nama Produk
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Keterangan/Formula
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Merk
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Jenis Produk
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Stok
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Jumlah Kebutuhan
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Satuan
                                    </th>
                                    @if ($dataPerencanaan->status === 'belum')
                                        <th scope="col" class="px-1 text-center py-3">
                                            Action
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = ($products->currentPage() - 1) * $products->perPage() + 1;
                                @endphp
                                @forelse ($products as $product)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th class="px-1 text-center py-4">
                                            {{ $counter }}
                                        </th>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ $product->product_code }}
                                        </td>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ $product->product->product_name }}
                                        </td>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ empty($product->product->formula) ? '-' : $product->product->formula }}
                                        </td>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ empty($product->product->merk) ? '-' : $product->product->merk }}
                                        </td>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ $product->product->product_type }}
                                        </td>
                                        <td class="px-1 py-4">
                                            {{ $product->stock }}
                                        </td>
                                        <td class="px-1 py-4 font-bold text-gray-600">
                                            {{ $product->jumlah_kebutuhan }}
                                        </td>
                                        <td scope="row"
                                            class="px-1 py-4 font-bold text-gray-600 whitespace-nowrap">
                                            {{ $product->product->product_unit }}
                                        </td>
                                        @if ($dataPerencanaan->status === 'belum')
                                            <td class="py-2 flex flex-row gap-x-2 justify-center">
                                                <a title="Edit Data"
                                                    href="{{ route('rencana.edit-rencana', $product->id) }}">
                                                    <div class="bg-uinOrange p-2 rounded-lg hover:bg-yellow-400">
                                                        <svg class="size-4 fill-white"
                                                            xmlns="http://www.w3.org/2000/svg" id="Outline"
                                                            viewBox="0 0 24 24" width="512" height="512">
                                                            <path
                                                                d="M18.656.93,6.464,13.122A4.966,4.966,0,0,0,5,16.657V18a1,1,0,0,0,1,1H7.343a4.966,4.966,0,0,0,3.535-1.464L23.07,5.344a3.125,3.125,0,0,0,0-4.414A3.194,3.194,0,0,0,18.656.93Zm3,3L9.464,16.122A3.02,3.02,0,0,1,7.343,17H7v-.343a3.02,3.02,0,0,1,.878-2.121L20.07,2.344a1.148,1.148,0,0,1,1.586,0A1.123,1.123,0,0,1,21.656,3.93Z" />
                                                            <path
                                                                d="M23,8.979a1,1,0,0,0-1,1V15H18a3,3,0,0,0-3,3v4H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2h9.042a1,1,0,0,0,0-2H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H16.343a4.968,4.968,0,0,0,3.536-1.464l2.656-2.658A4.968,4.968,0,0,0,24,16.343V9.979A1,1,0,0,0,23,8.979ZM18.465,21.122a2.975,2.975,0,0,1-1.465.8V18a1,1,0,0,1,1-1h3.925a3.016,3.016,0,0,1-.8,1.464Z" />
                                                        </svg>
                                                    </div>
                                                </a>
                                                <form action="{{ route('rencana.destroy-item', $product->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button title="Hapus Data" type="submit"
                                                        class="bg-uinRed p-2 rounded-lg hover:bg-red-600"><svg
                                                            class="size-4 fill-white"
                                                            xmlns="http://www.w3.org/2000/svg" id="Outline"
                                                            viewBox="0 0 24 24" width="512" height="512">
                                                            <path
                                                                d="M21,4H17.9A5.009,5.009,0,0,0,13,0H11A5.009,5.009,0,0,0,6.1,4H3A1,1,0,0,0,3,6H4V19a5.006,5.006,0,0,0,5,5h6a5.006,5.006,0,0,0,5-5V6h1a1,1,0,0,0,0-2ZM11,2h2a3.006,3.006,0,0,1,2.829,2H8.171A3.006,3.006,0,0,1,11,2Zm7,17a3,3,0,0,1-3,3H9a3,3,0,0,1-3-3V6H18Z" />
                                                            <path
                                                                d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18Z" />
                                                            <path
                                                                d="M14,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z" />
                                                        </svg></button>
                                                </form>
                                            </td>
                                        @endif
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @empty
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th colspan="13" class="px-6 py-4 text-center">
                                            Data Tidak Ditemukan
                                        </th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $products->appends(['search' => request('search')])->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div>
                <div x-show="openModal" x-cloak class="fixed z-10 inset-0 overflow-y-auto">
                    <div
                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="openModal" x-cloak class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75" @click="openModal = false"></div>
                        </div>

                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>

                        <div x-show="openModal" x-cloak
                            class="inline-block align-bottom bg-white rounded-3xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div x-data="{ selectedProduct: '', stock: 0, satuan: '' }">
                                <div class="mt-3">
                                    <h3 class="text-3xl font-semibold text-gray-900 py-5">
                                        📝 Tambah Produk
                                    </h3>
                                    <div>
                                        <form action="{{ route('rencana.store-item', $dataPerencanaan->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="mt-4">
                                                <label for="product_code"
                                                    class="block text-sm font-medium text-gray-700">Product</label>
                                                <select id="product_code" name="product_code"
                                                    x-model="selectedProduct"
                                                    @change="stock = $el.selectedOptions[0].dataset.stock; satuan = $el.selectedOptions[0].dataset.satuan; "
                                                    class="mt-1 block w-full p-2.5 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                    <option value="">Select Product</option>
                                                    @foreach ($assets as $product)
                                                        <option value="{{ $product->product_code }}"
                                                            data-stock="{{ $product->stock }}"
                                                            data-satuan="{{ $product->product_unit }}">
                                                            {{ $product->product_name }}
                                                            ({{ $product->product_code }})
                                                            {{ empty($product->formula) ? '' : '(' . $product->formula . ')' }}
                                                            {{ empty($product->merk) ? '' : '(' . $product->merk . ')' }}
                                                            {{ empty($product->product_type) ? '' : '(' . $product->product_type . ')' }}
                                                            ({{ $product->product_unit }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-4">
                                                <label for="stock"
                                                    class="block text-sm font-medium text-gray-700">Stok</label>
                                                <input type="number" name="stock" id="stock" x-model="stock"
                                                    class="mt-1 block w-full p-2.5 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                    readonly>
                                            </div>
                                            <div class="mt-4 flex flex-row gap-x-2">
                                                <div class="w-full">
                                                    <label for="quantity"
                                                        class="block text-sm font-medium text-gray-700">Jumlah
                                                        Kebutuhan</label>
                                                    <input type="number" name="quantity" id="quantity"
                                                        class="mt-1 block w-full p-2.5 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                        min="0" required>
                                                </div>
                                                <div class="w-full">
                                                    <label for="satuan"
                                                        class="block text-sm font-medium text-gray-700">Satuan</label>
                                                    <input type="text" name="satuan" id="satuan"
                                                        x-model="satuan"
                                                        class="mt-1 block w-full p-2.5 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="mt-6 flex flex-row justify-end gap-x-2">
                                                <button type="button" @click="openModal = false"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-md shadow-sm text-white bg-uinRed hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-md shadow-sm text-white bg-green-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
