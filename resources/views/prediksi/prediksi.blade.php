<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Prediksi Barang Habis Pakai') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-full mx-auto sm:px-3 lg:px-6 grid sm:grid-cols-2 xl:grid-cols-3">
            @if (Auth::user()->usertype == 'admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-full p-5 space-y-4 rounded-lg">
                    <h2 class="text-xl font-bold text-gray-900 text-center">Upload CSV File</h2>
                    <form action="{{ route('prediksi') }}" method="POST" enctype="multipart/form-data"
                        x-data="{ fileName: '' }">
                        @csrf
                        <label class="block text-sm mb-2 text-gray-700">Select CSV File</label>
                        <input type="file" name="csv_file" class="hidden text-sm" x-ref="csv"
                            @change="fileName = $refs.csv.files[0].name">
                        <div class="flex items-center justify-between border rounded-lg p-2.5 cursor-pointer bg-gray-50"
                            @click="$refs.csv.click()">
                            <span x-text="fileName || 'Choose File'" class="text-sm"></span>
                            <button type="button"
                                class="px-2 py-1 text-sm text-white bg-uinTosca hover:bg-green-700 rounded-md">Browse</button>
                        </div>
                        <button type="submit"
                            class="w-full mt-4 px-4 py-2 text-white bg-uinBlue rounded-lg hover:bg-uinNavy transition-all duration-300">Upload</button>
                        @error('csv_file')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            @endif
        </div>
        <div class="max-w-full mx-auto sm:px-4 lg:px-6 py-3">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-6">
                    <div
                        class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between pb-1">
                        <div class="flex gap-1 items-center">
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
                            <div class="flex flex-row items-center bg-white rounded-lg w-fit ps-4 border border-gray-300">
                                <svg class="size-4 fill-gray-700" xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1"
                                    viewBox="0 0 24 24" width="512" height="512">
                                    <path
                                        d="M24,3c0,.55-.45,1-1,1H1c-.55,0-1-.45-1-1s.45-1,1-1H23c.55,0,1,.45,1,1ZM15,20h-6c-.55,0-1,.45-1,1s.45,1,1,1h6c.55,0,1-.45,1-1s-.45-1-1-1Zm4-9H5c-.55,0-1,.45-1,1s.45,1,1,1h14c.55,0,1-.45,1-1s-.45-1-1-1Z" />
                                </svg>
                                <form x-data @change="$event.target.form.submit()" method="GET"
                                    action="{{ route('prediksi') }}">
                                    <select name="location" id="location"
                                        class="font-medium text-sm text-gray-700 bg-transparent border-none focus:ring-0 cursor-pointer">
                                        @foreach ($locations as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ request('location') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </div>
                        <div class="pr-44">
                            <h1 class="font-bold text-xl mb-2 text-gray-900">Tabel Prediksi Perencanaan BHP Tahun
                                {{ $dataPrediksis[0]->tahun_perencanaan ?? '-' }}</h1>
                        </div>
                        <div class="mr-4">
                            <a href="{{ route('perencanaan-bhp') }}"
                                class="inline-flex items-center gap-x-2 px-4 py-2 rounded-lg text-white bg-amber-500 hover:bg-amber-600 w-full duration-300 transition-all">
                                <svg class="size-4 fill-white" id="Layer_1" height="512" viewBox="0 0 24 24"
                                    width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                                    <path
                                        d="m16 6a1 1 0 0 1 0 2h-8a1 1 0 0 1 0-2zm7.707 17.707a1 1 0 0 1 -1.414 0l-2.407-2.407a4.457 4.457 0 0 1 -2.386.7 4.5 4.5 0 1 1 4.5-4.5 4.457 4.457 0 0 1 -.7 2.386l2.407 2.407a1 1 0 0 1 0 1.414zm-6.207-3.707a2.5 2.5 0 1 0 -2.5-2.5 2.5 2.5 0 0 0 2.5 2.5zm-4.5 2h-6a3 3 0 0 1 -3-3v-14a3 3 0 0 1 3-3h12a1 1 0 0 1 1 1v8a1 1 0 0 0 2 0v-8a3 3 0 0 0 -3-3h-12a5.006 5.006 0 0 0 -5 5v14a5.006 5.006 0 0 0 5 5h6a1 1 0 0 0 0-2z" />
                                </svg><span>Lihat Perencanaan >></span>
                            </a>
                        </div>
                    </div>
                    <div class="mb-1">
                        {{ $dataPrediksis->appends(['search' => request('search')])->links('pagination::tailwind') }}
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
                                        Jenis
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Program Studi
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Stok
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Kebutuhan
                                    </th>
                                    <th scope="col" class="px-1 py-3">
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
                                        <th class="px-1 py-4">
                                            {{ $counter }}
                                        </th>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ $dataPrediksi->product_code }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ $dataPrediksi->asset->product_name }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ empty($dataPrediksi->asset->product_detail) ? '-' : $dataPrediksi->asset->product_detail }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ empty($dataPrediksi->asset->merk) ? '-' : $dataPrediksi->asset->merk }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ $dataPrediksi->asset->product_type }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ $dataPrediksi->asset->location }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ $dataPrediksi->asset->stock }}
                                        </td>
                                        <td scope="row"
                                            class="px-1 py-2 font-semibold text-gray-900 whitespace-nowrap">
                                            {{ $dataPrediksi->kebutuhan }}
                                        </td>
                                        <td scope="row"
                                            class="px-1 py-2 font-semibold text-gray-900 whitespace-nowrap">
                                            {{ $dataPrediksi->asset->product_unit }}
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @empty
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th colspan="12" class="px-1 py-2 text-center">
                                            Data Tidak Ditemukan
                                        </th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $dataPrediksis->appends(['search' => request('search')])->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
