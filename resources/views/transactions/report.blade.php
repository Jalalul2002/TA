<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Transaksi') }}
        </h2>
    </x-slot>
    @php
        // dd($report);
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
    <div class="py-4">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg px-6 py-4">
                    <div class="flex flex-col-reverse gap-2 justify-between pb-4">
                        <div class="z-10 flex gap-2 flex-wrap justify-end" x-data="filter">
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
                                    <option value="" {{ request('purpose') == '' ? 'selected' : '' }}>
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
                                    <option value="" {{ request('user_id') == '' ? 'selected' : '' }}>
                                        Pilih Pengguna
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
                            <a title="Download Data"
                                href="{{ route('report.transaction.download', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'location' => request('location'), 'purpose' => request('purpose'), 'user_id' => request('user_id')]) }}">
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
                            <a href="{{ route('report.transaction.print', ['start_date' => request('start_date'), 'end_date' => request('end_date'), 'location' => request('location'), 'purpose' => request('purpose'), 'user_id' => request('user_id')]) }}"
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
                    @if (!request('user_id') || !request('start_date'))
                        <div class="w-full bg-gray-100 text-center py-12 rounded-lg">
                            <p>Silahkan Pilih Lokasi, Pengguna dan Periode</p>
                        </div>
                    @elseif ($report->isEmpty())
                        <div class="w-full bg-gray-100 text-center py-12 rounded-lg">
                            <p>Data Tidak Ditemukan</p>
                        </div>
                    @else
                        <div class="flex flex-col space-y-4 mb-2 mt-2">
                            <div class="flex justify-between border-b pb-2 gap-2 px-6">
                                <div class="flex items-center gap-x-8">
                                    <div>
                                        <h2 class="text-gray-500 text-sm font-medium">Detail Pengguna</h2>
                                        <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                            {{ "{$report[0]->user_id} - {$report[0]->name}" }}
                                        </h1>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Nomor Telepon</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ "{$report[0]->telp}" }}
                                    </h1>
                                </div>
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Prodi Pengguna</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ "{$report[0]->prodi}" }}
                                    </h1>
                                </div>
                                <div>
                                    <h2 class="text-gray-700 text-sm font-medium mb-2">Total Harga</h2>
                                    <h1 class="text-lg font-semibold items-baseline max-w-96">
                                        <span class="px-3 py-2 text-white rounded-full bg-uinBlue">
                                            Rp.
                                            {{ number_format($report->sum(fn($row) => $row->total_item_price + $row->total_loan_price) ?? 0, 0, ',', '.') }},-
                                        </span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                        <div class="relative overflow-x-auto sm:rounded-lg">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                @php
                                    $columns = [
                                        'created_at' => 'Tanggal',
                                        'type' => 'Tipe',
                                        'product_name' => 'Produk',
                                        'product_type' => 'Jenis',
                                        'price' => 'Harga',
                                        'quantity' => 'Jumlah',
                                        'rental' => 'Durasi',
                                        'unit' => 'Satuan',
                                        'total_price' => 'Sub Total',
                                    ];
                                @endphp
                                <thead class="text-xs text-white uppercase bg-uinTosca">
                                    <tr>
                                        <th scope="col" class="text-center px-2 py-3">
                                            No
                                        </th>
                                        @foreach ($columns as $field => $name)
                                            <th scope="col" class="py-3 px-1">
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
                                                    <a title="Sort by {{ $name }}"
                                                        href="{{ route('report.transaction', array_merge(request()->query(), ['sort_field' => $field, 'sort_order' => $newSortOrder])) }}">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // $counter = ($report->currentPage() - 1) * $report->perPage() + 1;
                                        $counter = 1;
                                    @endphp
                                    @forelse ($report as $data)
                                        @foreach ($data->items as $item)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <th class="text-center px-2 py-2">
                                                    {{ $counter++ }}
                                                </th>
                                                <td scope="row" class="px-2 py-2 font-medium">
                                                    {{ $item->created_at->format('d/m/y') }}
                                                </td>
                                                <td scope="row" class="px-2 py-2 font-medium">
                                                    {{ $data->type == 'bhp' ? 'BHP' : 'Alat' }}
                                                </td>
                                                <td scope="row"
                                                    class="px-2 py-2 font-medium whitespace-nowrap capitalize">
                                                    ({{ $item->product_code }})
                                                    {{ $item->asset->product_name }}
                                                    {{ $item->asset->merk ? "({$item->asset->merk})" : '' }}
                                                    {{ $item->asset->product_detail ? "({$item->asset->product_detail})" : '' }}
                                                </td>
                                                <td scope="row" class="px-2 py-2">
                                                    {{ $item->asset->product_type }}
                                                </td>
                                                <td scope="row"
                                                    class="px-2 py-2 font-medium whitespace-nowrap text-right">
                                                    <span class="px-2 py-1 text-white rounded-full bg-uinOrange">
                                                        Rp. {{ number_format($item->unit_price ?? 0, 0, ',', '.') }},-
                                                    </span>
                                                </td>
                                                <td scope="row" class="px-2 py-2 font-medium text-right">
                                                    {{ $item->formatted_quantity }}
                                                </td>
                                                <td scope="row" class="px-2 py-2 font-medium">
                                                    -
                                                </td>
                                                <td scope="row" class="px-2 py-2 font-medium">
                                                    {{ $item->asset->product_unit }}
                                                </td>
                                                <td class="pr-6 pl-2 font-semibold text-right whitespace-nowrap">
                                                    <span class="px-2 py-1  text-white rounded-full bg-uinBlue">
                                                        Rp.
                                                        {{ number_format($item->total_price ?? 0, 0, ',', '.') }},-
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @foreach ($data->loans as $item)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <th class="text-center px-2 py-2">
                                                    {{ $counter++ }}
                                                </th>
                                                <td scope="row" class="px-2 py-2 font-medium">
                                                    {{ $item->created_at->format('d/m/y') }}
                                                </td>
                                                <td scope="row" class="px-2 py-2 font-medium">
                                                    {{ $data->type == 'bhp' ? 'BHP' : 'Alat' }}
                                                </td>
                                                <td scope="row"
                                                    class="px-2 py-2 font-medium whitespace-nowrap capitalize">
                                                    ({{ $item->product_code }})
                                                    {{ $item->asset->product_name }}
                                                    {{ $item->asset->merk ? "{$item->asset->merk}" : '' }}
                                                    {{ $item->asset->product_detail ? "({$item->asset->product_detail})" : '' }}
                                                </td>
                                                <td scope="row" class="px-2 py-2">
                                                    {{ $item->asset->product_type }}
                                                </td>
                                                <td scope="row"
                                                    class="px-2 py-2 font-medium whitespace-nowrap text-right">
                                                    <span class="px-2 py-1 text-white rounded-full bg-uinOrange">
                                                        Rp.
                                                        {{ number_format($item->rental_price ?? 0, 0, ',', '.') }},-
                                                    </span>
                                                </td>
                                                <td scope="row" class="px-2 py-2 font-medium text-right">
                                                    {{ intval($item->quantity) }}
                                                    {{ $item->asset->latestPrice->price_type == 'unit' ? '' : $item->asset->product_unit }}
                                                </td>
                                                <td scope="row" class="px-2 py-2 font-medium text-right">
                                                    {{ $item->asset->latestPrice->price_type == 'unit'
                                                        ? '-'
                                                        : rtrim(rtrim(number_format($item->rental, 4, ',', '.'), '0'), ',') }}
                                                </td>
                                                <td scope="row" class="px-2 py-2 font-medium">
                                                    {{ $item->asset->latestPrice->price_type == 'unit' ? $item->asset->product_unit : ($item->asset->latestPrice->price_type == 'sample' ? 'sample' : 'jam') }}
                                                </td>
                                                <td class="pr-6 pl-2 font-semibold text-right whitespace-nowrap">
                                                    <span class="px-2 py-1  text-white rounded-full bg-uinBlue">
                                                        Rp.
                                                        {{ number_format($item->total_price ?? 0, 0, ',', '.') }},-
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @empty
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <th colspan="12" class="px-2 py-2 text-center">
                                                Data Tidak Ditemukan
                                            </th>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('filter', () => ({
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
