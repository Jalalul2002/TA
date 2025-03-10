<x-app-layout>
    @php
        $kodeBarang = [
            '' => '700',
            'Matematika' => '701',
            'Biologi' => '702',
            'Fisika' => '703',
            'Kimia' => '704',
            'Teknik Informatika' => '705',
            'Agroteknologi' => '706',
            'Teknik Elektro' => '707',
        ];
        $defaultKode = $kodeBarang[$dataPerencanaan->prodi] ?? '700';
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ $dataPerencanaan->type === 'bhp' ? route('perencanaan-bhp') : route('perencanaan-inv') }}">
                {{ __('◀️ Detail Perencanaan Barang') }}
            </a>
        </h2>
    </x-slot>
    <form id="complete-form" action="{{ route('perencanaan.complete', $dataPerencanaan->id) }}" method="POST"
        style="display: none;" hidden>
        @csrf
    </form>
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
    <div class="py-8" x-data="{ openEdit: false }">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-6 mb-6 border border-gray-200">
                <div class="flex flex-col space-y-4">
                    <div class="flex justify-between items-center border-b pb-4">
                        <h1 class="text-2xl font-bold text-gray-900">Detail Perencanaan
                            {{ $dataPerencanaan->type === 'bhp' ? 'Barang Habis Pakai' : 'Aset Inventaris' }}</h1>
                        <span
                            class="text-sm font-bold rounded-full px-4 py-2
                            {{ $dataPerencanaan->status == 'belum' ? 'bg-red-100 text-red-500' : 'bg-green-100 text-green-500' }}">{{ $dataPerencanaan->status == 'belum' ? 'Belum Diselesaikan' : 'Sudah Diselesaikan' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="flex items-center gap-x-8">
                            <div class="">
                                <h2 class="text-gray-500 text-sm font-medium">Perencanaan</h2>
                                <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                    {{ "{$dataPerencanaan->nama_perencanaan} - Prodi {$dataPerencanaan->prodi}" }}
                                </h1>
                            </div>
                            @if ($dataPerencanaan->status === 'belum' && Auth::user()->usertype !== 'user')
                                <button title="Edit Perencanaan" @click="openEdit = true"
                                    class="relative gap-x-2 items-end text-gray-300 hover:text-gray-700 fill-gray-300 hover:fill-gray-700">
                                    <svg class="size-5" xmlns="http://www.w3.org/2000/svg" id="Outline"
                                        viewBox="0 0 24 24" width="512" height="512">
                                        <path
                                            d="M22.853,1.148a3.626,3.626,0,0,0-5.124,0L1.465,17.412A4.968,4.968,0,0,0,0,20.947V23a1,1,0,0,0,1,1H3.053a4.966,4.966,0,0,0,3.535-1.464L22.853,6.271A3.626,3.626,0,0,0,22.853,1.148ZM5.174,21.122A3.022,3.022,0,0,1,3.053,22H2V20.947a2.98,2.98,0,0,1,.879-2.121L15.222,6.483l2.3,2.3ZM21.438,4.857,18.932,7.364l-2.3-2.295,2.507-2.507a1.623,1.623,0,1,1,2.295,2.3Z" />
                                    </svg>
                                    <span class="absolute top-3 left-2 text-xs">edit</span>
                                </button>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-gray-500 text-sm font-medium">Diupdate</h2>
                            <h1 class="text-lg font-semibold text-gray-900">
                                {{ ($dataPerencanaan->latestUpdater?->updater?->name ?? $dataPerencanaan->updater->name) . ' | ' . ($dataPerencanaan->latestUpdater?->updated_at->format('d M Y H:i') ?? $dataPerencanaan->updated_at->format('d M Y H:i')) }}
                            </h1>
                        </div>
                        @if (Auth::user()->usertype !== 'user')
                            @if ($dataPerencanaan->status === 'belum')
                                <div class="flex justify-end flex-row items-center">
                                    <div>
                                        <button
                                            onclick="event.preventDefault(); document.getElementById('complete-form').submit();"
                                            class="inline-flex items-center px-4 py-2 gap-x-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-green-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 duration-300 transition-all">
                                            <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                id="Outline" viewBox="0 0 24 24" width="512" height="512">
                                                <path
                                                    d="M12,10a4,4,0,1,0,4,4A4,4,0,0,0,12,10Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,16Z" />
                                                <path
                                                    d="M22.536,4.122,19.878,1.464A4.966,4.966,0,0,0,16.343,0H5A5.006,5.006,0,0,0,0,5V19a5.006,5.006,0,0,0,5,5H19a5.006,5.006,0,0,0,5-5V7.657A4.966,4.966,0,0,0,22.536,4.122ZM17,2.08V3a3,3,0,0,1-3,3H10A3,3,0,0,1,7,3V2h9.343A2.953,2.953,0,0,1,17,2.08ZM22,19a3,3,0,0,1-3,3H5a3,3,0,0,1-3-3V5A3,3,0,0,1,5,2V3a5.006,5.006,0,0,0,5,5h4a4.991,4.991,0,0,0,4.962-4.624l2.16,2.16A3.02,3.02,0,0,1,22,7.657Z" />
                                            </svg>
                                            <span>Selesaikan Perencanaan</span>
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Diselesaikan</h2>
                                    <h1 class="text-lg font-semibold text-gray-900">
                                        {{ $dataPerencanaan->updater->name . ' | ' . $dataPerencanaan->updated_at->format('d M Y H:i') }}
                                    </h1>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-6">
                    <div
                        class="flex flex-col lg:flex-row flex-wrap space-y-1 lg:space-y-0 lg:items-center justify-between pb-2">
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
                        <div class="flex items-center space-x-1 xl:space-x-2 justify-end">
                            <a href="{{ route('perencanaan.download', $dataPerencanaan->id) }}">
                                <div
                                    class="inline-flex gap-x-2 items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 duration-300 transition-all">
                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                                        data-name="Layer 1" viewBox="0 0 24 24">
                                        <path
                                            d="M23.971,17.98c-.191,1.175-1.215,2.028-2.436,2.028-.664,0-1.301-.196-1.84-.553-.387-.257-.447-.801-.118-1.129l.002-.002c.236-.235,.602-.283,.886-.11,.316,.191,.689,.298,1.07,.298,.604,0,1.031-.404,1.096-.795,.088-.55-.602-.873-.817-.959-.915-.371-1.751-.78-1.753-.78-.692-.484-1.027-1.231-.921-2.049,.111-.845,.674-1.534,1.471-1.796,.839-.277,1.603-.076,2.152,.203,.405,.205,.518,.733,.24,1.093-.203,.263-.559,.358-.863,.225-.306-.134-.71-.226-1.137-.088-.412,.136-.517,.447-.515,.577,.005,.442,.335,.57,.433,.615,.282,.128,.806,.368,1.366,.595,1.461,.591,1.828,1.745,1.685,2.627Zm-8.368-5.972h0c-.442,0-.8,.358-.8,.8v6.401c0,.442,.358,.8,.8,.8h2.133c.442,0,.8-.358,.8-.8s-.358-.8-.8-.8c-.755,0-1.333,0-1.333,0v-5.601c0-.442-.358-.8-.8-.8Zm-2.612,0c-.307,0-.589,.174-.726,.449l-.864,1.733-.864-1.733c-.137-.275-.418-.449-.726-.449-.602,0-.994,.634-.726,1.173l1.409,2.827-1.409,2.827c-.269,.539,.123,1.173,.726,1.173h0c.307,0,.589-.174,.726-.449l.864-1.733,.864,1.733c.137,.275,.418,.449,.726,.449,.602,0,.994-.634,.726-1.173l-1.409-2.827,1.409-2.827c.269-.539-.123-1.173-.726-1.173Zm7.009,10.992c0,.553-.447,1-1,1H5c-2.757,0-5-2.243-5-5V5C0,2.243,2.243,0,5,0h4.515c1.869,0,3.627,.728,4.95,2.05l3.484,3.486c.888,.887,1.521,2,1.833,3.217,.076,.299,.011,.617-.179,.861s-.481,.387-.79,.387h-5.813c-1.654,0-3-1.346-3-3V2.023c-.16-.015-.322-.023-.485-.023H5c-1.654,0-3,1.346-3,3v14c0,1.654,1.346,3,3,3h14c.553,0,1,.447,1,1ZM12,7c0,.551,.448,1,1,1h4.339c-.22-.382-.489-.736-.804-1.05l-3.484-3.486c-.318-.318-.671-.587-1.051-.805V7Z" />
                                    </svg>
                                    Export
                                </div>
                            </a>
                            <a href="{{ route('perencanaan.print', $dataPerencanaan->id) }}" target="_blank">
                                <div
                                    class="inline-flex gap-x-2 items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-fuchsia-500 hover:bg-fuchsia-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 duration-300 transition-all">
                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Outline"
                                        viewBox="0 0 24 24" width="512" height="512">
                                        <path
                                            d="M19,6V4a4,4,0,0,0-4-4H9A4,4,0,0,0,5,4V6a5.006,5.006,0,0,0-5,5v5a5.006,5.006,0,0,0,5,5,3,3,0,0,0,3,3h8a3,3,0,0,0,3-3,5.006,5.006,0,0,0,5-5V11A5.006,5.006,0,0,0,19,6ZM7,4A2,2,0,0,1,9,2h6a2,2,0,0,1,2,2V6H7ZM17,21a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V17a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Zm5-5a3,3,0,0,1-3,3V17a3,3,0,0,0-3-3H8a3,3,0,0,0-3,3v2a3,3,0,0,1-3-3V11A3,3,0,0,1,5,8H19a3,3,0,0,1,3,3Z" />
                                        <path d="M18,10H16a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                                    </svg>
                                    Print
                                </div>
                            </a>
                            @if ($dataPerencanaan->status === 'belum' && Auth::user()->usertype !== 'user')
                                <button @click="openModal = true"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-uinBlue hover:bg-uinNavy focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 duration-300 transition-all">
                                    <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 448 512">
                                        <path
                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                    </svg>
                                    Tambah
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="pb-1">
                        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            @php
                                $columns = [
                                    'product_code' => 'Kode Barang',
                                    'product_name' => 'Nama Barang',
                                    'product_detail' => 'Keterangan/Formula',
                                    'merk' => 'Merk',
                                    'product_type' => 'Jenis Produk',
                                    'stock' => 'stok',
                                    'jumlah_kebutuhan' => 'Jumlah Kebutuhan',
                                    'product_unit' => 'satuan',
                                ];
                            @endphp
                            <thead class="text-xs text-white uppercase bg-uinTosca">
                                <tr>
                                    <th scope="col" class="px-1 text-center py-3">
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
                                                    $isActive = request('sort_field', 'jumlah_kebutuhan') === $field;
                                                @endphp
                                                <a title="Sort by {{ $name }}"
                                                    href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['sort_field' => $field, 'sort_order' => $newSortOrder])) }}">
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
                                    @if ($dataPerencanaan->status === 'belum' && Auth::user()->usertype !== 'user')
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
                                            {{ empty($product->product->product_detail) ? '-' : $product->product->product_detail }}
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
                                        @if ($dataPerencanaan->status === 'belum' && Auth::user()->usertype !== 'user')
                                            <td class="py-2 flex flex-row gap-x-2 justify-center">
                                                <a title="Edit Data"
                                                    href="{{ route('rencana.edit-rencana', $product->id) }}">
                                                    <div
                                                        class="bg-uinOrange p-2 rounded-lg hover:bg-yellow-400 transition-all duration-300">
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
                                                        class="bg-uinRed p-2 rounded-lg hover:bg-red-600 transition-all duration-300"><svg
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
                        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
            {{-- Edit Perencanaan Modal --}}
            <div>
                <div x-show="openEdit" x-cloak class="fixed z-50 inset-0 overflow-y-auto">
                    <div
                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="openEdit" x-cloak class="fixed inset-0 transition-opacity" aria-hidden="true">
                            <div class="absolute inset-0 bg-gray-500 opacity-75" @click="openEdit = false"></div>
                        </div>
                        <!-- This element is to trick the browser into centering the modal contents. -->
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                            aria-hidden="true">&#8203;</span>
                        <div x-show="openEdit" x-cloak
                            class="inline-block align-bottom bg-white rounded-3xl px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                            <div class="mt-3">
                                <h3 class="text-3xl font-semibold text-gray-900 py-5">
                                    📝 Edit Nama Perencanaan
                                </h3>
                                <div>
                                    <form action="{{ route('rencana.update-detail', $dataPerencanaan->id) }}"
                                        method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mt-4">
                                            <label for="nama_perencanaan"
                                                class="block text-sm font-medium text-gray-700">Nama
                                                Perencanaan</label>
                                            <input type="text" name="nama_perencanaan" id="nama_perencanaan"
                                                value="{{ $dataPerencanaan->nama_perencanaan }}"
                                                class="mt-1 block w-full p-2.5 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                required>
                                        </div>
                                        <div class="mt-6 flex flex-row justify-end gap-x-2">
                                            <button type="button" @click="openEdit = false"
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
            <!-- Item Modal -->
            <div>
                <div x-show="openModal" x-cloak class="fixed z-50 inset-0 overflow-y-auto">
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
                            <div x-data="{ selectedProduct: '', stock: 0, satuan: '', showNewProductForm: false }">
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
                                                    @change="stock = $el.selectedOptions[0].dataset.stock; satuan = $el.selectedOptions[0].dataset.satuan; showNewProductForm = selectedProduct === 'new';"
                                                    required
                                                    class="mt-1 block w-full p-2.5 py-2 text-sm border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                    <option value="">Select Product</option>
                                                    <option value="new">+ Tambah Data Baru</option>
                                                    @foreach ($assets as $product)
                                                        <option value="{{ $product->product_code }}"
                                                            data-stock="{{ $product->stock }}"
                                                            data-satuan="{{ $product->product_unit }}">
                                                            {{ $product->product_name }}
                                                            ({{ $product->product_code }})
                                                            {{ empty($product->product_detail) ? '' : '(' . $product->product_detail . ')' }}
                                                            {{ empty($product->merk) ? '' : '(' . $product->merk . ')' }}
                                                            {{ empty($product->product_type) ? '' : '(' . $product->product_type . ')' }}
                                                            ({{ $product->product_unit }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- Form Input Tambah Barang Baru -->
                                            <div x-show="showNewProductForm" class="mt-4 bg-gray-100 p-4 rounded-md">
                                                <h3 class="text-sm font-semibold text-gray-700">Tambah Barang Baru</h3>
                                                <div class="mt-2">
                                                    <label for="new_product_code"
                                                        class="block text-sm font-medium text-gray-700">Kode
                                                        Barang</label>
                                                    <div class="flex items-center">
                                                        <input type="text" name="new_initial_code"
                                                            id="new_initial_code" value="{{ $defaultKode }}"
                                                            readonly
                                                            class="mt-1 block w-1/6 p-2.5 py-2 text-sm border-gray-300 rounded-md">
                                                        <p> - </p>
                                                        <input type="text" name="new_product_code"
                                                            id="new_product_code" x-bind:required="showNewProductForm"
                                                            class="mt-1 block w-full p-2.5 py-2 text-sm border-gray-300 rounded-md">
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <label for="new_product_name"
                                                        class="block text-sm font-medium text-gray-700">Nama
                                                        Barang</label>
                                                    <input type="text" name="new_product_name"
                                                        id="new_product_name" x-bind:required="showNewProductForm"
                                                        class="mt-1 block w-full p-2.5 py-2 text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div class="mt-2">
                                                    <label for="new_product_detail"
                                                        class="block text-sm font-medium text-gray-700">Keterangan
                                                        (Optional)</label>
                                                    <input type="text" name="new_product_detail"
                                                        id="new_product_detail"
                                                        class="mt-1 block w-full p-2.5 py-2 text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div class="mt-2">
                                                    <label for="new_merk"
                                                        class="block text-sm font-medium text-gray-700">Merk
                                                        (Optional)</label>
                                                    <input type="text" name="new_merk" id="new_merk"
                                                        class="mt-1 block w-full p-2.5 py-2 text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div class="mt-2">
                                                    <label for="new_type"
                                                        class="block text-sm font-medium text-gray-700">Tipe</label>
                                                    <input type="text" name="new_type" id="new_type" readonly
                                                        value="{{ $dataPerencanaan->type }}"
                                                        class="mt-1 block w-full p-2.5 py-2 text-sm border-gray-300 rounded-md">
                                                </div>
                                                <div class="mt-2">
                                                    <label for="new_product_type"
                                                        class="block text-sm font-medium text-gray-700">Jenis</label>
                                                    <select id="new_product_type" name="new_product_type"
                                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mt-1"
                                                        x-bind:required="showNewProductForm">
                                                        <option value="">-- Pilih Jenis Produk --</option>
                                                        <option value="Cairan">Cairan</option>
                                                        <option value="Padatan">Padatan</option>
                                                        <option value="Lainnya">Lainnya</option>
                                                    </select>
                                                </div>
                                                <div class="mt-2">
                                                    <label for="new_location"
                                                        class="block text-sm font-medium text-gray-700">Lokasi</label>
                                                    <input type="text" name="new_location" id="new_location"
                                                        value="{{ $dataPerencanaan->prodi }}" readonly
                                                        class="mt-1 block w-full p-2.5 py-2 text-sm border-gray-300 rounded-md">
                                                </div>
                                            </div>
                                            <div class="mt-4">
                                                <label for="stock"
                                                    class="block text-sm font-medium text-gray-700">Stok</label>
                                                <input type="number" name="stock" id="stock" x-model="stock"
                                                    value="0"
                                                    class="mt-1 block w-full p-2.5 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                                                    :readonly="!showNewProductForm">
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
                                                    <template x-if="!showNewProductForm">
                                                        <input type="text" name="satuan" id="satuan"
                                                            x-model="satuan"
                                                            class="mt-1 block w-full px-2.5 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                                    </template>
                                                    <template x-if="showNewProductForm">
                                                        <select id="product_unit" name="product_unit"
                                                            x-model="satuan"
                                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 py-2 mt-1"
                                                            x-bind:required="showNewProductForm">
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
                                                            @foreach ($units as $unit)
                                                                <option value="{{ $unit }}">
                                                                    {{ $unit }}</option>
                                                            @endforeach
                                                        </select>
                                                    </template>
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
