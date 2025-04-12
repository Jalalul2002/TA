<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Penggunaan Barang</title>
    @vite('resources/css/app.css')
    <style>
        @media print {
            @page {
                margin: 10mm 5mm 10mm 5mm;
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

<body class="bg-gray-100 text-gray-900">
    <div class="max-w-6xl mx-auto p-6 bg-white">
        <div class="flex items-center justify-between border-b-[1px] border-black pb-4 mb-4 px-4">
            <!-- Logo -->
            <div class="w-14 me-1">
                <img src="{{ asset('Logo-uinsgd_official.png') }}" alt="Logo" class="w-full h-auto">
            </div>

            <!-- Header -->
            <div class="text-center flex-1">
                <h1 class="text-lg font-bold text-center uppercase">Laporan Data Penggunaan Barang</h1>
                <h1 class="text-sm font-bold uppercase">Laboratorium Fakultas Sains dan Teknologi</h1>
                <h1 class="text-sm font-bold uppercase">Universitas Islam Negeri Sunan Gunung Djati Bandung</h1>
                <p class="text-[10px] mt-2">Jl. A.H. Nasution No. 105 Cibiru Kota Bandung 40614 Jawa Barat – Indonesia |
                    Email: fst@uinsgd.ac.id</p>
            </div>

            <!-- Kosong sebagai penyeimbang -->
            <div class="w-14"></div>
        </div>

        <div class="main-data text-xs">
            <p class="mb-2 text-sm font-bold">Informasi Penggunaan</p>
            <div class="flex items-start justify-between flex-row mb-4">
                <table>
                    <tr>
                        <td class="text-gray-700">ID Pengguna/NIM/NIP</td>
                        <td class="text-gray-700 px-2">:</td>
                        <td class="text-gray-700">
                            <strong>{{ request()->has('user_id') ? $data->first()->user_id : $data->user_id }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-700">Nama Pengguna</td>
                        <td class="text-gray-700 px-2">:</td>
                        <td class="text-gray-700">
                            <strong>{{ request()->has('user_id') ? $data->first()->name : $data->name }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-700">Prodi Pengguna</td>
                        <td class="text-gray-700 px-2">:</td>
                        <td class="text-gray-700">
                            <strong>{{ request()->has('user_id') ? $data->first()->prodi : $data->prodi }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-700">Nomor Telepon</td>
                        <td class="text-gray-700 px-2">:</td>
                        <td class="text-gray-700">
                            <strong>{{ request()->has('user_id') ? $data->first()->telp : $data->telp }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-gray-700">Keterangan</td>
                        <td class="text-gray-700 px-2">:</td>
                        <td class="text-gray-700">
                            <strong>{{ request()->has('user_id') ? $data->first()->detail : $data->detail }}</strong>
                        </td>
                    </tr>
                </table>
                <table>
                    @if (request()->has('user_id'))
                        @if ($startDate && $endDate)
                            <tr>
                                <td class="text-gray-700">Periode</td>
                                <td class="text-gray-700 px-2">:</td>
                                <td class="text-gray-700"><strong>{{ $startDate->format('d M Y') }} -
                                        {{ $endDate->format('d M Y') }}</strong></td>
                            </tr>
                        @endif
                        @if ($location)
                            <tr>
                                <td class="text-gray-700">Lokasi</td>
                                <td class="text-gray-700 px-2">:</td>
                                <td class="text-gray-700"><strong>{{ $location }}</strong></td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td class="text-gray-700">Tanggal Pembuatan</td>
                            <td class="text-gray-700 px-2">:</td>
                            <td class="text-gray-700"><strong>{{ $data->created_at->format('d/m/Y') }}</strong></td>
                        </tr>
                        <tr>
                            <td class="text-gray-700">Laboran</td>
                            <td class="text-gray-700 px-2">:</td>
                            <td class="text-gray-700"><strong>{{ $data->creator->name }}</strong></td>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-gray-700">Tanggal Cetak</td>
                        <td class="text-gray-700 px-2">:</td>
                        <td class="text-gray-700">{{ $printDate }}</td>
                    </tr>
                </table>
            </div>
            <div class="flex justify-end mb-4">
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
            <p class="mb-2 text-sm font-bold">Daftar Bahan Habis pakai</p>
            <div class="overflow-x-none text-xs">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            @if (request()->has('user_id'))
                                <th class="border border-gray-300 px-2 py-1">Tanggal</th>
                                <th class="border border-gray-300 px-2 py-1">Laboran</th>
                            @endif
                            <th class="border border-gray-300 px-2 py-1">Nama Produk</th>
                            <th class="border border-gray-300 px-2 py-1">Merk</th>
                            <th class="border border-gray-300 px-2 py-1">Jenis</th>
                            <th class="border border-gray-300 px-2 py-1">Harga</th>
                            <th class="border border-gray-300 px-2 py-1">Jumlah</th>
                            <th class="border border-gray-300 px-2 py-1">Satuan</th>
                            <th class="border border-gray-300 px-2 py-1">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (request()->has('user_id'))
                            @foreach ($data as $row)
                                @foreach ($row->items as $item)
                                    <tr class="hover:bg-gray-100">
                                        <td class="border border-gray-300 px-2 py-1">
                                            {{ $row->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1">{{ $row->creator->name }}</td>
                                        <td class="border border-gray-300 px-2 py-1">{{ $item->asset->product_name }}
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1">{{ $item->asset->merk ?: '-' }}
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1">{{ $item->asset->product_type }}
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp.
                                            {{ number_format($item->unit_price, 0, ',', '.') }},-
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">
                                            {{ $item->formatted_quantity }}</td>
                                        <td class="border border-gray-300 px-2 py-1">{{ $item->asset->product_unit }}
                                        </td>
                                        <td class="border border-gray-300 px-2 py-1 text-right">Rp.
                                            {{ number_format($item->total_price, 0, ',', '.') }},-
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-2 py-1 text-center font-bold" colspan="8">Total
                                    Harga
                                </td>
                                <td class="border border-gray-300 px-2 py-1 text-right font-bold">Rp.
                                    {{ $totalPrice }},-
                                </td>
                            </tr>
                        @else
                            @foreach ($data->items as $item)
                                <tr class="hover:bg-gray-100">
                                    <td class="border border-gray-300 px-2 py-1">{{ $item->asset->product_name }}
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1">{{ $item->asset->merk ?: '-' }}</td>
                                    <td class="border border-gray-300 px-2 py-1">{{ $item->asset->product_type }}
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">Rp.
                                        {{ number_format($item->unit_price, 0, ',', '.') }},-
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1 text-right">
                                        {{ $item->formatted_quantity }}</td>
                                    <td class="border border-gray-300 px-2 py-1">{{ $item->asset->product_unit }}
                                    </td>
                                    <td class="border border-gray-300 px-2 py-1 text-right whitespace-nowrap">Rp.
                                        {{ number_format($item->total_price, 0, ',', '.') }},-
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-2 py-1 text-center font-bold" colspan="6">Total
                                    Harga
                                </td>
                                <td class="border border-gray-300 px-2 py-1 text-right font-bold">Rp.
                                    {{ number_format($totalPrice, 0, ',', '.') }},-
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="w-full mt-10 px-6 flex justify-end">
                <div class="flex flex-col">
                    <p class="text-xs">Bandung,
                        <span>{{ \Carbon\Carbon::parse($printDate)->translatedFormat('d F Y') }}</span>
                    </p>
                    <p class="text-xs">Petugas,</p>
                </div>
            </div>
        </div>
        <footer class="text-center text-gray-600 text-xs mt-6 print-footer">
            <p>&copy; Dicetak dari: <strong>{{ request()->getHost() }}</strong> tanggal {{ $printDate }}</p>
        </footer>
</body>

</html>
