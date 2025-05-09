<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8 px-4 lg:px-0">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-4 gap-4 xl:gap-6">
            <!-- Card Total Aset Inventaris -->
            <div class="col-span-1">
                <div class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Aset Inventaris</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $totalInventaris }}</p>
                    </div>
                    <div class="bg-uinBlue text-white p-3 rounded-full">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h11M9 21V3m5 18v-6" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Card Total Aset BHP -->
            <div class="col-span-1">
                <div
                    class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Aset BHP</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $totalBhp }}</p>
                    </div>
                    <div class="bg-uinYellow text-white p-3 rounded-full">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8V4m0 0L8 8m4-4l4 4M4 16h16" />
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Card Total Perencanaan -->
            <div class="col-span-1">
                <div
                    class="bg-white shadow-md rounded-2xl p-6 flex items-center justify-between border border-gray-200">
                    <div>
                        <h3 class="text-gray-500 text-sm font-medium">Total Perencanaan</h3>
                        <p class="text-3xl font-semibold text-gray-800">{{ $totalPerencanaan }}</p>
                    </div>
                    <div class="bg-uinRed text-white p-3 rounded-full">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6M3 6h18M3 18h18" />
                        </svg>
                    </div>
                </div>
            </div>
            {{-- Card Assets Terbaru --}}
            <div class="col-span-1 lg:col-span-2">
                <div class="bg-white p-4 rounded-lg shadow-md ">
                    <h3 class="text-lg font-semibold mb-4">📦 Aset Terbaru</h3>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-white uppercase bg-uinTosca">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nama Produk</th>
                                    <th scope="col" class="px-6 py-3">Tipe</th>
                                    <th scope="col" class="px-6 py-3">Stok</th>
                                    <th scope="col" class="px-6 py-3">Diperbarui</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentAssets as $asset)
                                    <tr class="border-b border-gray-200">
                                        <td scope="col" class="px-6 py-3">
                                            {{ $asset->product_name ?? 'Tidak ada data' }}
                                        </td>
                                        <td scope="col" class="px-6 py-3">
                                            {{ $asset->type == 'bhp' ? 'BHP' : 'Inventaris' }}
                                        </td>
                                        <td scope="col" class="px-6 py-3">
                                            {{ rtrim(rtrim(number_format($asset->stock, 4, ',', '.'), '0'), ',') }}</td>
                                        <td scope="col" class="px-6 py-3 text-gray-500">
                                            {{ $asset->updated_at?->diffForHumans() ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-2 text-center text-gray-500">Tidak ada aset terbaru
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
                    <h3 class="text-lg font-semibold mb-2">📝 Transaksi Terbaru</h3>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            <thead class="text-xs text-white uppercase bg-uinTosca">
                                <tr>
                                    <th scope="col" class="px-4 py-3">Nama</th>
                                    <th scope="col" class="px-4 py-3">Jenis Transaksi</th>
                                    <th scope="col" class="px-4 py-3">Keperluan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentTransactions as $item)
                                    <tr class="border-b border-gray-200">
                                        <td scope="col" class="px-4 py-2">
                                            {{ $item->user_id }}-{{ $item->name }}
                                        </td>
                                        <td scope="col" class="px-4 py-2">
                                            {{ $item->type == 'bhp' ? 'Penggunaan Bahan' : 'Peminjaman Barang' }}
                                        </td>
                                        <td scope="col" class="px-4 py-2 capitalize">
                                            {{ $item->purpose }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-2 text-center text-gray-500">Tidak ada transaksi
                                            terbaru
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
</x-app-layout>
