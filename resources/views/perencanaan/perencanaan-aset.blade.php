<x-app-layout>
    @php
        $type = $perencanaans[0]->type;
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Perencanaan') }} {{ $type == 'bhp' ? 'Barang Habis Pakai' : 'Aset Inventaris' }}
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
                        @if (Auth::user()->usertype !== 'user')
                            <div>
                                <a href="{{ route('add-perencanaan.inv') }}"
                                    class="inline-flex text-sm items-center px-4 py-2 border border-transparent rounded-md font-semibold text-white bg-uinBlue hover:bg-uinNavy transition-all duration-300">
                                    <svg class="w-4 h-4 me-2 text-white" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor" viewBox="0 0 448 512">
                                        <path
                                            d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z" />
                                    </svg>
                                    Tambah Perencanaan
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-white uppercase bg-uinTosca">
                                <tr>
                                    <th scope="col" class="text-center px-2 py-3">
                                        No
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Tahun Perencanaan
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Program Studi
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Total Barang
                                    </th>
                                    <th scope="col" class="pl-5 px-1 py-3">
                                        Status
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Dibuat Oleh
                                    </th>
                                    <th scope="col" class="px-1 py-3">
                                        Diupdate Oleh
                                    </th>
                                    <th scope="col" class="text-center px-1 py-3 size-fit">
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
                                        <th class="text-center px-2 py-2">
                                            {{ $counter }}
                                        </th>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ $perencanaan->nama_perencanaan }}
                                        </td>
                                        <td scope="row" class="px-1 py-2 font-medium whitespace-nowrap">
                                            {{ $perencanaan->prodi }}
                                        </td>
                                        <td class="px-1 py-2">
                                            {{ $perencanaan->plans->count() }}
                                        </td>
                                        <td class="px-1 py-2">
                                            <span
                                                class="text-sm font-bold rounded-full px-4 py-2 {{ $perencanaan->status == 'belum' ? 'bg-red-100 text-red-500' : 'bg-green-100 text-green-500' }}">
                                                {{ strtoupper($perencanaan->status) }}
                                            </span>
                                        </td>
                                        <td class="px-1 py-2">
                                            {{ $perencanaan->creator->name }}
                                        </td>
                                        <td class="px-1 py-2">
                                            {{ $perencanaan->latestUpdater?->updater?->name ?? $perencanaan->updater->name }}
                                        </td>
                                        <td class="py-2 flex flex-row gap-x-1 justify-center">
                                            @if ($perencanaan->status === 'belum' && Auth::user()->usertype !== 'user')
                                                <form id="complete-form"
                                                    action="{{ route('perencanaan.complete', $perencanaan->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                                <button title="Selesaikan Perencanaan"
                                                    onclick="event.preventDefault(); document.getElementById('complete-form').submit();"
                                                    class="inline-flex items-center p-2 text-sm font-medium rounded-lg shadow-sm text-white bg-green-500 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300">
                                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                                        id="Capa_1" x="0px" y="0px" viewBox="0 0 507.506 507.506"
                                                        style="enable-background:new 0 0 507.506 507.506;"
                                                        xml:space="preserve" width="512" height="512">
                                                        <g>
                                                            <path
                                                                d="M163.865,436.934c-14.406,0.006-28.222-5.72-38.4-15.915L9.369,304.966c-12.492-12.496-12.492-32.752,0-45.248l0,0   c12.496-12.492,32.752-12.492,45.248,0l109.248,109.248L452.889,79.942c12.496-12.492,32.752-12.492,45.248,0l0,0   c12.492,12.496,12.492,32.752,0,45.248L202.265,421.019C192.087,431.214,178.271,436.94,163.865,436.934z" />
                                                        </g>
                                                    </svg>
                                                </button>
                                            @endif
                                            <a title="Lihat Detail"
                                                href="{{ route('detail-perencanaan', $perencanaan->id) }}">
                                                <div
                                                    class="bg-amber-500 p-2 rounded-lg hover:bg-amber-700 transition-all duration-300">
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
                                            <a title="Download Data"
                                                href="{{ route('perencanaan.download', $perencanaan->id) }}">
                                                <div
                                                    class="bg-teal-500 p-2 rounded-lg hover:bg-teal-700 transition-all duration-300">
                                                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg"
                                                        id="Outline" viewBox="0 0 24 24" width="512"
                                                        height="512">
                                                        <path
                                                            d="M9.878,18.122a3,3,0,0,0,4.244,0l3.211-3.211A1,1,0,0,0,15.919,13.5l-2.926,2.927L13,1a1,1,0,0,0-1-1h0a1,1,0,0,0-1,1l-.009,15.408L8.081,13.5a1,1,0,0,0-1.414,1.415Z" />
                                                        <path
                                                            d="M23,16h0a1,1,0,0,0-1,1v4a1,1,0,0,1-1,1H3a1,1,0,0,1-1-1V17a1,1,0,0,0-1-1H1a1,1,0,0,0-1,1v4a3,3,0,0,0,3,3H21a3,3,0,0,0,3-3V17A1,1,0,0,0,23,16Z" />
                                                    </svg>
                                                </div>
                                            </a>
                                            @if (Auth::user()->usertype !== 'user')
                                                <form action="{{ route('destroy-rencana', $perencanaan->id) }}"
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
                                        <th colspan="8" class="px-1 py-2 text-center">
                                            Data Tidak Ditemukan
                                        </th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $perencanaans->appends(['search' => request('search')])->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
