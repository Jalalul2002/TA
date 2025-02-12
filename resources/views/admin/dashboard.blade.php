<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div x-data="{ filter: '{{ $filter }}' }" class="max-w-full sm:px-6 lg:px-8 pb-4">
            <div class="flex flex-row items-center bg-white rounded-xl w-fit ps-4 shadow-md">
                <svg class="w-4 h-4 fill-gray-600" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24"
                    width="512" height="512">
                    <path
                        d="M1,4.75H3.736a3.728,3.728,0,0,0,7.195,0H23a1,1,0,0,0,0-2H10.931a3.728,3.728,0,0,0-7.195,0H1a1,1,0,0,0,0,2ZM7.333,2a1.75,1.75,0,1,1-1.75,1.75A1.752,1.752,0,0,1,7.333,2Z" />
                    <path
                        d="M23,11H20.264a3.727,3.727,0,0,0-7.194,0H1a1,1,0,0,0,0,2H13.07a3.727,3.727,0,0,0,7.194,0H23a1,1,0,0,0,0-2Zm-6.333,2.75A1.75,1.75,0,1,1,18.417,12,1.752,1.752,0,0,1,16.667,13.75Z" />
                    <path
                        d="M23,19.25H10.931a3.728,3.728,0,0,0-7.195,0H1a1,1,0,0,0,0,2H3.736a3.728,3.728,0,0,0,7.195,0H23a1,1,0,0,0,0-2ZM7.333,22a1.75,1.75,0,1,1,1.75-1.75A1.753,1.753,0,0,1,7.333,22Z" />
                </svg>
                <select id="filter" x-model="filter" @change="window.location.href = '?filter=' + filter"
                    class="font-medium text-gray-500 bg-transparent border-none focus:ring-0 cursor-pointer">
                    <option value="all">Filter Program Studi</option>
                    <option value="Umum">Data Umum</option>
                    <option value="Matematika">Matematika</option>
                    <option value="Biologi">Biologi</option>
                    <option value="Fisika">Fisika</option>
                    <option value="Kimia">Kimia</option>
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Agroteknologi">Agroteknologi</option>
                    <option value="Teknik Elektro">Teknik Elektro</option>
                </select>
            </div>
        </div>
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            <!-- Card Jumlah Aset Inventaris -->
            <div class="col-span-1 row-span-1">
                <div
                    class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Jumlah Aset Inventaris</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $jumlahInventaris }}</p>
                    </div>
                    <div class="bg-uinBlue p-3 rounded-full">
                        <svg class="w-7 h-7 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                            data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                            <path
                                d="M20.527,4.217,14.5.737a5.015,5.015,0,0,0-5,0L3.473,4.217a5.014,5.014,0,0,0-2.5,4.33v6.96a5.016,5.016,0,0,0,2.5,4.331L9.5,23.317a5.012,5.012,0,0,0,5,0l6.027-3.479a5.016,5.016,0,0,0,2.5-4.331V8.547A5.014,5.014,0,0,0,20.527,4.217ZM10.5,2.47a3,3,0,0,1,3,0l6.027,3.479a2.945,2.945,0,0,1,.429.33L13.763,9.854a3.53,3.53,0,0,1-3.526,0L4.044,6.279a2.945,2.945,0,0,1,.429-.33ZM4.473,18.105a3.008,3.008,0,0,1-1.5-2.6V8.547a2.893,2.893,0,0,1,.071-.535l6.193,3.575A5.491,5.491,0,0,0,11,12.222v9.569a2.892,2.892,0,0,1-.5-.206Zm16.554-2.6a3.008,3.008,0,0,1-1.5,2.6L13.5,21.585a2.892,2.892,0,0,1-.5.206V12.222a5.491,5.491,0,0,0,1.763-.635l6.193-3.575a2.893,2.893,0,0,1,.071.535Z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Card Jumlah Total Aset Inventaris -->
            <div class="col-span-1 row-span-1">
                <div
                    class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Aset Inventaris</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $jumlahStokInventaris }}</p>
                    </div>
                    <div class="bg-uinRed p-3 rounded-full">
                        <svg class="w-7 h-7 fill-white" id="Layer_1" height="512" viewBox="0 0 24 24" width="512"
                            xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                            <path
                                d="m7 14a1 1 0 0 1 -1 1h-1a1 1 0 0 1 0-2h1a1 1 0 0 1 1 1zm4-1h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0-2zm-5 4h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0-2zm5 0h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0-2zm-5-12h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0-2zm5 0h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0-2zm-5 4h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0-2zm5 0h-1a1 1 0 0 0 0 2h1a1 1 0 0 0 0-2zm13 1v9a5.006 5.006 0 0 1 -5 5h-14a5.006 5.006 0 0 1 -5-5v-14a5.006 5.006 0 0 1 5-5h6a5.006 5.006 0 0 1 5 5h3a5.006 5.006 0 0 1 5 5zm-19 12h9v-17a3 3 0 0 0 -3-3h-6a3 3 0 0 0 -3 3v14a3 3 0 0 0 3 3zm17-12a3 3 0 0 0 -3-3h-3v15h3a3 3 0 0 0 3-3zm-3 3a1 1 0 1 0 1 1 1 1 0 0 0 -1-1zm0 4a1 1 0 1 0 1 1 1 1 0 0 0 -1-1zm0-8a1 1 0 1 0 1 1 1 1 0 0 0 -1-1z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Card Jumlah Aset BHP -->
            <div class="col-span-1 row-span-1">
                <div
                    class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Jumlah Aset BHP</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $jumlahBhp }}</p>
                    </div>
                    <div class="bg-uinTosca text-white p-3 rounded-full">
                        <svg class="w-7 h-7 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                            data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                            <path
                                d="M20,12a3.994,3.994,0,0,0-3.172,1.566l-.07-.03a5,5,0,0,0-6.009-6.377l-.091-.172A3.995,3.995,0,1,0,8.879,7.9l.073.137a4.992,4.992,0,0,0-1.134,6.7L5.933,16.5a4,4,0,1,0,1.455,1.377l1.838-1.718a4.993,4.993,0,0,0,6.539-.871l.279.119A4,4,0,1,0,20,12ZM6,4A2,2,0,1,1,8,6,2,2,0,0,1,6,4ZM4,22a2,2,0,1,1,2-2A2,2,0,0,1,4,22Zm8-7a3,3,0,0,1-1.6-5.534l.407-.217A3,3,0,1,1,12,15Zm8,3a2,2,0,1,1,2-2A2,2,0,0,1,20,18Z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Card Jumlah User -->
            <div class="col-span-1 row-span-1">
                <div
                    class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Jumlah Pengguna</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $jumlahUsers }}</p>
                    </div>
                    <div class="bg-amber-500 text-white p-3 rounded-full">
                        <svg class="w-7 h-7 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                            data-name="Layer 1" viewBox="0 0 24 24">
                            <path
                                d="m8.5,9.5c0,.551.128,1.073.356,1.537-.49.628-.795,1.407-.836,2.256-.941-.988-1.52-2.324-1.52-3.792,0-3.411,3.122-6.107,6.659-5.381,2.082.428,3.769,2.105,4.213,4.184.134.628.159,1.243.091,1.831-.058.498-.495.866-.997.866h-.045c-.592,0-1.008-.527-.943-1.115.044-.395.021-.81-.08-1.233-.298-1.253-1.32-2.268-2.575-2.557-2.286-.525-4.324,1.207-4.324,3.405Zm-3.89-1.295c.274-1.593,1.053-3.045,2.261-4.178,1.529-1.433,3.531-2.141,5.63-2.011,3.953.256,7.044,3.719,6.998,7.865-.019,1.736-1.473,3.118-3.208,3.118h-2.406c-.244-.829-1.002-1.439-1.91-1.439-1.105,0-2,.895-2,2s.895,2,2,2c.538,0,1.025-.215,1.384-.561h2.932c2.819,0,5.168-2.245,5.208-5.063C21.573,4.715,17.651.345,12.63.021c-2.664-.173-5.191.732-7.126,2.548-1.499,1.405-2.496,3.265-2.855,5.266-.109.608.372,1.166.989,1.166.472,0,.893-.329.972-.795Zm7.39,8.795c-3.695,0-6.892,2.292-7.955,5.702-.165.527.13,1.088.657,1.253.526.159,1.087-.131,1.252-.657.789-2.53,3.274-4.298,6.045-4.298s5.257,1.768,6.045,4.298c.134.428.528.702.955.702.099,0,.198-.015.298-.045.527-.165.821-.726.657-1.253-1.063-3.41-4.26-5.702-7.955-5.702Z" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Card Jumlah Perencanaan -->
            <div class="col-span-1 row-span-1">
                <div
                    class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Jumlah Perencanaan</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $jumlahPerencanaan }}</p>
                    </div>
                    <div class="bg-zinc-500 text-white p-3 rounded-full">
                        <svg class="w-7 h-7 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                            data-name="Layer 1" viewBox="0 0 24 24" width="512" height="512">
                            <path
                                d="M19,2h-1V1c0-.552-.448-1-1-1s-1,.448-1,1v1H8V1c0-.552-.448-1-1-1s-1,.448-1,1v1h-1C2.243,2,0,4.243,0,7v12c0,2.757,2.243,5,5,5h14c2.757,0,5-2.243,5-5V7c0-2.757-2.243-5-5-5ZM5,4h14c1.654,0,3,1.346,3,3v1H2v-1c0-1.654,1.346-3,3-3Zm14,18H5c-1.654,0-3-1.346-3-3V10H22v9c0,1.654-1.346,3-3,3Zm-3-6c0,.552-.448,1-1,1h-6c-.552,0-1-.448-1-1s.448-1,1-1h6c.552,0,1,.448,1,1Z" />
                        </svg>
                    </div>
                </div>
            </div>
            {{-- Card Assets Terbaru --}}
            <div class="col-span-2 row-span-4">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-4">📦 Aset Terbaru</h3>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-white uppercase bg-uinTosca">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Nama Produk</th>
                                    <th scope="col" class="px-4 py-3">Tipe</th>
                                    <th scope="col" class="px-4 py-3">Program Studi</th>
                                    <th scope="col" class="px-4 py-3">Stok</th>
                                    <th scope="col" class="px-4 py-3">Diperbarui</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentAssets as $asset)
                                    <tr class="border-b border-gray-200">
                                        <td scope="col" class="px-4 py-2">
                                            {{ $asset->product_name ?? 'Tidak ada data' }}
                                        </td>
                                        <td scope="col" class="px-4 py-2">
                                            {{ ucfirst($asset->type ?? 'Tidak ada') }}
                                        </td>
                                        <td scope="col" class="px-4 py-2">
                                            {{ ucfirst($asset->location ?? 'Tidak ada') }}
                                        </td>
                                        <td scope="col" class="px-4 py-2">{{ $asset->stock ?? 0 }}</td>
                                        <td scope="col" class="px-4 py-2 text-gray-500">
                                            {{ $asset->updated_at?->diffForHumans() ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-2 text-center text-gray-500">Tidak ada aset
                                            terbaru
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- Card Penggunaan --}}
            <div class="col-span-1 row-span-4">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">📝 Penggunaan Terbaru</h3>
                    <ul class="divide-y divide-gray-300 text-sm">
                        <li class="py-2 flex items-center justify-between">
                            <span class="font-medium">Tidak Ada</span>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- Card users --}}
            <div class="col-span-1 row-span-2">
                <div class="bg-white p-4 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold mb-2">👤 Pengguna Terbaru</h3>
                    <ul class="divide-y divide-gray-300 text-sm">
                        @forelse ($Users as $user)
                            <li class="py-2 flex items-center justify-between">
                                <span class="font-medium">{{ $user->name ?? 'Tidak ada data' }}</span>
                                <span
                                    class="text-gray-500 text-xs">{{ $user->created_at?->diffForHumans() ?? '-' }}</span>
                            </li>
                        @empty
                            <li class="py-2 text-center text-gray-500 text-sm">Tidak ada pengguna terbaru</li>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
