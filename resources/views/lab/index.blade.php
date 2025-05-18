<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Laboratorium') }}
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
                        <div class="z-10 flex gap-2 flex-wrap justify-end" x-data="filterLab">
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
                            @if ($user === 'admin' || $user === 'staff')
                                <a href="{{ route('lab.add') }}"
                                    class="inline-flex text-sm items-center px-4 py-2 border border-transparent rounded-md font-semibold text-white bg-uinBlue hover:bg-uinNavy transition-all duration-300">
                                    <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 448 512">
                                        <path
                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                    </svg>
                                    Tambah
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            @php
                                $columns = [
                                    'lab_code' => 'Kode',
                                    'name' => 'Nama',
                                    'prodi' => 'Prgram Studi',
                                    'capacity' => 'Kapasitas',
                                ];
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
                                    @if ($user == 'admin' || $user == 'staff')
                                        <th scope="col" class="text-center px-2 py-3 size-fit">
                                            Action
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $counter = ($datas->currentPage() - 1) * $datas->perPage() + 1;
                                @endphp
                                @forelse ($datas as $item)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th class="text-center px-2 py-2">
                                            {{ $counter }}
                                        </th>
                                        <td scope="row" class="px-2 py-2 font-medium whitespace-nowrap">
                                            {{ $item->lab_code }}
                                        </td>
                                        <td scope="row" class="px-2 py-2 font-medium whitespace-nowrap">
                                            {{ $item->name }}
                                        </td>
                                        <td scope="row" class="px-2 py-2 font-medium whitespace-nowrap">
                                            {{ $item->prodi ?: '-' }}
                                        </td>
                                        <td scope="row" class="px-2 py-2 font-medium whitespace-nowrap">
                                            {{ $item->capacity ?? '-' }}
                                        </td>
                                        <td scope="row" class="px-2 py-2 font-medium text-center">
                                            @if ($user == 'admin' || $user == 'staff')
                                                <form action="{{ route('lab.destroy', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button title="Hapus Data" type="submit"
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
                        {{ $datas->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('filterLab', () => ({
            location: '{{ request('location') }}',

            updateFilter(key, value) {
                let url = new URL(window.location.href);

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
