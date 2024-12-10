<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Prediksi BHP') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-3 lg:px-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-full max-w-md p-8 space-y-4 rounded-lg">
                <<h2 class="text-2xl font-bold text-gray-800 text-center">Upload CSV File</h2>
                    <form action="/send-data" method="POST" enctype="multipart/form-data" x-data="{ fileName: '' }">
                        @csrf
                        <label class="block mb-2 text-gray-700">Select CSV File</label>
                        <input type="file" name="csv_file" class="hidden" x-ref="csv"
                            @change="fileName = $refs.csv.files[0].name">
                        <div class="flex items-center justify-between border rounded p-2 cursor-pointer bg-gray-50"
                            @click="$refs.csv.click()">
                            <span x-text="fileName || 'Choose File'"></span>
                            <button type="button"
                                class="px-2 py-1 text-sm text-white bg-blue-600 rounded">Browse</button>
                        </div>
                        <button type="submit"
                            class="w-full mt-4 px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Upload</button>
                        @error('csv_file')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </form>
            </div>
        </div>
        <div class="max-w-full mx-auto sm:px-4 lg:px-6 py-3">
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
                        <div class="pr-44">
                            <h1 class="font-bold text-xl mb-2 text-gray-800">Tabel Prediksi Perencanaan BHP Tahun
                                {{ $dataPrediksis[0]->tahun_perencanaan ?? '-' }}</h1>
                        </div>
                        <div class="mr-4">
                            <a href="/perencanaan-bhp"
                                class="inline-flex items-center px-4 py-3 rounded-lg text-white bg-uinBlue hover:bg-uinNavy w-full">
                                >> Lihat Perencanaan
                            </a>
                        </div>
                    </div>
                    <div class="mb-1">
                        {{ $dataPrediksis->appends(['search' => request('search')])->links('pagination::tailwind') }}
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
                                    Jenis
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Program Studi
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Stok
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Kebutuhan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Satuan
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = ($dataPrediksis->currentPage() - 1) * $dataPrediksis->perPage() + 1;
                            @endphp
                            @forelse ($dataPrediksis as $dataPrediksi)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th class="px-6 py-4">
                                        {{ $counter }}
                                    </th>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $dataPrediksi->product_code }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $dataPrediksi->asset->product_name }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ empty($dataPrediksi->asset->formula) ? '-' : $dataPrediksi->asset->formula }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ empty($dataPrediksi->asset->merk) ? '-' : $dataPrediksi->asset->merk }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $dataPrediksi->asset->product_type }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $dataPrediksi->asset->location }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $dataPrediksi->asset->stock }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $dataPrediksi->kebutuhan }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $dataPrediksi->asset->product_unit }}
                                    </td>
                                </tr>
                                @php
                                    $counter++;
                                @endphp
                            @empty
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th colspan="12" class="px-6 py-4 text-center">
                                        Data Tidak Ditemukan
                                    </th>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $dataPrediksis->appends(['search' => request('search')])->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
