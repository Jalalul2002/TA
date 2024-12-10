<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Perencanaan Barang Habis Pakai') }}
        </h2>
    </x-slot>
    <div class="py-12">
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
                        <div>
                            <a href="/add-perencanaan-bhp"
                                class="inline-flex items-center px-4 py-3 rounded-lg text-white bg-uinBlue hover:bg-uinNavy w-full">
                                <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 448 512">
                                    <path
                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                </svg>
                                Tambah Perencanaan BHP
                            </a>
                        </div>
                    </div>
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    No
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tahun Perencanaan
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Program Studi
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Total Barang
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Dibuat Oleh
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Diupdate Oleh
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $counter = ($perencanaans->currentPage() - 1) * $perencanaans->perPage() + 1;
                            @endphp
                            @forelse ($perencanaans as $perencanaan)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th class="px-6 py-4">
                                        {{ $counter }}
                                    </th>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $perencanaan->nama_perencanaan }}
                                    </td>
                                    <td scope="row" class="px-6 py-4 font-medium whitespace-nowrap">
                                        {{ $perencanaan->prodi }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $perencanaan->plans->count() }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $perencanaan->status }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $perencanaan->creator->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $perencanaan->latestUpdater?->updater?->name ?? 'Belum Ada' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('detail-perencanaan-bhp', $perencanaan->id) }}"
                                            class="font-medium text-blue-600 hover:underline">Detail</a>
                                        <form action="{{ route('destroy-rencana', $perencanaan->id) }}" method="POST">
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
                        {{ $perencanaans->appends(['search' => request('search')])->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
