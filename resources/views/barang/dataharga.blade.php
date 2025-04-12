<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Harga Barang dan Sewa per Item') }}
        </h2>
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
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-6">
                    <div
                        class="flex flex-col-reverse xl:flex-row space-y-1 space-x-1 xl:items-center justify-between pb-4">
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
                                class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 lg:w-52 xl:w-64 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search for items" autocomplete="off">
                        </div>
                        <div x-data="filterData()"
                            class="flex flex-col md:flex-row space-y-1 md:space-y-0 md:items-center gap-x-1 xl:gap-x-2 justify-end w-full">
                            <div
                                class="flex flex-col space-y-1 md:space-y-0 md:flex-row md:items-center gap-x-1 xl:gap-x-2">
                                <div class="flex space-x-1 xl:space-x-2">
                                    <select id="type" name="type" x-model="type" @change="applyFilter()"
                                        class="block w-full p-2 border border-gray-300 rounded-md text-sm text-gray-700">
                                        <option value="">-- Filter Tipe Barang --</option>
                                        <option value="bhp">BHP</option>
                                        <option value="inventaris">Inventaris</option>
                                    </select>
                                    <select id="product_type" name="product_type" x-model="productType"
                                        @change="applyFilter()"
                                        class="block w-full p-2 border border-gray-300 rounded-md text-sm text-gray-700">
                                        <option value="">-- Filter Jenis --</option>
                                        <option value="Cairan">Cairan</option>
                                        <option value="Padatan">Padatan</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    @if (Auth::user()->usertype == 'admin' || Auth::user()->usertype == 'user')
                                        <select id="location" name="location" x-model="location"
                                            @change="applyFilter()"
                                            class="block w-full p-2 border border-gray-300 rounded-md text-sm text-gray-700">
                                            <option value="">-- Filter Lokasi --</option>
                                            <option value="Umum">Umum</option>
                                            <option value="Matematika">Matematika</option>
                                            <option value="Biologi">Biologi</option>
                                            <option value="Fisika">Fisika</option>
                                            <option value="Kimia">Kimia</option>
                                            <option value="Teknik Indivatika">Teknik Informatika</option>
                                            <option value="Agroteknologi">Agroteknologi</option>
                                            <option value="Teknik Elektro">Teknik Elektro</option>
                                        </select>
                                    @endif
                                </div>
                                @if (Auth::user()->usertype !== 'user')
                                    <a href="{{ route('add-harga') }}"
                                        class="inline-flex text-sm items-center justify-center px-4 py-2 border border-transparent rounded-md font-semibold text-white bg-uinBlue hover:bg-uinNavy transition duration-300">
                                        <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor" viewBox="0 0 448 512">
                                            <path
                                                d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                        </svg>
                                        Tambah
                                    </a>
                                @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="mb-1">
                            {{ $data->appends(request()->query())->links('pagination::tailwind') }}
                        </div>
                        <div class="relative overflow-x-auto sm:rounded-lg">
                            @php
                                $columns = [
                                    'product_code' => 'Kode Barang',
                                    'product_name' => 'Nama Barang',
                                    'product_detail' => 'Keterangan',
                                    'merk' => 'Merk',
                                    'product_type' => 'Jenis',
                                    'product_unit' => 'satuan',
                                    'price_type' => 'Tipe Harga',
                                    'purchase_price' => 'Harga Beli',
                                    'price' => 'Harga Pakai',
                                    'effective_date' => 'Tanggal Berlaku',
                                ];
                                if (Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'user') {
                                    $columns['location'] = 'Prodi';
                                }
                            @endphp
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <thead class="text-xs text-white uppercase bg-uinTosca">
                                    <tr>
                                        <th scope="col" class="py-3 text-center">
                                            No
                                        </th>
                                        @foreach ($columns as $field => $name)
                                            <th scope="col" class="py-3">
                                                <div class="flex items-center">
                                                    {{ $name }}
                                                    @php
                                                        // Tentukan arah sorting berdasarkan field yang sedang diurutkan
                                                        $newSortOrder =
                                                            request('sort_field') === $field &&
                                                            request('sort_order') === 'asc'
                                                                ? 'desc'
                                                                : 'asc';
                                                        $isActive = request('sort_field', 'effective_date') === $field;
                                                    @endphp
                                                    <a title="Sort by {{ $name }}"
                                                        href="{{ route('data-harga', array_merge(request()->query(), ['sort_field' => $field, 'sort_order' => $newSortOrder])) }}">
                                                        <svg class="w-3 h-3 ms-1.5 {{ $isActive ? 'fill-uinOrange' : 'fill-white' }}"
                                                            xmlns="http://www.w3.org/2000/svg" id="arrow-circle-down"
                                                            viewBox="0 0 24 24" width="512" height="512">
                                                            <path
                                                                d="M18.873,11.021H5.127a2.126,2.126,0,0,1-1.568-3.56L10.046.872a2.669,2.669,0,0,1,3.939.034l6.431,6.528a2.126,2.126,0,0,1-1.543,3.587ZM12,24.011a2.667,2.667,0,0,1-1.985-.887L3.584,16.6a2.125,2.125,0,0,1,1.543-3.586H18.873a2.125,2.125,0,0,1,1.568,3.558l-6.487,6.589A2.641,2.641,0,0,1,12,24.011Z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </th>
                                        @endforeach
                                        <th scope="col" class="py-3">
                                            Dibuat Oleh
                                        </th>
                                        @if (Auth::user()->usertype !== 'user')
                                            <th scope="col" class="py-3 text-center">
                                                Action
                                            </th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $counter = ($data->currentPage() - 1) * $data->perPage() + 1;
                                    @endphp
                                    @forelse ($data as $assetLab)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <th class="px-2 py-4 text-center">
                                                {{ $counter }}
                                            </th>
                                            <td scope="row" class="px-2 font-medium whitespace-nowrap">
                                                {{ $assetLab->product_code }}
                                            </td>
                                            <td scope="row" class="px-2 font-medium">
                                                {{ $assetLab->product_name }}
                                            </td>
                                            <td class="px-2">
                                                {{ empty($assetLab->product_detail) ? '-' : $assetLab->product_detail }}
                                            </td>
                                            <td class="px-2">
                                                {{ empty($assetLab->merk) ? '-' : $assetLab->merk }}
                                            </td>
                                            <td class="px-2">
                                                {{ $assetLab->product_type }}
                                            </td>
                                            <td class="px-2">
                                                {{ $assetLab->product_unit }}
                                            </td>
                                            <td class="px-2 whitespace-nowrap">
                                                <span
                                                    class="px-2 py-1 text-white rounded-full {{ $assetLab->price_type == 'unit' ? 'bg-uinBlue' : 'bg-uinTosca' }}">
                                                    {{ $assetLab->price_type == 'unit' ? 'per Unit' : 'Sewa per Jam' }}
                                                </span>
                                            </td>
                                            <td class="px-2 font-semibold text-right whitespace-nowrap">
                                                <span class="px-2 py-1 text-white rounded-full bg-uinOrange">
                                                    Rp. {{ number_format($assetLab->purchase_price ?? 0, 0, ',', '.') }},-
                                                </span>
                                            </td>
                                            <td class="px-2 font-semibold text-right whitespace-nowrap">
                                                <span class="px-2 py-1 text-white rounded-full bg-teal-500">
                                                    Rp. {{ number_format($assetLab->price ?? 0, 0, ',', '.') }},-
                                                </span>
                                            </td>
                                            <td class="px-2 font-semibold">
                                                {{ $assetLab->effective_date->format('d/m/Y') }}
                                            </td>
                                            @if (Auth::user()->usertype == 'admin' || Auth::user()->usertype === 'user')
                                                <td class="px-2">
                                                    {{ $assetLab->location }}
                                                </td>
                                            @endif
                                            <td class="px-2">
                                                {{ $assetLab->creator->name }}
                                            </td>
                                            @if (Auth::user()->usertype !== 'user')
                                                <td class="py-2 flex flex-row gap-x-2 justify-center">
                                                    <form action="{{ route('data-harga.destroy', $assetLab->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="bg-uinRed p-2 rounded-lg hover:bg-red-600 transition duration-300"><svg
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
                                            @if (Auth::user()->usertype == 'admin')
                                                <th colspan="13" class="px-6 py-4 text-center">
                                                    Data Tidak Ditemukan
                                                </th>
                                            @else
                                                <th colspan="12" class="px-6 py-4 text-center">
                                                    Data Tidak Ditemukan
                                                </th>
                                            @endif
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $data->appends(request()->query())->links('pagination::tailwind') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
