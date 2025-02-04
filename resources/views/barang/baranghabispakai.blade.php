<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Barang Habis Pakai') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
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
                        <div class="flex items-center gap-x-2">
                            <form action="{{ route('export.bhp') }}" method="GET" class="flex items-center gap-2">
                                <div>
                                    <select id="product_type" name="product_type"
                                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md text-sm">
                                        <option value="">-- Pilih Jenis Produk --</option>
                                        <option value="Cairan">Cairan</option>
                                        <option value="Padatan">Padatan</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                @if (Auth::user()->usertype == 'admin')
                                    <div>
                                        <select id="location" name="location"
                                            class="mt-1 block w-full p-2 border border-gray-300 rounded-md text-sm">
                                            <option value="">-- Pilih Lokasi --</option>
                                            <option value="Matematika">Matematika</option>
                                            <option value="Biologi">Biologi</option>
                                            <option value="Fisika">Fisika</option>
                                            <option value="Kimia">Kimia</option>
                                            <option value="Teknik Informatika">Teknik Informatika</option>
                                            <option value="Agroteknologi">Agroteknologi</option>
                                            <option value="Teknik Elektro">Teknik Elektro</option>
                                        </select>
                                    </div>
                                @endif
                                <div>
                                    <button type="submit"
                                        class="inline-flex text-sm items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-800">
                                        Export Excel
                                    </button>
                                </div>
                            </form>
                            <a href="/add-aset-bhp"
                                class="inline-flex text-sm items-center px-4 py-2 border border-transparent rounded-md font-semibold text-white bg-uinBlue hover:bg-uinNavy">
                                <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 448 512">
                                    <path
                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                </svg>
                                Tambah Data
                            </a>
                        </div>
                    </div>
                    <div class="mb-1">
                        {{ $assetLabs->onEachSide(1)->appends(['search' => request('search')])->links('pagination::tailwind') }}
                    </div>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        @php
                            $columns = [
                                'product_code' => 'Kode Barang',
                                'product_name' => 'Nama Barang',
                                'formula' => 'Rumus Kimia',
                                'merk' => 'Merk',
                                'product_type' => 'Jenis',
                                'stock' => 'stok',
                                'product_unit' => 'satuan',
                                'location_detail' => 'Lokasi Penyimpanan',
                            ];
                            if (Auth::user()->usertype === 'admin') {
                                $columns['location'] = 'Lokasi';
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
                                                    $isActive = request('sort_field', 'product_name') === $field;
                                                @endphp
                                                <a
                                                    href="{{ route('data-bhp', ['sort_field' => $field, 'sort_order' => $newSortOrder]) }}">
                                                    <svg class="w-3 h-3 ms-1.5 {{ $isActive ? 'text-uinOrange' : 'text-white' }}"
                                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                                        fill="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            d="M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </th>
                                    @endforeach
                                    <th scope="col" class="py-3">
                                        Diupdate Oleh
                                    </th>
                                    <th scope="col" class="py-3 text-center">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = ($assetLabs->currentPage() - 1) * $assetLabs->perPage() + 1;
                                @endphp
                                @forelse ($assetLabs as $assetLab)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th class="px-2 py-4 text-center">
                                            {{ $counter }}
                                        </th>
                                        <td scope="row" class="px-2 font-medium whitespace-nowrap">
                                            {{ $assetLab->product_code }}
                                        </td>
                                        <td scope="row" class="px-2 font-medium whitespace-nowrap">
                                            {{ $assetLab->product_name }}
                                        </td>
                                        <td class="px-2">
                                            {{ empty($assetLab->formula) ? '-' : $assetLab->formula }}
                                        </td>
                                        <td class="px-2">
                                            {{ empty($assetLab->merk) ? '-' : $assetLab->merk }}
                                        </td>
                                        <td class="px-2">
                                            {{ $assetLab->product_type }}
                                        </td>
                                        <td class="px-2">
                                            {{ $assetLab->stock }}
                                        </td>
                                        <td class="px-2">
                                            {{ $assetLab->product_unit }}
                                        </td>
                                        <td class="px-2">
                                            {{ empty($assetLab->location_detail) ? '-' : $assetLab->location_detail }}
                                        </td>
                                        @if (Auth::user()->usertype == 'admin')
                                            <td class="px-2">
                                                {{ $assetLab->location }}
                                            </td>
                                        @endif
                                        <td class="px-2">
                                            {{ $assetLab->updater->name }}
                                        </td>
                                        <td class="py-2 flex flex-row gap-x-2 justify-center">
                                            <a href="{{ route('edit-aset', $assetLab->product_code) }}">
                                                <div class="bg-uinOrange p-2 rounded-lg hover:bg-yellow-400">
                                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                        id="Outline" viewBox="0 0 24 24" width="512"
                                                        height="512">
                                                        <path
                                                            d="M18.656.93,6.464,13.122A4.966,4.966,0,0,0,5,16.657V18a1,1,0,0,0,1,1H7.343a4.966,4.966,0,0,0,3.535-1.464L23.07,5.344a3.125,3.125,0,0,0,0-4.414A3.194,3.194,0,0,0,18.656.93Zm3,3L9.464,16.122A3.02,3.02,0,0,1,7.343,17H7v-.343a3.02,3.02,0,0,1,.878-2.121L20.07,2.344a1.148,1.148,0,0,1,1.586,0A1.123,1.123,0,0,1,21.656,3.93Z" />
                                                        <path
                                                            d="M23,8.979a1,1,0,0,0-1,1V15H18a3,3,0,0,0-3,3v4H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2h9.042a1,1,0,0,0,0-2H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H16.343a4.968,4.968,0,0,0,3.536-1.464l2.656-2.658A4.968,4.968,0,0,0,24,16.343V9.979A1,1,0,0,0,23,8.979ZM18.465,21.122a2.975,2.975,0,0,1-1.465.8V18a1,1,0,0,1,1-1h3.925a3.016,3.016,0,0,1-.8,1.464Z" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <form action="{{ route('destroy-aset', $assetLab->product_code) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-uinRed p-2 rounded-lg hover:bg-red-600"><svg
                                                        class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                        id="Outline" viewBox="0 0 24 24" width="512"
                                                        height="512">
                                                        <path
                                                            d="M21,4H17.9A5.009,5.009,0,0,0,13,0H11A5.009,5.009,0,0,0,6.1,4H3A1,1,0,0,0,3,6H4V19a5.006,5.006,0,0,0,5,5h6a5.006,5.006,0,0,0,5-5V6h1a1,1,0,0,0,0-2ZM11,2h2a3.006,3.006,0,0,1,2.829,2H8.171A3.006,3.006,0,0,1,11,2Zm7,17a3,3,0,0,1-3,3H9a3,3,0,0,1-3-3V6H18Z" />
                                                        <path
                                                            d="M10,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,10,18Z" />
                                                        <path
                                                            d="M14,18a1,1,0,0,0,1-1V11a1,1,0,0,0-2,0v6A1,1,0,0,0,14,18Z" />
                                                    </svg></button>
                                            </form>
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @empty
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        @if (Auth::user()->usertype == 'admin')
                                            <th colspan="12" class="px-6 py-4 text-center">
                                                Data Tidak Ditemukan
                                            </th>
                                        @endif
                                        <th colspan="11" class="px-6 py-4 text-center">
                                            Data Tidak Ditemukan
                                        </th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $assetLabs->onEachSide(1)->appends(['search' => request('search')])->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
