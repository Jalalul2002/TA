<x-app-layout>
    @php
        $kodeBarang = [
            '' => '700',
            'Matematika' => '701',
            'Biologi' => '702',
            'Fisika' => '703',
            'Kimia' => '704',
            'Teknik Informatika' => '705',
            'Agroteknologi' => '706',
            'Teknik Elektro' => '707',
        ];
        $defaultKode = $kodeBarang[$dataTransaksi->location] ?? '700';
    @endphp
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('penggunaan') }}">
                {{ __('◀️ Detail Penggunaan Barang') }} {{ $dataTransaksi->name }}
            </a>
        </h2>
    </x-slot>
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
    <div class="py-4" x-data="{ openEdit: false }">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center space-x-1 xl:space-x-2 justify-end mb-4">
                <a href="{{ route('penggunaan.export.id', $dataTransaksi->id) }}">
                    <div
                        class="inline-flex gap-x-2 items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 duration-300 transition-all">
                        <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Layer_1"
                            data-name="Layer 1" viewBox="0 0 24 24">
                            <path
                                d="M23.971,17.98c-.191,1.175-1.215,2.028-2.436,2.028-.664,0-1.301-.196-1.84-.553-.387-.257-.447-.801-.118-1.129l.002-.002c.236-.235,.602-.283,.886-.11,.316,.191,.689,.298,1.07,.298,.604,0,1.031-.404,1.096-.795,.088-.55-.602-.873-.817-.959-.915-.371-1.751-.78-1.753-.78-.692-.484-1.027-1.231-.921-2.049,.111-.845,.674-1.534,1.471-1.796,.839-.277,1.603-.076,2.152,.203,.405,.205,.518,.733,.24,1.093-.203,.263-.559,.358-.863,.225-.306-.134-.71-.226-1.137-.088-.412,.136-.517,.447-.515,.577,.005,.442,.335,.57,.433,.615,.282,.128,.806,.368,1.366,.595,1.461,.591,1.828,1.745,1.685,2.627Zm-8.368-5.972h0c-.442,0-.8,.358-.8,.8v6.401c0,.442,.358,.8,.8,.8h2.133c.442,0,.8-.358,.8-.8s-.358-.8-.8-.8c-.755,0-1.333,0-1.333,0v-5.601c0-.442-.358-.8-.8-.8Zm-2.612,0c-.307,0-.589,.174-.726,.449l-.864,1.733-.864-1.733c-.137-.275-.418-.449-.726-.449-.602,0-.994,.634-.726,1.173l1.409,2.827-1.409,2.827c-.269,.539,.123,1.173,.726,1.173h0c.307,0,.589-.174,.726-.449l.864-1.733,.864,1.733c.137,.275,.418,.449,.726,.449,.602,0,.994-.634,.726-1.173l-1.409-2.827,1.409-2.827c.269-.539-.123-1.173-.726-1.173Zm7.009,10.992c0,.553-.447,1-1,1H5c-2.757,0-5-2.243-5-5V5C0,2.243,2.243,0,5,0h4.515c1.869,0,3.627,.728,4.95,2.05l3.484,3.486c.888,.887,1.521,2,1.833,3.217,.076,.299,.011,.617-.179,.861s-.481,.387-.79,.387h-5.813c-1.654,0-3-1.346-3-3V2.023c-.16-.015-.322-.023-.485-.023H5c-1.654,0-3,1.346-3,3v14c0,1.654,1.346,3,3,3h14c.553,0,1,.447,1,1ZM12,7c0,.551,.448,1,1,1h4.339c-.22-.382-.489-.736-.804-1.05l-3.484-3.486c-.318-.318-.671-.587-1.051-.805V7Z" />
                        </svg>
                        Export
                    </div>
                </a>
                <a href="{{ route('penggunaan.print.id', $dataTransaksi->id) }}" target="_blank">
                    <div
                        class="inline-flex gap-x-2 items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-fuchsia-500 hover:bg-fuchsia-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 duration-300 transition-all">
                        <svg class="size-4 fill-white" xmlns="http://www.w3.org/2000/svg" id="Outline"
                            viewBox="0 0 24 24" width="512" height="512">
                            <path
                                d="M19,6V4a4,4,0,0,0-4-4H9A4,4,0,0,0,5,4V6a5.006,5.006,0,0,0-5,5v5a5.006,5.006,0,0,0,5,5,3,3,0,0,0,3,3h8a3,3,0,0,0,3-3,5.006,5.006,0,0,0,5-5V11A5.006,5.006,0,0,0,19,6ZM7,4A2,2,0,0,1,9,2h6a2,2,0,0,1,2,2V6H7ZM17,21a1,1,0,0,1-1,1H8a1,1,0,0,1-1-1V17a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Zm5-5a3,3,0,0,1-3,3V17a3,3,0,0,0-3-3H8a3,3,0,0,0-3,3v2a3,3,0,0,1-3-3V11A3,3,0,0,1,5,8H19a3,3,0,0,1,3,3Z" />
                            <path d="M18,10H16a1,1,0,0,0,0,2h2a1,1,0,0,0,0-2Z" />
                        </svg>
                        Print
                    </div>
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg p-6">
                    <div class="flex flex-col space-y-4 mb-5">
                        <div class="flex justify-between border-b pb-4">
                            <div class="flex items-center gap-x-8">
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Detail Pengguna</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ "{$dataTransaksi->user_id} - {$dataTransaksi->name}" }}
                                    </h1>
                                </div>
                            </div>
                            <div class="flex items-center gap-x-8">
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Nomor Telepon</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ "{$dataTransaksi->telp}" }}
                                    </h1>
                                </div>
                            </div>
                            <div class="flex items-center gap-x-8">
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Prodi Pengguna</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ "{$dataTransaksi->prodi}" }}
                                    </h1>
                                </div>
                            </div>
                            <div class="flex items-center gap-x-8">
                                <div>
                                    <h2 class="text-gray-500 text-sm font-medium">Keterangan</h2>
                                    <h1 class="text-lg font-semibold items-baseline text-gray-900">
                                        {{ "{$dataTransaksi->detail}" }}
                                    </h1>
                                </div>
                            </div>
                            <div>
                                <h2 class="text-gray-500 text-sm font-medium">Diupdate</h2>
                                <h1 class="text-lg font-semibold text-gray-900">
                                    {{ $dataTransaksi->updater->name . ' | ' . $dataTransaksi->updated_at->format('d M Y H:i') }}
                                </h1>
                            </div>
                            <div>
                                <span
                                    class="text-sm font-bold rounded-full px-4 py-2 bg-yellow-100 text-yellow-500">Barang
                                    Keluar</span>
                            </div>
                        </div>
                    </div>
                    <div class="pb-1">
                        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                    <div class="relative overflow-x-auto sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                            @php
                                $columns = [
                                    'product_code' => 'Kode Barang',
                                    'product_name' => 'Nama Barang',
                                    'product_detail' => 'Keterangan/Formula',
                                    'merk' => 'Merk',
                                    'product_type' => 'Jenis Produk',
                                    'stock' => 'stok',
                                    'jumlah_pemakaian' => 'Jumlah Penggunaan',
                                    'updated_stock' => 'Sisa Stok',
                                    'product_unit' => 'satuan',
                                ];
                            @endphp
                            <thead class="text-xs text-white uppercase bg-uinTosca">
                                <tr>
                                    <th scope="col" class="px-1 text-center py-3">
                                        No
                                    </th>
                                    @foreach ($columns as $field => $name)
                                        <th scope="col" class="py-3">
                                            <div class="flex items-center">
                                                {{ $name }}
                                                @php
                                                    // Tentukan arah sorting berdasarkan field yang sedang diurutkan
                                                    $newSortOrder =
                                                        request('sort_field') === $field &&
                                                        request('sort_order') === 'asc'
                                                            ? 'desc'
                                                            : 'asc';
                                                    $isActive = request('sort_field', 'product_name') === $field;
                                                @endphp
                                                <a title="Sort by {{ $name }}"
                                                    href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->query(), ['sort_field' => $field, 'sort_order' => $newSortOrder])) }}">
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
                                    $counter = ($products->currentPage() - 1) * $products->perPage() + 1;
                                @endphp
                                @forelse ($products as $product)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th class="px-1 text-center py-4">
                                            {{ $counter }}
                                        </th>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ $product->product_code }}
                                        </td>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ $product->asset->product_name }}
                                        </td>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ empty($product->asset->product_detail) ? '-' : $product->asset->product_detail }}
                                        </td>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ empty($product->asset->merk) ? '-' : $product->asset->merk }}
                                        </td>
                                        <td scope="row" class="px-1 py-4 font-medium whitespace-nowrap">
                                            {{ $product->asset->product_type }}
                                        </td>
                                        <td class="px-1 py-4">
                                            {{ $product->stock }}
                                        </td>
                                        <td class="px-1 py-4 font-bold text-gray-600">
                                            {{ $product->jumlah_pemakaian }}
                                        </td>
                                        <td class="px-1 py-4 font-bold text-gray-600">
                                            {{ $product->updated_stock }}
                                        </td>
                                        <td scope="row" class="px-1 py-4 font-bold text-gray-600 whitespace-nowrap">
                                            {{ $product->asset->product_unit }}
                                        </td>
                                    </tr>
                                    @php
                                        $counter++;
                                    @endphp
                                @empty
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th colspan="10" class="px-6 py-4 text-center">
                                            Data Tidak Ditemukan
                                        </th>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $products->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
