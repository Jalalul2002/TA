<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Perencanaan Barang Habis Pakai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg py-4 px-16 mb-4">
                <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between">
                    <div>
                        <h1 class="font-medium mb-2">Nama Perencanaan</h1>
                        <h1 class="text-lg font-bold">{{ $dataPerencanaan->nama_perencanaan }}</h1>
                    </div>
                    <div>
                        <h1 class="font-medium mb-2">Program Studi</h1>
                        <h1 class="text-lg font-bold">{{ $dataPerencanaan->prodi }}</h1>
                    </div>
                    <div>
                        <h1 class="font-medium mb-2">Status</h1>
                        <h1
                            class="text-lg font-bold {{ $dataPerencanaan->status == 'belum' ? 'text-red-500' : 'text-green-500' }}">
                            {{ strtoupper($dataPerencanaan->status) }}</h1>
                    </div>
                    <div>
                        <h1 class="font-medium mb-2">Diupdate :</h1>
                        <h1 class="text-lg font-bold">{{ strtoupper($dataPerencanaan->updated_at) }}</h1>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-6">
                    <div
                        class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-4">
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
                        <div>
                            <button @click="openModal = true"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 448 512">
                                    <path
                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                </svg>
                                Tambah Produk
                            </button>
                        </div>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Kode Produk
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Nama Produk
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Rumus Kimia
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Merk
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Jenis Produk
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Stok
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Jumlah Kebutuhan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Satuan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = ($products->currentPage() - 1) * $products->perPage() + 1;
                            @endphp
                            @forelse ($products as $product)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th class="px-6 py-4">
                                        {{ $counter }}
                                    </th>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $product->product_code }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $product->product->product_name }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $product->product->formula }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $product->product->merk }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $product->product->product_type }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $product->stock }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $product->jumlah_kebutuhan }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $product->product->product_unit }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="#" class="font-medium text-blue-600 hover:underline">Edit</a>
                                        <form action="{{ route('rencana.destroy-item', $product->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @php
                                    $counter++;
                                @endphp
                            @empty
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th colspan="8" class="px-6 py-4 text-center">
                                        Data Tidak Ditemukan
                                    </th>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $products->appends(['search' => request('search')])->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
            <!-- Modal -->
            <div>
                <div x-show="openModal" x-cloak class="fixed z-10 inset-0 overflow-y-auto">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="openModal" x-cloak class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75" @click="openModal = false"></div>
                        </div>

                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>

                        <div x-show="openModal" x-cloak
                            class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div x-data="{ selectedProduct: '', stock: 0 }">
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                                        Tambah Produk
                                    </h3>
                                    <div class="mt-2">
                                        <form action="{{ route('rencana.store-item', $dataPerencanaan->id) }}"
                                            method="POST">
                                            @csrf
                                            <div class="mt-4">
                                                <label for="product_code"
                                                    class="block text-sm font-medium text-gray-700">Product</label>
                                                <select id="product_code" name="product_code"
                                                    x-model="selectedProduct"
                                                    @change="stock = $el.selectedOptions[0].dataset.stock"
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                    <option value="">Select Product</option>
                                                    @foreach ($assetBhps as $product)
                                                        <option value="{{ $product->product_code }}"
                                                            data-stock="{{ $product->stock }}">
                                                            {{ $product->product_name }}
                                                            ({{ $product->product_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mt-4">
                                                <label for="stock"
                                                    class="block text-sm font-medium text-gray-700">Stok</label>
                                                <input type="number" name="stock" id="stock" x-model="stock"
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                    readonly>
                                            </div>
                                            <div class="mt-4">
                                                <label for="quantity"
                                                    class="block text-sm font-medium text-gray-700">Jumlah
                                                    Kebutuhan</label>
                                                <input type="number" name="quantity" id="quantity"
                                                    class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                    required>
                                            </div>
                                            <div class="mt-6">
                                                <button type="submit"
                                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Tambah Produk
                                                </button>
                                                <button type="button" @click="openModal = false"
                                                    class="ml-3 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                                    Batal
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
