<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Transaksi</title>
    @vite('resources/css/app.css')
    <style>
        @media print {
            @page {
                margin: 4mm 3mm 4mm 3mm;
                /* Margin atas, kanan, bawah, kiri */
            }

            .main-data {
                transform-origin: top left;
                /* Sesuaikan agar tidak ada blank space */
            }

            .print-footer {
                z-index: 50;
                position: fixed;
                bottom: 0px;
                width: 100%;
                text-align: center;
                font-size: 12px;
                color: gray;
            }

            .print-button {
                display: none;
            }
        }
    </style>
</head>
@php
    // dd($report);
@endphp

<body class="bg-gray-100 text-gray-900">
    <div class="max-w-6xl mx-auto p-6 bg-white">
        <div class="flex items-center justify-between border-b-[1px] border-black pb-1 px-4">
            <!-- Logo -->
            <div class="w-12 me-1">
                <img src="{{ asset('Logo-uinsgd_official.png') }}" alt="Logo" class="w-full h-auto">
            </div>

            <!-- Header -->
            <div class="text-center flex-1">
                <h1 class="text-sm font-bold text-center uppercase">Laporan Data Transaksi</h1>
                <h1 class="text-xs font-bold uppercase">Laboratorium Fakultas Sains dan Teknologi</h1>
                <h1 class="text-xs font-bold uppercase">Universitas Islam Negeri Sunan Gunung Djati Bandung</h1>
                <p class="text-[10px]">Jl. A.H. Nasution No. 105 Cibiru Kota Bandung 40614 Jawa Barat – Indonesia |
                    Email: fst@uinsgd.ac.id</p>
            </div>

            <!-- Kosong sebagai penyeimbang -->
            <div class="w-14"></div>
        </div>

        <div class="main-data text-xs">
            <div class="my-1">
                <p class="text-center font-medium text-gray-900 capitalize">Rekap {{ $purpose ?: 'Semua Keperluan' }}
                </p>
                <p class="text-gray-900 font-medium capitalize">Informasi Pengguna</p>
                <table class="text-gray-900 ml-2">
                    <tbody>
                        <tr>
                            <td>ID</td>
                            <td class="pl-2 pr-1">:</td>
                            <td>{{ $report->isNotEmpty() ? $report[0]->user_id : '' }}</td>
                        </tr>
                        <tr>
                            <td>Nama</td>
                            <td class="pl-2 pr-1">:</td>
                            <td>{{ $report->isNotEmpty() ? $report[0]->name : '' }}</td>
                        </tr>
                        <tr>
                            <td>Prodi</td>
                            <td class="pl-2 pr-1">:</td>
                            <td>{{ $report->isNotEmpty() ? $report[0]->prodi : '' }}</td>
                        </tr>
                        <tr>
                            <td>Telp</td>
                            <td class="pl-2 pr-1">:</td>
                            <td>{{ $report->isNotEmpty() ? $report[0]->telp : '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end">
                <button onclick="window.print()"
                    class="print-button px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-800 transition flex items-center gap-2 font-semibold">
                    <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Outline" viewBox="0 0 24 24"
                        width="512" height="512">
                        <path
                            d="M19,6V4a4,4,0,0,0-4-4H9A4,4,0,0,0,5,4V6a5.006,5.006,0,0,0-5,5v5a5.006,5.006,0,0,0,5,5,3,3,0,0,0,3,3h8a3,3,0,0,0,3-3,5.006,5.006,0,0,0,5-5V11A5.006,5.006,0,0,0,19,6ZM7,4A2,2,0,0,1,9,2h6a2,2,0,0,1,2,2V6H7ZM17,21a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V17a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Zm5-5a3,3,0,0,1-3,3V17a3,3,0,0,0-3-3H8a3,3,0,0,0-3,3v2a3,3,0,0,1-3-3V11A3,3,0,0,1,5,8H19a3,3,0,0,1,3,3Z" />
                        <path d="M18,10H16a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                    </svg>
                    <span>Cetak</span>
                </button>
            </div>
            @php
                $allItems = $report->flatMap->items;
                $allLoans = $report->flatMap->loans;
                $no_bhp = 1;
                $no_inv = 1;
            @endphp
            <div class="overflow-x-none text-xs">
                <p class="text-gray-900 font-medium capitalize mb-1">Bahan Habis Pakai</p>
                <div class="px-2">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="border border-gray-300 px-1">No</th>
                                <th class="border border-gray-300 px-1">Tanggal</th>
                                <th class="border border-gray-300 px-1">Produk</th>
                                <th class="border border-gray-300 px-1">Harga</th>
                                <th class="border border-gray-300 px-1">Banyaknya</th>
                                <th class="border border-gray-300 px-1">Satuan</th>
                                <th class="border border-gray-300 px-1">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allItems as $item)
                                <tr class="hover:bg-gray-100">
                                    <td class="border border-gray-300 px-1">
                                        {{ $no_bhp++ }}</td>
                                    <td class="border border-gray-300 px-1">
                                        {{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="border border-gray-300 px-1">{{ $item->asset->product_name }}
                                        {{ $item->asset->product_detail ?: '' }} {{ $item->asset->merk ?: '' }}</td>
                                    <td class="border border-gray-300 px-1 text-right whitespace-nowrap">
                                        Rp. {{ number_format($item->unit_price, 0, ',', '.') }},-</td>
                                    <td class="border border-gray-300 px-1 text-right whitespace-nowrap">
                                        {{ $item->formatted_quantity }}</td>
                                    <td class="border border-gray-300 px-1 whitespace-nowrap">
                                        {{ $item->asset->product_unit }}
                                    </td>
                                    <td class="border border-gray-300 px-1 text-right whitespace-nowrap">
                                        Rp. {{ number_format($item->total_price, 0, ',', '.') }},-</td>
                                </tr>
                            @empty
                                <tr class="hover:bg-gray-100">
                                    <td class="border border-gray-300 px-1 text-center" colspan="6">Tidak Ada
                                        Transaksi</td>
                                    <td class="border border-gray-300 px-1 text-right whitespace-nowrap">
                                        Rp. 0,-</td>
                                </tr>
                            @endforelse
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-1 text-right font-bold" colspan="6">Total
                                    Biaya Bahan Habis Pakai</td>
                                <td class="border border-gray-300 px-1 text-right font-bold whitespace-nowrap">
                                    Rp. {{ number_format($report->sum('total_item_price'), 0, ',', '.') }},-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-gray-900 font-medium capitalize mt-2 mb-1">Pemakaian Alat</p>
                <div class="px-2">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200 text-gray-700">
                                <th class="border border-gray-300 px-1">No</th>
                                <th class="border border-gray-300 px-1">Tanggal</th>
                                <th class="border border-gray-300 px-1">Produk</th>
                                <th class="border border-gray-300 px-1">Harga</th>
                                <th class="border border-gray-300 px-1">Banyaknya</th>
                                <th class="border border-gray-300 px-1">Durasi</th>
                                <th class="border border-gray-300 px-1">Satuan</th>
                                <th class="border border-gray-300 px-1">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allLoans as $item)
                                <tr class="hover:bg-gray-100">
                                    <td class="border border-gray-300 px-1">
                                        {{ $no_inv++ }}</td>
                                    <td class="border border-gray-300 px-1">
                                        {{ $item->created_at->format('d/m/Y') }}</td>
                                    <td class="border border-gray-300 px-1">{{ $item->asset->product_name }}
                                        {{ $item->asset->product_detail ?: '' }} {{ $item->asset->merk ?: '' }}</td>
                                    <td class="border border-gray-300 px-1 text-right whitespace-nowrap">
                                        Rp. {{ number_format($item->rental_price, 0, ',', '.') }},-</td>
                                    <td class="border border-gray-300 px-1 text-right whitespace-nowrap">
                                        {{ intval($item->quantity) }}
                                        {{ $item->asset->latestPrice->price_type == 'unit' ? '' : $item->asset->product_unit }}
                                    </td>
                                    <td class="border border-gray-300 px-1 whitespace-nowrap text-right">
                                        {{ $item->asset->latestPrice->price_type == 'unit'
                                            ? '-'
                                            : rtrim(rtrim(number_format($item->rental, 4, ',', '.'), '0'), ',') }}
                                    </td>
                                    <td class="border border-gray-300 px-1 whitespace-nowrap">
                                        {{ $item->asset->latestPrice->price_type == 'unit' ? $item->asset->product_unit : ($item->asset->latestPrice->price_type == 'sample' ? 'sample' : 'jam') }}
                                    </td>
                                    <td class="border border-gray-300 px-1 text-right whitespace-nowrap">
                                        Rp. {{ number_format($item->total_price, 0, ',', '.') }},-</td>
                                </tr>
                            @empty
                                <tr class="hover:bg-gray-100">
                                    <td class="border border-gray-300 px-1 text-center" colspan="7">Tidak Ada
                                        Transaksi</td>
                                    <td class="border border-gray-300 px-1 text-right whitespace-nowrap">
                                        Rp. 0,-</td>
                                </tr>
                            @endforelse
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-1 text-right font-bold" colspan="7">Total
                                    Biaya Pemakaian Alat</td>
                                <td class="border border-gray-300 px-1 text-right font-bold whitespace-nowrap">
                                    Rp. {{ number_format($report->sum('total_loan_price'), 0, ',', '.') }},-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="border-t border-gray-500 mt-2 py-2">
                <table>
                    <thead>
                        <tr>
                            <th class="border border-gray-500 px-1" colspan="2">Detail Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="font-medium">
                            <td class="border border-gray-500 pl-2 pr-10">Total Biaya Keseluruhan</td>
                            <td class="border border-gray-500 pr-2 text-right pl-10">Rp.
                                {{ number_format($report->sum(fn($row) => $row->total_item_price + $row->total_loan_price) ?? 0, 0, ',', '.') }},-
                            </td>
                        </tr>
                        <tr class="font-medium">
                            <td class="border border-gray-500 pl-2 pr-10">Keterangan</td>
                            <td class="border border-gray-500 px-1"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-full px-6 flex justify-end">
                <div class="flex flex-col">
                    <p class="text-xs">Bandung,
                        <span>{{ \Carbon\Carbon::parse($printDate)->translatedFormat('d F Y') }}</span>
                    </p>
                    <p class="text-xs">Petugas,</p>
                </div>
            </div>
        </div>
    </div>
    <footer class="text-center text-gray-600 text-xs mt-6 print-footer">
        <p>&copy; Dicetak dari: <strong>{{ request()->getHost() }}</strong> tanggal {{ $printDate }}</p>
    </footer>
</body>

</html>
