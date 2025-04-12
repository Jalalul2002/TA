<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Data Perencanaan</title>
    @vite('resources/css/app.css')
    <style>
        @media print {
            @page {
                margin: 10mm 5mm 10mm 5mm;
                /* Margin atas, kanan, bawah, kiri */
            }

            .main-data {
                transform-origin: top left;
                width: 100%;
                height: 80%;
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
    <div class="max-w-6xl mx-auto p-6 bg-white rounded-lg">
        <div class="flex items-center justify-between border-b-[1px] border-black pb-4 mb-4 px-4">
            <!-- Logo -->
            <div class="w-14 me-1">
                <img src="{{ asset('Logo-uinsgd_official.png') }}" alt="Logo" class="w-full h-auto">
            </div>

            <!-- Header -->
            <div class="text-center flex-1">
                <h1 class="text-lg font-bold text-center uppercase">Laporan Data Perencanaan
                    {{ $perencanaan->type == 'bhp' ? 'Bahan Habis Pakai' : 'Inventaris' }}</h1>
                <h1 class="text-sm font-bold uppercase">Laboratorium Fakultas Sains dan Teknologi</h1>
                <h1 class="text-sm font-bold uppercase">Universitas Islam Negeri Sunan Gunung Djati Bandung</h1>
                <p class="text-[10px] mt-2">Jl. A.H. Nasution No. 105 Cibiru Kota Bandung 40614 Jawa Barat – Indonesia |
                    Email: fst@uinsgd.ac.id</p>
            </div>

            <!-- Kosong sebagai penyeimbang -->
            <div class="w-14"></div>
        </div>
        <div class="main-data">
            <div class="flex items-center justify-around flex-row mb-4">
                <p class="text-sm text-gray-700">Tahun Perencanaan:
                    <strong>{{ $perencanaan->nama_perencanaan }}</strong>
                </p>
                <p class="text-sm text-gray-700">Prodi: <strong>{{ $perencanaan->prodi }}</strong></p>
                <p class="text-sm text-gray-700 capitalize">Status: <strong>{{ $perencanaan->status }}</strong></p>
                <p class="text-sm text-gray-700">Tanggal: <strong>{{ $perencanaan->updated_at }}</strong></p>

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

            <div>
                <table class="w-full border-collapse border border-gray-300 text-xs">
                    <thead>
                        <tr class="bg-gray-200 text-gray-700">
                            <th class="border border-gray-300 px-3 py-2">Nama Barang</th>
                            <th class="border border-gray-300 px-3 py-2">Keterangan</th>
                            <th class="border border-gray-300 px-3 py-2">Merk</th>
                            <th class="border border-gray-300 px-3 py-2">Jenis</th>
                            <th class="border border-gray-300 px-3 py-2">Stok</th>
                            <th class="border border-gray-300 px-3 py-2">Satuan</th>
                            <th class="border border-gray-300 px-3 py-2">Harga Beli</th>
                            <th class="border border-gray-300 px-3 py-2">Jumlah</th>
                            <th class="border border-gray-300 px-3 py-2">Sub Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $row)
                            <tr class="hover:bg-gray-100">
                                <td class="border border-gray-300 px-3 py-2">{{ $row->product->product_name ?? '-' }}
                                </td>
                                <td class="border border-gray-300 px-3 py-2">{{ $row->product->product_detail ?: '-' }}
                                </td>
                                <td class="border border-gray-300 px-3 py-2">{{ $row->product->merk ?: '-' }}</td>
                                <td class="border border-gray-300 px-3 py-2">{{ $row->product->product_type ?? '-' }}
                                </td>
                                <td class="border border-gray-300 px-3 py-2">{{ $row->stock > 0 ? $row->stock : '0' }}
                                </td>
                                <td class="border border-gray-300 px-3 py-2">{{ $row->product->product_unit ?? '-' }}
                                </td>
                                <td class="border border-gray-300 px-3 py-2 whitespace-nowrap text-right">Rp.
                                    {{ number_format($row->purchase_price ?? 0, 0, ',', '.') }},-</td>
                                <td class="border border-gray-300 px-3 py-2">{{ $row->jumlah_kebutuhan }}</td>
                                <td class="border border-gray-300 px-3 py-2 font-bold whitespace-nowrap text-right">Rp.
                                    {{ number_format($row->total_price ?? 0, 0, ',', '.') }},-</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="border border-gray-300 px-3 py-2 font-bold text-right" colspan="8">Total Harga
                            </td>
                            <td class="border border-gray-300 px-3 py-2 font-bold whitespace-nowrap text-right">Rp.
                                {{ number_format($perencanaan->total_price ?? 0, 0, ',', '.') }},-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-full mt-6 px-6 flex justify-end">
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
