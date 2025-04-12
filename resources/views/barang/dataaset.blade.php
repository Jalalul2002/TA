<x-app-layout>
    @php
        $segment = request()->segment(1); // Ambil segment pertama dari URL
        $type = $segment === 'data-aset' ? 'inv' : 'bhp';
    @endphp

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data ') }} {{ $type == 'bhp' ? 'Barang Habis Pakai' : 'Aset Inventaris' }}
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
                            class="flex flex-col md:flex-row space-y-1 md:space-y-0 md:items-center gap-x-1 xl:gap-x-2 justify-end">
                            <form action="{{ $type == 'bhp' ? route('export.bhp') : route('export.inv') }}"
                                method="GET"
                                class="flex flex-col space-y-1 md:space-y-0 md:flex-row md:items-center gap-x-1 xl:gap-x-2">
                                <div class="flex space-x-1 xl:space-x-2">
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
                                            <option value="Teknik Informatika">Teknik Informatika</option>
                                            <option value="Agroteknologi">Agroteknologi</option>
                                            <option value="Teknik Elektro">Teknik Elektro</option>
                                        </select>
                                    @endif
                                </div>
                                @if (Auth::user()->usertype !== 'user')
                                    <a href="{{ $type == 'bhp' ? route('add-aset-bhp') : route('add-aset-inv') }}"
                                        class="inline-flex text-sm items-center px-4 py-2 border border-transparent rounded-md font-semibold text-white bg-uinBlue hover:bg-uinNavy transition duration-300">
                                        <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor" viewBox="0 0 448 512">
                                            <path
                                                d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                        </svg>
                                        Tambah
                                    </a>
                                @endif
                                <button type="submit"
                                    class="inline-flex gap-x-2 text-sm items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-800 transition duration-300">
                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                                        data-name="Layer 1" viewBox="0 0 24 24">
                                        <path
                                            d="M23.971,17.98c-.191,1.175-1.215,2.028-2.436,2.028-.664,0-1.301-.196-1.84-.553-.387-.257-.447-.801-.118-1.129l.002-.002c.236-.235,.602-.283,.886-.11,.316,.191,.689,.298,1.07,.298,.604,0,1.031-.404,1.096-.795,.088-.55-.602-.873-.817-.959-.915-.371-1.751-.78-1.753-.78-.692-.484-1.027-1.231-.921-2.049,.111-.845,.674-1.534,1.471-1.796,.839-.277,1.603-.076,2.152,.203,.405,.205,.518,.733,.24,1.093-.203,.263-.559,.358-.863,.225-.306-.134-.71-.226-1.137-.088-.412,.136-.517,.447-.515,.577,.005,.442,.335,.57,.433,.615,.282,.128,.806,.368,1.366,.595,1.461,.591,1.828,1.745,1.685,2.627Zm-8.368-5.972h0c-.442,0-.8,.358-.8,.8v6.401c0,.442,.358,.8,.8,.8h2.133c.442,0,.8-.358,.8-.8s-.358-.8-.8-.8c-.755,0-1.333,0-1.333,0v-5.601c0-.442-.358-.8-.8-.8Zm-2.612,0c-.307,0-.589,.174-.726,.449l-.864,1.733-.864-1.733c-.137-.275-.418-.449-.726-.449-.602,0-.994,.634-.726,1.173l1.409,2.827-1.409,2.827c-.269,.539,.123,1.173,.726,1.173h0c.307,0,.589-.174,.726-.449l.864-1.733,.864,1.733c.137,.275,.418,.449,.726,.449,.602,0,.994-.634,.726-1.173l-1.409-2.827,1.409-2.827c.269-.539-.123-1.173-.726-1.173Zm7.009,10.992c0,.553-.447,1-1,1H5c-2.757,0-5-2.243-5-5V5C0,2.243,2.243,0,5,0h4.515c1.869,0,3.627,.728,4.95,2.05l3.484,3.486c.888,.887,1.521,2,1.833,3.217,.076,.299,.011,.617-.179,.861s-.481,.387-.79,.387h-5.813c-1.654,0-3-1.346-3-3V2.023c-.16-.015-.322-.023-.485-.023H5c-1.654,0-3,1.346-3,3v14c0,1.654,1.346,3,3,3h14c.553,0,1,.447,1,1ZM12,7c0,.551,.448,1,1,1h4.339c-.22-.382-.489-.736-.804-1.05l-3.484-3.486c-.318-.318-.671-.587-1.051-.805V7Z" />
                                    </svg>
                                    <span>Export</span>
                                </button>
                                <a href="{{ $type == 'bhp' ? route('print.bhp', ['product_type' => request('product_type'), 'location' => request('location')]) : route('print.inv', ['product_type' => request('product_type'), 'location' => request('location')]) }}"
                                    target="_blank"
                                    class="inline-flex gap-x-2 text-sm items-center px-4 py-2 bg-teal-500 border border-transparent rounded-md font-semibold text-white hover:bg-teal-600 transition duration-300">
                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Outline"
                                        viewBox="0 0 24 24" width="512" height="512">
                                        <path
                                            d="M19,6V4a4,4,0,0,0-4-4H9A4,4,0,0,0,5,4V6a5.006,5.006,0,0,0-5,5v5a5.006,5.006,0,0,0,5,5,3,3,0,0,0,3,3h8a3,3,0,0,0,3-3,5.006,5.006,0,0,0,5-5V11A5.006,5.006,0,0,0,19,6ZM7,4A2,2,0,0,1,9,2h6a2,2,0,0,1,2,2V6H7ZM17,21a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V17a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Zm5-5a3,3,0,0,1-3,3V17a3,3,0,0,0-3-3H8a3,3,0,0,0-3,3v2a3,3,0,0,1-3-3V11A3,3,0,0,1,5,8H19a3,3,0,0,1,3,3Z" />
                                        <path d="M18,10H16a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                                    </svg>
                                    <span>Print</span>
                                </a>
                            </form>
                        </div>
                    </div>
                    <div class="mb-1">
                        {{ $assetLabs->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        @php
                            $columns = [
                                'product_code' => 'Kode Barang',
                                'product_name' => 'Nama Barang',
                                'product_detail' => 'Keterangan',
                                'merk' => 'Merk',
                                'product_type' => 'Jenis',
                                'stock' => 'stok',
                                'product_unit' => 'satuan',
                                'purchase_price' => 'Harga Beli',
                                'price' => $type === 'bhp' ? 'Harga Pakai' : 'Harga Sewa',
                                'location_detail' => 'Lokasi Penyimpanan',
                            ];
                            if (Auth::user()->usertype === 'admin' || Auth::user()->usertype === 'user') {
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
                                        <th scope="col" class="py-3 px-2">
                                            <div class="flex items-center justify-between">
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
                                                <a title="Sort by {{ $name }}"
                                                    href="{{ $type === 'bhp' ? route('data-bhp', array_merge(request()->query(), ['sort_field' => $field, 'sort_order' => $newSortOrder])) : route('data-aset', array_merge(request()->query(), ['sort_field' => $field, 'sort_order' => $newSortOrder])) }}">
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
                                        Diupdate Oleh
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
                                    $counter = ($assetLabs->currentPage() - 1) * $assetLabs->perPage() + 1;
                                @endphp
                                @forelse ($assetLabs as $assetLab)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th class="px-2 py-4 text-center">
                                            {{ $counter }}
                                        </th>
                                        <td scope="row" class="px-2 font-medium">
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
                                            {{ $assetLab->formatted_stock }}
                                        </td>
                                        <td class="px-2">
                                            {{ $assetLab->product_unit }}
                                        </td>
                                        <td class="px-2 text-right font-semibold whitespace-nowrap">
                                            <span class="px-2 py-1 text-white rounded-full bg-uinOrange">
                                                Rp.
                                                {{ number_format($assetLab->latestPrice->purchase_price ?? 0, 0, ',', '.') }},-
                                            </span>
                                        </td>
                                        <td class="px-2 text-right font-semibold whitespace-nowrap">
                                            <span class="px-2 py-1 text-white rounded-full bg-uinBlue">
                                                Rp.
                                                {{ number_format($assetLab->latestPrice->price ?? 0, 0, ',', '.') }},-
                                                /{{ optional($assetLab->latestPrice)->price_type
                                                    ? ($assetLab->latestPrice->price_type == 'unit'
                                                        ? 'item'
                                                        : 'jam')
                                                    : ($assetLab->type == 'bhp'
                                                        ? $assetLab->product_unit
                                                        : 'jam') }}
                                            </span>
                                        </td>
                                        <td class="px-2">
                                            {{ empty($assetLab->location_detail) ? '-' : $assetLab->location_detail }}
                                        </td>
                                        @if (Auth::user()->usertype == 'admin' || Auth::user()->usertype === 'user')
                                            <td class="px-2">
                                                {{ $assetLab->location }}
                                            </td>
                                        @endif
                                        <td class="px-2">
                                            {{ $assetLab->updater->name }}
                                        </td>
                                        @if (Auth::user()->usertype !== 'user')
                                            <td class="py-2 flex flex-row gap-x-2 justify-center">
                                                <a href="{{ route('edit-aset', $assetLab->product_code) }}">
                                                    <div
                                                        class="bg-uinOrange p-2 rounded-lg hover:bg-yellow-400 transition duration-300">
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
                                                <form action="{{ route('destroy-aset', $assetLab->product_code) }}"
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
                                            <th colspan="12" class="px-6 py-4 text-center">
                                                Data Tidak Ditemukan
                                            </th>
                                        @else
                                            <th colspan="11" class="px-6 py-4 text-center">
                                                Data Tidak Ditemukan
                                            </th>
                                        @endif
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $assetLabs->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
