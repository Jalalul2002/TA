<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Peminjaman Barang') }}
        </h2>
    </x-slot>
    @php
        $user = Auth::user()->usertype;
    @endphp
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
                    <div class="flex flex-col-reverse gap-2 justify-between pb-4">
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
                                class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 md:w-64 xl:w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Search for items" autocomplete="off">
                        </div>
                        <div class="z-10 flex gap-2 flex-wrap justify-end" x-data="filterPenggunaan">
                            {{-- Filter Lokasi --}}
                            @if ($user === 'admin')
                                <div
                                    class="flex flex-row items-center bg-white rounded-lg w-fit border border-gray-300 ps-2">
                                    <svg class="size-4 fill-gray-400" xmlns="http://www.w3.org/2000/svg" id="Outline"
                                        viewBox="0 0 24 24" width="512" height="512">
                                        <path
                                            d="M12,6a4,4,0,1,0,4,4A4,4,0,0,0,12,6Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,12Z" />
                                        <path
                                            d="M12,24a5.271,5.271,0,0,1-4.311-2.2c-3.811-5.257-5.744-9.209-5.744-11.747a10.055,10.055,0,0,1,20.11,0c0,2.538-1.933,6.49-5.744,11.747A5.271,5.271,0,0,1,12,24ZM12,2.181a7.883,7.883,0,0,0-7.874,7.874c0,2.01,1.893,5.727,5.329,10.466a3.145,3.145,0,0,0,5.09,0c3.436-4.739,5.329-8.456,5.329-10.466A7.883,7.883,0,0,0,12,2.181Z" />
                                    </svg>
                                    <select x-model="location" @change="updateFilter('location', location)"
                                        class="font-medium text-sm text-gray-700 bg-transparent border-none focus:ring-0 cursor-pointer ps-1">
                                        @foreach ($locations as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ request('location') == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            {{-- Filter Tujuan --}}
                            <div
                                class="flex flex-row items-center bg-white rounded-lg w-fit border border-gray-300 ps-2">
                                <svg class="size-4 fill-gray-400" id="Layer_1" height="512" viewBox="0 0 24 24"
                                    width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                                    <path
                                        d="m17 14a1 1 0 0 1 -1 1h-8a1 1 0 0 1 0-2h8a1 1 0 0 1 1 1zm-4 3h-5a1 1 0 0 0 0 2h5a1 1 0 0 0 0-2zm9-6.515v8.515a5.006 5.006 0 0 1 -5 5h-10a5.006 5.006 0 0 1 -5-5v-14a5.006 5.006 0 0 1 5-5h4.515a6.958 6.958 0 0 1 4.95 2.05l3.484 3.486a6.951 6.951 0 0 1 2.051 4.949zm-6.949-7.021a5.01 5.01 0 0 0 -1.051-.78v4.316a1 1 0 0 0 1 1h4.316a4.983 4.983 0 0 0 -.781-1.05zm4.949 7.021c0-.165-.032-.323-.047-.485h-4.953a3 3 0 0 1 -3-3v-4.953c-.162-.015-.321-.047-.485-.047h-4.515a3 3 0 0 0 -3 3v14a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3z" />
                                </svg>
                                <select x-model="purpose" @change="updateFilter('purpose', purpose)"
                                    class="font-medium text-sm text-gray-700 bg-transparent border-none focus:ring-0 cursor-pointer ps-1">
                                    <option value="semua" {{ request('purpose') == 'semua' ? 'selected' : '' }}>
                                        Semua Keperluan
                                    </option>
                                    <option value="praktikum" {{ request('purpose') == 'praktikum' ? 'selected' : '' }}>
                                        Praktikum
                                    </option>
                                    <option value="penelitian"
                                        {{ request('purpose') == 'penelitian' ? 'selected' : '' }}>
                                        Penelitian
                                    </option>
                                </select>
                            </div>
                            {{-- Filter User --}}
                            <div
                                class="flex flex-row items-center bg-white rounded-lg w-fit border border-gray-300 ps-2">
                                <svg class="size-4 fill-gray-400" xmlns="http://www.w3.org/2000/svg" id="Outline"
                                    viewBox="0 0 24 24" width="512" height="512">
                                    <path
                                        d="M12,12A6,6,0,1,0,6,6,6.006,6.006,0,0,0,12,12ZM12,2A4,4,0,1,1,8,6,4,4,0,0,1,12,2Z" />
                                    <path
                                        d="M12,14a9.01,9.01,0,0,0-9,9,1,1,0,0,0,2,0,7,7,0,0,1,14,0,1,1,0,0,0,2,0A9.01,9.01,0,0,0,12,14Z" />
                                </svg>
                                <select x-model="userId" @change="updateFilter('user_id', userId)"
                                    class="font-medium text-sm text-gray-700 bg-transparent border-none focus:ring-0 cursor-pointer ps-1">
                                    <option value="semua" {{ request('user_id') == 'semua' ? 'selected' : '' }}>
                                        Semua Pengguna
                                    </option>
                                    @foreach ($filterUser as $data)
                                        <option value="{{ $data->user_id }}"
                                            {{ request('user_id') == $data->user_id ? 'selected' : '' }}>
                                            {{ $data->user_id }} - {{ $data->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Filter Tanggal --}}
                            <div class="flex flex-row items-center gap-x-2">
                                <input type="date" x-model="startDate"
                                    @change="updateFilter('start_date', startDate)"
                                    class="border border-gray-300 text-gray-900 rounded-lg text-sm p-2"
                                    max="{{ now()->format('Y-m-d') }}">
                                <span>-</span>
                                <input type="date" x-model="endDate" @change="updateFilter('end_date', endDate)"
                                    class="border border-gray-300 text-gray-900 rounded-lg text-sm p-2"
                                    :min="startDate" max="{{ now()->format('Y-m-d') }}">
                            </div>
                            @if ($user !== 'user')
                                <a href="{{ route('add-peminjaman') }}"
                                    class="inline-flex text-sm items-center px-4 py-2 border border-transparent rounded-md font-semibold text-white bg-uinBlue hover:bg-uinNavy transition-all duration-300">
                                    <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 448 512">
                                        <path
                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                    </svg>
                                    Tambah
                                </a>
                            @endif
                            <a title="Download Data"
                                href="{{ route('export.transaction.inv', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'location' => request('location'), 'purpose' => request('purpose'), 'user_id' => request('user_id')]) }}">
                                <div
                                    class="inline-flex text-sm items-center px-4 py-2 border border-transparent rounded-md font-semibold text-white bg-teal-500 hover:bg-teal-700 transition-all duration-300">
                                    <svg class="size-4 fill-white me-2" xmlns="http://www.w3.org/2000/svg"
                                        id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
                                        <path
                                            d="M23.971,17.98c-.191,1.175-1.215,2.028-2.436,2.028-.664,0-1.301-.196-1.84-.553-.387-.257-.447-.801-.118-1.129l.002-.002c.236-.235,.602-.283,.886-.11,.316,.191,.689,.298,1.07,.298,.604,0,1.031-.404,1.096-.795,.088-.55-.602-.873-.817-.959-.915-.371-1.751-.78-1.753-.78-.692-.484-1.027-1.231-.921-2.049,.111-.845,.674-1.534,1.471-1.796,.839-.277,1.603-.076,2.152,.203,.405,.205,.518,.733,.24,1.093-.203,.263-.559,.358-.863,.225-.306-.134-.71-.226-1.137-.088-.412,.136-.517,.447-.515,.577,.005,.442,.335,.57,.433,.615,.282,.128,.806,.368,1.366,.595,1.461,.591,1.828,1.745,1.685,2.627Zm-8.368-5.972h0c-.442,0-.8,.358-.8,.8v6.401c0,.442,.358,.8,.8,.8h2.133c.442,0,.8-.358,.8-.8s-.358-.8-.8-.8c-.755,0-1.333,0-1.333,0v-5.601c0-.442-.358-.8-.8-.8Zm-2.612,0c-.307,0-.589,.174-.726,.449l-.864,1.733-.864-1.733c-.137-.275-.418-.449-.726-.449-.602,0-.994,.634-.726,1.173l1.409,2.827-1.409,2.827c-.269,.539,.123,1.173,.726,1.173h0c.307,0,.589-.174,.726-.449l.864-1.733,.864,1.733c.137,.275,.418,.449,.726,.449,.602,0,.994-.634,.726-1.173l-1.409-2.827,1.409-2.827c.269-.539-.123-1.173-.726-1.173Zm7.009,10.992c0,.553-.447,1-1,1H5c-2.757,0-5-2.243-5-5V5C0,2.243,2.243,0,5,0h4.515c1.869,0,3.627,.728,4.95,2.05l3.484,3.486c.888,.887,1.521,2,1.833,3.217,.076,.299,.011,.617-.179,.861s-.481,.387-.79,.387h-5.813c-1.654,0-3-1.346-3-3V2.023c-.16-.015-.322-.023-.485-.023H5c-1.654,0-3,1.346-3,3v14c0,1.654,1.346,3,3,3h14c.553,0,1,.447,1,1ZM12,7c0,.551,.448,1,1,1h4.339c-.22-.382-.489-.736-.804-1.05l-3.484-3.486c-.318-.318-.671-.587-1.051-.805V7Z" />
                                    </svg>
                                    Download
                                </div>
                            </a>
                            <a href="{{ route('print.peminjaman', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'location' => request('location'), 'purpose' => request('purpose'), 'user_id' => request('user_id')]) }}"
                                target="_blank">
                                <div
                                    class="inline-flex text-sm items-center px-4 py-2 border border-transparent rounded-md font-semibold text-white bg-zinc-500 hover:bg-fuchsia-700 transition-all duration-300">
                                    <svg class="size-4 me-2 fill-white" xmlns="http://www.w3.org/2000/svg"
                                        id="Outline" viewBox="0 0 24 24" width="512" height="512">
                                        <path
                                            d="M19,6V4a4,4,0,0,0-4-4H9A4,4,0,0,0,5,4V6a5.006,5.006,0,0,0-5,5v5a5.006,5.006,0,0,0,5,5,3,3,0,0,0,3,3h8a3,3,0,0,0,3-3,5.006,5.006,0,0,0,5-5V11A5.006,5.006,0,0,0,19,6ZM7,4A2,2,0,0,1,9,2h6a2,2,0,0,1,2,2V6H7ZM17,21a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V17a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Zm5-5a3,3,0,0,1-3,3V17a3,3,0,0,0-3-3H8a3,3,0,0,0-3,3v2a3,3,0,0,1-3-3V11A3,3,0,0,1,5,8H19a3,3,0,0,1,3,3Z" />
                                        <path d="M18,10H16a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                                    </svg>
                                    Cetak
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            @php
                                $columns = [
                                    'created_at' => 'Tanggal Dibuat',
                                    'purpose' => 'Keperluan',
                                    'user_id' => 'ID Pengguna',
                                    'name' => 'Nama Pengguna',
                                    'prodi' => 'Prodi Pengguna',
                                    'telp' => 'Nomor Telepon',
                                    'items' => 'Status',
                                    'total_price' => 'Total Harga',
                                    'created_by' => 'Pembuat',
                                ];
                                if (Auth::user()->usertype !== 'staff') {
                                    $columns['location'] = 'Program Studi';
                                }
                            @endphp
                            <thead class="text-xs text-white uppercase bg-uinTosca">
                                <tr>
                                    <th scope="col" class="text-center px-2 py-3">
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
                                                    $isActive = request('sort_field', 'created_at') === $field;
                                                @endphp
                                                <a title="Sort by {{ $name }}" class="px-2"
                                                    href="{{ route('peminjaman', array_merge(request()->query(), ['sort_field' => $field, 'sort_order' => $newSortOrder])) }}">
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
                                    <th scope="col" class="text-center px-2 py-3 size-fit">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
                                @endphp
                                @forelse ($transactions as $peminjaman)
                                    @php
                                        $isCompleted =
                                            $peminjaman->total_returned_quantity == $peminjaman->total_loan_quantity;
                                        $statusColor = $isCompleted
                                            ? 'bg-green-300 text-green-700'
                                            : 'bg-red-300 text-red-700';
                                    @endphp
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th class="text-center px-2 py-2">
                                            {{ $counter }}
                                        </th>
                                        <td class="px-2 py-2">
                                            {{ $peminjaman->created_at->format('d/m/Y') }}
                                        </td>
                                        <td scope="row" class="px-2 py-2 font-medium whitespace-nowrap capitalize">
                                            {{ $peminjaman->purpose }}
                                        </td>
                                        <td scope="row" class="px-2 py-2 font-medium whitespace-nowrap">
                                            {{ $peminjaman->user_id }}
                                        </td>
                                        <td scope="row" class="px-2 py-2 font-medium whitespace-nowrap">
                                            {{ $peminjaman->name }}
                                        </td>
                                        <td scope="row" class="px-2 py-2 font-medium whitespace-nowrap">
                                            {{ $peminjaman->prodi }}
                                        </td>
                                        <td scope="row" class="px-2 py-2 font-medium whitespace-nowrap">
                                            {{ $peminjaman->telp }}
                                        </td>
                                        <td class="px-2 py-2">
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                                {{ intval($peminjaman->total_returned_quantity ?? 0) }} /
                                                {{ intval($peminjaman->total_loan_quantity ?? 0) }}
                                            </span>
                                        </td>
                                        <td class="px-2 font-semibold text-right whitespace-nowrap">
                                            <span class="px-2 py-1 text-white rounded-full bg-teal-500">
                                                Rp.
                                                {{ number_format($peminjaman->total_loan_price ?? 0, 0, ',', '.') }},-
                                            </span>
                                        </td>
                                        <td class="px-2 py-2">
                                            {{ $peminjaman->creator->name }}
                                        </td>
                                        @if (Auth::user()->usertype !== 'staff')
                                            <td class="px-2 py-2">
                                                {{ $peminjaman->location }}
                                            </td>
                                        @endif
                                        <td class="py-2 flex flex-row gap-x-1 justify-center">
                                            <a title="Lihat Detail"
                                                href="{{ route('detail-peminjaman', $peminjaman->id) }}">
                                                <div
                                                    class="bg-amber-500 p-2 rounded-lg hover:bg-amber-700 transition-all duration-300">
                                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                        id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
                                                        <path
                                                            d="m8,16c0,.828-.672,1.5-1.5,1.5s-1.5-.672-1.5-1.5.672-1.5,1.5-1.5,1.5.672,1.5,1.5Zm-1.5-11.5c-.828,0-1.5.672-1.5,1.5s.672,1.5,1.5,1.5,1.5-.672,1.5-1.5-.672-1.5-1.5-1.5Zm0,5c-.828,0-1.5.672-1.5,1.5s.672,1.5,1.5,1.5,1.5-.672,1.5-1.5-.672-1.5-1.5-1.5ZM19,0H5C2.243,0,0,2.243,0,5v13c0,2.757,2.243,5,5,5h3c.552,0,1-.447,1-1s-.448-1-1-1h-3c-1.654,0-3-1.346-3-3V5c0-1.654,1.346-3,3-3h14c1.654,0,3,1.346,3,3v9c0,.553.448,1,1,1s1-.447,1-1V5c0-2.757-2.243-5-5-5Zm-8,7h7c.552,0,1-.448,1-1s-.448-1-1-1h-7c-.552,0-1,.448-1,1s.448,1,1,1Zm0,5h7c.552,0,1-.448,1-1s-.448-1-1-1h-7c-.552,0-1,.448-1,1s.448,1,1,1Zm12.705,6.549c.391.578.391,1.324,0,1.902-.896,1.325-2.959,3.549-6.705,3.549s-5.809-2.224-6.706-3.549c-.391-.579-.391-1.325,0-1.902.896-1.325,2.958-3.549,6.705-3.549s5.809,2.224,6.705,3.549Zm-1.775.951c-.73-1.006-2.263-2.5-4.93-2.5s-4.201,1.495-4.93,2.5c.729,1.006,2.263,2.5,4.93,2.5s4.2-1.494,4.93-2.5Zm-4.93-1.5c-.828,0-1.5.672-1.5,1.5s.672,1.5,1.5,1.5,1.5-.672,1.5-1.5-.672-1.5-1.5-1.5Z" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <a title="Download Data"
                                                href="{{ route('peminjaman.export.id', $peminjaman->id) }}">
                                                <div
                                                    class="bg-teal-500 p-2 rounded-lg hover:bg-teal-700 transition-all duration-300">
                                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                        id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24">
                                                        <path
                                                            d="M23.971,17.98c-.191,1.175-1.215,2.028-2.436,2.028-.664,0-1.301-.196-1.84-.553-.387-.257-.447-.801-.118-1.129l.002-.002c.236-.235,.602-.283,.886-.11,.316,.191,.689,.298,1.07,.298,.604,0,1.031-.404,1.096-.795,.088-.55-.602-.873-.817-.959-.915-.371-1.751-.78-1.753-.78-.692-.484-1.027-1.231-.921-2.049,.111-.845,.674-1.534,1.471-1.796,.839-.277,1.603-.076,2.152,.203,.405,.205,.518,.733,.24,1.093-.203,.263-.559,.358-.863,.225-.306-.134-.71-.226-1.137-.088-.412,.136-.517,.447-.515,.577,.005,.442,.335,.57,.433,.615,.282,.128,.806,.368,1.366,.595,1.461,.591,1.828,1.745,1.685,2.627Zm-8.368-5.972h0c-.442,0-.8,.358-.8,.8v6.401c0,.442,.358,.8,.8,.8h2.133c.442,0,.8-.358,.8-.8s-.358-.8-.8-.8c-.755,0-1.333,0-1.333,0v-5.601c0-.442-.358-.8-.8-.8Zm-2.612,0c-.307,0-.589,.174-.726,.449l-.864,1.733-.864-1.733c-.137-.275-.418-.449-.726-.449-.602,0-.994,.634-.726,1.173l1.409,2.827-1.409,2.827c-.269,.539,.123,1.173,.726,1.173h0c.307,0,.589-.174,.726-.449l.864-1.733,.864,1.733c.137,.275,.418,.449,.726,.449,.602,0,.994-.634,.726-1.173l-1.409-2.827,1.409-2.827c.269-.539-.123-1.173-.726-1.173Zm7.009,10.992c0,.553-.447,1-1,1H5c-2.757,0-5-2.243-5-5V5C0,2.243,2.243,0,5,0h4.515c1.869,0,3.627,.728,4.95,2.05l3.484,3.486c.888,.887,1.521,2,1.833,3.217,.076,.299,.011,.617-.179,.861s-.481,.387-.79,.387h-5.813c-1.654,0-3-1.346-3-3V2.023c-.16-.015-.322-.023-.485-.023H5c-1.654,0-3,1.346-3,3v14c0,1.654,1.346,3,3,3h14c.553,0,1,.447,1,1ZM12,7c0,.551,.448,1,1,1h4.339c-.22-.382-.489-.736-.804-1.05l-3.484-3.486c-.318-.318-.671-.587-1.051-.805V7Z" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <a href="{{ route('peminjaman.print.id', $peminjaman->id) }}"
                                                target="_blank">
                                                <div
                                                    class="bg-fuchsia-500 p-2 rounded-lg hover:bg-fuchsia-700 transition-all duration-300">
                                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                        id="Outline" viewBox="0 0 24 24" width="512"
                                                        height="512">
                                                        <path
                                                            d="M19,6V4a4,4,0,0,0-4-4H9A4,4,0,0,0,5,4V6a5.006,5.006,0,0,0-5,5v5a5.006,5.006,0,0,0,5,5,3,3,0,0,0,3,3h8a3,3,0,0,0,3-3,5.006,5.006,0,0,0,5-5V11A5.006,5.006,0,0,0,19,6ZM7,4A2,2,0,0,1,9,2h6a2,2,0,0,1,2,2V6H7ZM17,21a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V17a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Zm5-5a3,3,0,0,1-3,3V17a3,3,0,0,0-3-3H8a3,3,0,0,0-3,3v2a3,3,0,0,1-3-3V11A3,3,0,0,1,5,8H19a3,3,0,0,1,3,3Z" />
                                                        <path d="M18,10H16a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                                                    </svg>
                                                </div>
                                            </a>
                                            @if ($user !== 'user')
                                                <form action="{{ route('destroy-peminjaman', $peminjaman->id) }}"
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
                                            @endif
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @empty
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th colspan="11" class="px-2 py-2 text-center">
                                            Data Tidak Ditemukan
                                        </th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $transactions->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('filterPenggunaan', () => ({
            location: '{{ request('location') }}',
            purpose: '{{ request('purpose') }}',
            userId: '{{ request('user_id') }}',
            startDate: '{{ request('start_date') }}',
            endDate: '{{ request('end_date') ?: now()->format('Y-m-d') }}',
            today: '{{ now()->format('Y-m-d') }}', // Simpan nilai hari ini

            updateFilter(key, value) {
                let url = new URL(window.location.href);

                if (key === 'start_date') {
                    this.endDate = this.today; // Ubah endDate jadi hari ini
                    url.searchParams.set('end_date', this.endDate);
                }

                if (value) {
                    url.searchParams.set(key, value);
                } else {
                    url.searchParams.delete(key);
                }

                window.location.href = url.toString();
            }
        }));
    });
</script>
